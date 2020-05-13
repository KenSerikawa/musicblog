<?php

namespace App\Controller;

use App\Entity\Post;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    public function index()
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

    public function create(Request $request) 
    {
        $post = new Post();
        $post->setUuid();

        $form = $this->createForm(PostType::class, $post);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $post->setCreatedAt(new \DateTime());
            
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('posts');
        }

        return $this->render('project/form.html.twig', [
            'form' => $form->createView()
        ]);

    }
}
