<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Entity\Ville;
use App\Entity\Voiture;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ListePersonneController extends AbstractController
{
    /**
     * @Route("/listePersonne", name="liste")
     */
    public function liste(Request $request)
    { //recuperation du repository grace au manager
        $em = $this->getDoctrine()->getManager();
        $personneRepository = $em->getRepository(Personne::class);
        //personneRepository herite de servciceEntityRepository ayant les methodes pour recuperer les données de la bdd
        $listePersonnes = $personneRepository->findAll();
        $resultat = [];
        foreach ($listePersonnes as $pers) {
            array_push($resultat, $pers->getNom());
        }
        $reponse = new JsonResponse($resultat);


        return $reponse;
    }

    /**
     * @Route("/insertPersonne", name="insertPersonne", methods={"POST"})
     */
    public function insert(Request $request)
    {
        $pers = new Personne();
        $donnees = json_decode($request->getContent(),true);
        $ville = $this->getDoctrine()->getRepository(Ville::class)->find($donnees['ville_id']);
        $voiture = $this->getDoctrine()->getRepository(Voiture::class)->find($donnees['voiture_id']);
        $user = $this->getDoctrine()->getRepository(User::class)->find($donnees['user_id']);
        //$date = $this->getDoctrine()->getRepository(DateTime::class)->find($donnees['DateNaiss']);
        $pers->setNom($donnees['nom']);
        $pers->setPrenom($donnees['prenom']);
        $pers->setDateNaiss(\DateTime::createFromFormat('Y-m-d', $donnees['dateNaiss']));
       /* $pers->setTel($donnees['tel']);
        $pers->setEmail($donnees['email']);*/
        $pers->setVille($ville);
        $pers->setVoiture($voiture);
        $pers->setUser($user);

        if ($request->isMethod('post')) {
            //récupération de l'entityManager pour insérer les données en bdd
            $em = $this->getDoctrine()->getManager();

            $em->persist($pers);
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
     * @Route("/deletePersonne/{id}", name="deletePersonne",requirements={"id"="[0-9]{1,5}"})
     */
    public function delete(Request $request, $id)
    {
        //récupération du Manager et du repository pour accéder à la bdd
        $em = $this->getDoctrine()->getManager();
        $personneRepository = $em->getRepository(Personne::class);
        //requete de selection
        $pers = $personneRepository->find($id);
        //suppression de l'entity
        $em->remove($pers);
        $em->flush();
        $resultat = ["ok"];
        $reponse = new JsonResponse($resultat);
        return $reponse;
    }
}
