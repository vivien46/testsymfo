<?php

namespace App\Controller;

use App\Entity\Entity1;
use App\Form\EntityFormType;
use App\Repository\Entity1Repository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestsymfoController extends AbstractController
{
    #[Route('/', name: 'testsymfo_app', methods:['GET', 'POST'])]
    public function index(Entity1Repository $repo, Request $request): Response
    {
        $sortField = $request->query->get('sort', null); // Utilise 'null' pour détecter l'absence du paramètre
        $sortOrder = $request->query->get('order', null);

        if ($sortField === null || $sortOrder === null) {
            // Si 'sort' ou 'order' n'est pas fourni, utilise le comportement par défaut
            $sortField = 'id';
            $sortOrder = 'asc';
        } else {
            // Valide les champs de tri pour éviter toute manipulation indésirable
            $validFields = ['id', 'firstName', 'name', 'birthDate', 'type'];
            $sortField = in_array($sortField, $validFields) ? $sortField : 'id';
            $sortOrder = in_array($sortOrder, ['asc', 'desc']) ? $sortOrder : 'asc';
        }

        $entities = $repo->findBy([], [$sortField => $sortOrder]);
        // $entities = $repo->findBy([],['birthDate' => 'desc']);

        return $this->render('testsymfo/index.html.twig', [
            'entities' => $entities,
        ]);
    }

    #[Route('testsymfo/new', name: 'testsymfo_new', methods:['GET', 'POST'])]
    public function new(Entity1 $entity, EntityManagerInterface $manager, Request $request)
    {
        $entity = new Entity1;

        $form = $this->createForm(EntityFormType::class, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           $manager->persist($entity);
           $manager->flush();

           $this->addFlash('success',"Les informations ont été enregistrées avec succès");

           return $this->redirectToRoute('testsymfo_app');
        }
        return $this->render('testsymfo/new.html.twig',[
            'entity' => $entity,
            'entityForm' => $form->createView(),
            'editMode' => false,
        ]);
    }

    #[Route('testsymfo/edit/{id}', name: 'testsymfo_edit', methods:['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $manager, Entity1Repository $repo, $id)
    {
        $entity = $repo->find($id);

        if (!$entity) {
            throw $this->createNotFoundException("L'enregistrement recherché n'a pas été trouvé");
        }

        $form = $this->createForm(EntityFormType::class, $entity);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ){
            $manager->flush();

            $this->addFlash('success',"Les informations ont été modifiées avec succès");

            return $this->redirectToRoute('testsymfo_show', [
                'id' => $entity->getId()
            ]);
        }

        return $this->render('testsymfo/new.html.twig', [
            'entityForm' => $form->createView(),
            'editMode' => true
        ]);
    }

    #[Route('register/{id}', name: 'testsymfo_show', methods:['GET', 'POST'])]
    public function show(Entity1Repository $repo, $id) : Response
    {
        $entity = $repo->find($id);

        return $this->render('testsymfo/show.html.twig', [
            'entity' => $entity,
        ]);
    }

    #[Route('register/delete/{id}', name: 'testsymfo_delete', methods: ['GET', 'POST'])]
    public function delete(EntityManagerInterface $manager, Entity1Repository $repo, $id) : Response
    {
        $entity = $repo->find($id);

        if (!$entity) {
            throw $this->createNotFoundException("L'enregistrement avec l'ID ' .$id ' n'existe pas");
        }
        $manager->remove($entity);
        $manager->flush();

        $this->addFlash('success', "L'enregistrement à été supprimé avec succès.");

        return $this->redirectToRoute('testsymfo_app');
    }
}
