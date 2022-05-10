<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Voiture;
use App\Entity\Marque;
use App\Repository\MarqueRepository;

class VoitureController extends AbstractController
{
    /**
     * @Route("/listeVoiture", name="listeVoiture")
     */
    public function liste(Request $request)
    { //recuperation du repository grace au manager
        $em = $this->getDoctrine()->getManager();
        $voitureRepository = $em->getRepository(Voiture::class);
        //personneRepository herite de servciceEntityRepository ayant les methodes pour recuperer les données de la bdd
        $listeVoiture = $voitureRepository->findAll();
        $resultat = [];
        foreach ($listeVoiture as $voit) {
            array_push($resultat, $voit->getNom());
        }
        $reponse = new JsonResponse($resultat);


        return $reponse;
    }

    /**
     * @Route("/insertVoiture", name="insertVoiture", methods={"POST"})
     */
    public function insert(Request $request)
    {
        $voit = new Voiture();
        $donnees = json_decode($request->getContent(),true);
        $marque = $this->getDoctrine()->getRepository(Marque::class)->find($donnees['marque_id']);
        $voit->setMarque($marque);
        $voit->setNbPlaces($donnees['nb_places']);
        $voit->setModele($donnees['modele']);

        if ($request->isMethod('post')) {
            //récupération de l'entityManager pour insérer les données en bdd
            $em = $this->getDoctrine()->getManager();

            $em->persist($voit);
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
     * @Route("/deleteVoiture/{id}", name="deleteVoiture",requirements={"id"="[0-9]{1,5}"})
     */
    public function delete(Request $request, $id)
    {
        //récupération du Manager et du repository pour accéder à la bdd
        $em = $this->getDoctrine()->getManager();
        $voitRepository = $em->getRepository(Voiture::class);
        //requete de selection
        $voit = $voitRepository->find($id);
        //suppression de l'entity
        $em->remove($voit);
        $em->flush();
        $resultat = ["ok"];
        $reponse = new JsonResponse($resultat);
        return $reponse;
    }
}
