<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Services\FileUploader;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    private $repository;

    public function __construct(PostRepository $postRepository)
    {
        $this->repository = $postRepository;
    }
    public function index()
    {
        $posts = $this->repository->findAll();
        

        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);
    }

    public function create(Request $request, FileUploader $fileUploader) 
    {
        $post = new Post();
        $post->setUuid();

        $form = $this->createForm(PostType::class, $post);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $post->setCreatedAt();

            /* @var UploadedFile $file */
            $file = $request->files->get('post')['trackname'];

            if($file) {
  
                $filename = $fileUploader->updateFile($file, 'audio');
                
                $post->setTrackname($filename);
  
            }

            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('post.index');
        }

        return $this->render('post/form.html.twig', [
            'form' => $form->createView()
        ]);

    }
}
