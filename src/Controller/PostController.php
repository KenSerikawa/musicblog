<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Services\FileUploader;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PostController extends AbstractController
{
    private $repository;

    public function __construct(PostRepository $postRepository, TokenStorageInterface $tokenStorage)
    {
        $this->repository = $postRepository;
        $this->user = $tokenStorage->getToken()->getUser();
    }
    
    public function index()
    {
        $posts = $this->repository->findAll();
        
        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);
    }

    public function create(Request $request, FileUploader $fileUploader, CategoryController $categoryController) 
    {
        $post = new Post();
        $post->setUuid();
        $post->setUser($this->user);

        $form = $this->createForm(PostType::class, $post);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $post->setCreatedAt();

            if($post->getCategory() !== null) {
                $categoryController->saveFromPost($post->getCategory());
            }

            $trackfile = $request->files->get('post')['trackname'];

            if($trackfile) {
                $filename = $fileUploader->updateFile($trackfile, 'audio');
                $post->setTrackname($filename);
            }

            $imagefile = $request->files->get('post')['imagename'];

            if($imagefile) {
                $filename = $fileUploader->updateFile($imagefile, 'images');
                $post->setImagename($filename);
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
