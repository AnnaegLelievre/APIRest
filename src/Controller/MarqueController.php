<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Marque;

class MarqueController extends AbstractController
{
    /**
     * @Route("/listeMarque", name="listeMarque")
     */
    public function liste(Request $request)
    { //recuperation du repository grace au manager
        $em = $this->getDoctrine()->getManager();
        $marqueRepository = $em->getRepository(Marque::class);
        //personneRepository herite de servciceEntityRepository ayant les methodes pour recuperer les données de la bdd
        $listeMarque = $marqueRepository->findAll();
        $resultat = [];
        foreach ($listeMarque as $marq) {
            array_push($resultat, $marq->getNom());
        }
        $reponse = new JsonResponse($resultat);


        return $reponse;
    }

    /**
     * @Route("/insertMarque", name="insertMarque", methods={"POST"})
     */
    public function insert(Request $request)
    {
        $marq = new Marque();
        $donnees = json_decode($request->getContent(),true);
        $marq->setNom($donnees['nom']);
        if ($request->isMethod('post')) {
            //récupération de l'entityManager pour insérer les données en bdd
            $em = $this->getDoctrine()->getManager();

            $em->persist($marq);
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
     * @Route("/deleteMarque/{id}", name="deleteMarque",requirements={"id"="[0-9]{1,5}"})
     */
    public function delete(Request $request, $id)
    {
        //récupération du Manager et du repository pour accéder à la bdd
        $em = $this->getDoctrine()->getManager();
        $marqueRepository = $em->getRepository(Marque::class);
        //requete de selection
        $marq = $marqueRepository->find($id);
        //suppression de l'entity
        $em->remove($marq);
        $em->flush();
        $resultat = ["ok"];
        $reponse = new JsonResponse($resultat);
        return $reponse;
    }
}
