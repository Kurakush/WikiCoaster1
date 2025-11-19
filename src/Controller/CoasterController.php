<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Coaster;
use App\Form\CoasterType;
use App\Repository\CoasterRepository;


class CoasterController extends AbstractController
{   
    #[Route(path: '/coaster')]
    public function index(CoasterRepository $coasterRepository): Response {
        $entities = $coasterRepository->findAll();

        return $this->render('coaster/index.html.twig', [
            'controller_name' => 'CoasterController',
            'coasters' => $entities, // envoie les entités à la vue
        ]);
    }


    #[Route(path: '/coaster/add')]
    public function add(EntityManagerInterface $em, Request $request):Response
    {
        $coaster = new Coaster();

        // création d'un formulaire
        $form = $this->createForm(CoasterType::class, $coaster);

        // envoi des données
        $form->handleRequest($request);

        // On s'assure que le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // on sauvegarde en base
            $em->persist($coaster);
            $em->flush();

            // redirection vers la liste des montagnes russes
        
        }

        return $this->render('coaster/add.html.twig', [
            'form' => $form->createView(),
        ]);

    }
    
}