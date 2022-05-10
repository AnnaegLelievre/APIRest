<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Inscription;
use App\Entity\Trajet;
use App\Entity\Personne;

class InscriptionController extends AbstractController
{
    /**
     * @Route("/listeInscription", name="listeInscription")
     */
    public function liste(Request $request)
    { //recuperation du repository grace au manager
        $em = $this->getDoctrine()->getManager();
        $inscriptionRepository = $em->getRepository(Inscription::class);
        //personneRepository herite de servciceEntityRepository ayant les methodes pour recuperer les données de la bdd
        $listeInscription = $inscriptionRepository->findAll();
        $resultat = [];
        foreach ($listeInscription as $insc) {
            array_push($resultat, $insc->getId());
        }
        $reponse = new JsonResponse($resultat);


        return $reponse;
    }

    /**
     * @Route("/insertInscription", name="insertInscription", methods={"POST"})
     */
    public function insert(Request $request)
    {
        $insc = new Inscription();
        $donnees = json_decode($request->getContent(),true);
        $pers = $this->getDoctrine()->getRepository(Personne::class)->find($donnees['pers_id']);
        $traj = $this->getDoctrine()->getRepository(Trajet::class)->find($donnees['trajet_id']);

        $insc->setPers($pers);
        $insc->setTrajet($traj);
        if ($request->isMethod('post')) {
            //récupération de l'entityManager pour insérer les données en bdd
            $em = $this->getDoctrine()->getManager();

            $em->persist($insc);
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
     * @Route("/deleteInscription/{id}", name="deleteInscription",requirements={"id"="[0-9]{1,5}"})
     */
    public function delete(Request $request, $id)
    {
        //récupération du Manager et du repository pour accéder à la bdd
        $em = $this->getDoctrine()->getManager();
        $inscriptionRepository = $em->getRepository(Inscription::class);
        //requete de selection
        $insc = $inscriptionRepository->find($id);
        //suppression de l'entity
        $em->remove($insc);
        $em->flush();
        $resultat = ["ok"];
        $reponse = new JsonResponse($resultat);
        return $reponse;
    }
}
