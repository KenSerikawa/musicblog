<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CategoryController extends AbstractController
{

    private $repository;
    private $tokenStorage;

    public function __construct(CategoryRepository $categoryRepository, TokenStorageInterface $tokenStorage)
    {
        $this->repository = $categoryRepository;
        $this->tokenStorage = $tokenStorage;
    }

    public function index()
    {
        $categories = $this->repository->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
            'user' => $this->tokenStorage->getToken()->getUser()
        ]);
    }

    public function save(Request $request)
    {
        $category = new Category();
        
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        $form->getErrors();

        if($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();
            
            $category->setCreatedAt();
            $category->setUser($this->tokenStorage->getToken()->getUser());

            $em = $this->getDoctrine()->getManager();

            $em->persist($category);

            $em->flush();

            $this->redirectToRoute('category.index');
        }

        return $this->render('category/form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function saveFromPost(Category $category) 
    {
        $em = $this->getDoctrine()->getManager();

        $category->setCreatedAt();
        $category->setUser($this->tokenStorage->getToken()->getUser());

        $em->persist($category);

        $em->flush();

    }

}
