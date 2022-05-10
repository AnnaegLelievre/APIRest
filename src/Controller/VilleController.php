<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Ville;

class VilleController extends AbstractController
{
    /**
     * @Route("/listeVille", name="listeVille")
     */
    public function liste(Request $request)
    { //recuperation du repository grace au manager
        $em = $this->getDoctrine()->getManager();
        $villeRepository = $em->getRepository(Ville::class);
        //personneRepository herite de servciceEntityRepository ayant les methodes pour recuperer les données de la bdd
        $listeVille = $villeRepository->findAll();
        $resultat = [];
        foreach ($listeVille as $traj) {
            array_push($resultat, $traj->getVille());
        }
        $reponse = new JsonResponse($resultat);


        return $reponse;
    }

    /**
     * @Route("/insertVille", name="insertVille", methods={"POST"})
     */
    public function insert(Request $request)
    {
        $vill = new Ville();
        $donnees = json_decode($request->getContent(),true);
        $vill->setVille($donnees['ville']);
        $vill->setCodepostal($donnees['codepostal']);

        if ($request->isMethod('post')) {
            //récupération de l'entityManager pour insérer les données en bdd
            $em = $this->getDoctrine()->getManager();

            $em->persist($vill);
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
     * @Route("/deleteVille/{id}", name="deleteVille",requirements={"id"="[0-9]{1,5}"})
     */
    public function delete(Request $request, $id)
    {
        //récupération du Manager et du repository pour accéder à la bdd
        $em = $this->getDoctrine()->getManager();
        $villeRepository = $em->getRepository(Ville::class);
        //requete de selection
        $vill = $villeRepository->find($id);
        //suppression de l'entity
        $em->remove($vill);
        $em->flush();
        $resultat = ["ok"];
        $reponse = new JsonResponse($resultat);
        return $reponse;
    }
}
