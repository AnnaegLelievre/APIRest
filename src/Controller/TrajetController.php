<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Trajet;
use App\Entity\Ville;
use DateTime;

class TrajetController extends AbstractController
{
    /**
     * @Route("/listeTrajet", name="listeTrajet")
     */
    public function liste(Request $request)
    { //recuperation du repository grace au manager
        $em = $this->getDoctrine()->getManager();
        $trajetRepository = $em->getRepository(Trajet::class);
        //personneRepository herite de servciceEntityRepository ayant les methodes pour recuperer les données de la bdd
        $listeTrajet = $trajetRepository->findAll();
        $resultat = [];
        foreach ($listeTrajet as $traj) {
            array_push($resultat, $traj->getId());
        }
        $reponse = new JsonResponse($resultat);


        return $reponse;
    }

    /**
     * @Route("/insertTrajet", name="insertTrajet", methods={"POST"})
     */
    public function insert(Request $request)
    {
        $traj = new Trajet();
        $donnees = json_decode($request->getContent(),true);
        $ville_dep = $this->getDoctrine()->getRepository(Ville::class)->find($donnees['ville_dep_id']);
        $ville_arr = $this->getDoctrine()->getRepository(Ville::class)->find($donnees['ville_arr_id']);
        $traj->setVilleDep($ville_dep);
        $traj->setVilleArr($ville_arr);
        $traj->setNbKm($donnees['nb_km']);
        $traj->setDateTrajet(\DateTime::createFromFormat('Y-m-d', $donnees['DateTrajet']));
        if ($request->isMethod('post')) {
            //récupération de l'entityManager pour insérer les données en bdd
            $em = $this->getDoctrine()->getManager();

            $em->persist($traj);
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
     * @Route("/deleteTrajet/{id}", name="deleteTrajet",requirements={"id"="[0-9]{1,5}"})
     */
    public function delete(Request $request, $id)
    {
        //récupération du Manager et du repository pour accéder à la bdd
        $em = $this->getDoctrine()->getManager();
        $trajetRepository = $em->getRepository(Trajet::class);
        //requete de selection
        $traj = $trajetRepository->find($id);
        //suppression de l'entity
        $em->remove($traj);
        $em->flush();
        $resultat = ["ok"];
        $reponse = new JsonResponse($resultat);
        return $reponse;
    }
}
