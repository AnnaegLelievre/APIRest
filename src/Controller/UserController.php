<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/listeUser", name="listeUser")
     */
    public function liste(Request $request)
    { //recuperation du repository grace au manager
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository(User::class);
        //personneRepository herite de servciceEntityRepository ayant les methodes pour recuperer les données de la bdd
        $listeUser = $userRepository->findAll();
        $resultat = [];
        foreach ($listeUser as $user) {
            array_push($resultat, $user->getUsername());
        }
        $reponse = new JsonResponse($resultat);


        return $reponse;
    }

    /**
     * @Route("/insertUser", name="insertUser", methods={"POST"})
     */
    public function insert(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $donnees = json_decode($request->getContent(),true);
        $user->setUsername($donnees['username']);
        $user->setRoles($donnees['roles']);
        $user->setPassword($encoder->encodePassword($user, $donnees['password']));
        $user->setApiToken($donnees['apiToken']);
        
        if ($request->isMethod('post')) {
            //récupération de l'entityManager pour insérer les données en bdd
            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            //insertion en bdd
            $em->flush();
            $resultat = ["ok"];
        } else {
            $resultat = ["nok"];
        }

        $reponse = new JsonResponse($resultat);
        return $reponse;
    }

    /**
     * @Route("/deleteUser/{id}", name="deleteUser",requirements={"id"="[0-9]{1,5}"})
     */
    public function delete(Request $request, $id)
    {
        //récupération du Manager et du repository pour accéder à la bdd
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository(User::class);
        //requete de selection
        $user = $userRepository->find($id);
        //suppression de l'entity
        $em->remove($user);
        $em->flush();
        $resultat = ["ok"];
        $reponse = new JsonResponse($resultat);
        return $reponse;
    }

    /**
     * @Route("/login/{username}/{password}", name="login",requirements={"username"="[a-z]{4,30}", "password"="[a-z]{4,30}"})
     */
    public function login(Request$request, $username, $password, UserPasswordEncoderInterface $passwordEncoder)
    {
        //TODO: prend en argument un login et un mdp et retourne un token

        if($request->isMethod('get')){
            $em = $this->getDoctrine()->getManager();
            $userRepository = $em->getRepository(User::class);
            $user = $userRepository->findOneBy(['username' => $username]);
     
            if ($passwordEncoder->isPasswordValid($user, $password )) {
                $apiToken = $user->getApiToken();
                $resultat=[$apiToken];
            } else {
                $resultat=["Log failed"];
            }

            $reponse = new JsonResponse($resultat); 
            return $reponse;

        }
    }

    /**
     * @Route("/register/{username}/{password}", name="register",requirements={"username"="[a-z]{4,30}", "password"="[a-z]{4,30}"})
     */
    public function register(Request$request, $username, $password, UserPasswordEncoderInterface $passwordEncoder)
    {
        //TODO: enregiste un login et un mdp et retourne un token

        if($request->isMethod('get')){
            $user = new User;
            $user->setUsername($username);
            $encodePassword = $passwordEncoder->encodePassword($user, $password);
            $user->setPassword($encodePassword);
    
            $token = base_convert(hash('sha256', time() . mt_rand()), 16, 36);
            $user->setApiToken($token);
    
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
    
            $apiToken = $user->getApiToken();
            $resultat=[$apiToken];
        } else {
            $resultat=["Register failed"];
        }

        $reponse = new JsonResponse($resultat); 
        return $reponse;

    }
}
