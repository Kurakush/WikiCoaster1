<?php

namespace App\Controller;

use App\Entity\Coaster;
use App\Form\CoasterType;
use App\Repository\CategoryRepository;
use App\Repository\CoasterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ParkRepository;


class CoasterController extends AbstractController
{
    #[Route(path: '/coaster')]
    public function index(CoasterRepository $coasterRepository, 
    ParkRepository $parkRepository, 
    CategoryRepository $categoryRepository,
    Request $request,
    ): Response {

        //récupère toutes les entités Coaster de la BD
        // $entities = $coasterRepository->findAll();
        $parks = $parkRepository->findAll();
        $categories = $categoryRepository->findAll();

        $parkId = (int) $request->query->get('park');
        $categoryId = (int) $request->query->get('category');
        $search = (string) $request->query->get('search','');

        $count = 20;
        $page = (int) $request->query->get('p',1);

        $entities = $coasterRepository->findFiltered($parkId, $categoryId, $search, $count, $page);
        $pageCount = max(ceil($entities->count() / $count), 1);

        return $this->render('coaster/index.html.twig', [
            'controller_name' => 'CoasterController',
            'entities' => $entities, //envoie les entités à la vue
            'parks' => $parks,
            'categories' => $categories,
            'parkId' => $parkId,
            'categoryId' => $categoryId,
            'search' => $search,
            'pageCount' => $pageCount, // Nombre de pages
            'page' => $page, // Page courante

        ]);
    }


    #[Route(path: '/coaster/add')]
    public function add(EntityManagerInterface $em, Request $request): Response
    {
        $coaster = new Coaster();

        //création d'un formulaire CoasterType avec l'entité comme donnée
        $form = $this->createForm(CoasterType::class, $coaster);

        //envoi des données $_POST
        $form->handleRequest($request);

        // On s'assure que le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($coaster);
            $em->flush();

            //redirection 


        }


        return $this->render('coaster/add.html.twig', [
            'coasterForm' => $form,
        ]);



        /*$coaster->setName('Space Mountains');
        $coaster->setLength(600);
        $coaster->setMaxHeight(50);
        $coaster->setMaxSpeed(90);
        $coaster->setOperating(true);


        //Demande à doctrine d'observer l'entité
        $em->persist($coaster);

        //Met à jour la DB
        $em->flush();


        return new Response('Coaster créé !');*/
    }

// {id} est un parametre, <\d+> indique que c'est un entier de 1 ou plusieurs chiffres
    #[Route(path: '/coaster/{id<\d+>}/edit')]
    public function edit(Coaster $entity, Request $request, EntityManagerInterface $em):Response
    {
        dump($entity);
        $form = $this->createForm(CoasterType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //L'entité est déjà gérée par Doctrine, pas besoin de faire un persist
            $em->flush();

            //redirection vers l'index
            return $this->redirectToRoute('app_coaster_index');
        }


        return $this->render('coaster/edit.html.twig', [
            'coasterForm' => $form,
            
        ]);

    }

    #[Route(path: '/coaster/{id<\d+>}/remove')]
    public function remove(Coaster $entity, Request $request, EntityManagerInterface $em): Response
    {

        if ($this->isCsrfTokenValid('delete'.$entity->getId(), $request->request->get('_token'))) {
           
        

        $em->remove($entity);
        $em->flush();

        return $this->redirectToRoute('app_coaster_index');
    }

        return $this -> render('coaster/remove.html.twig', [
            'coaster' => $entity,
        ]);
    }
}