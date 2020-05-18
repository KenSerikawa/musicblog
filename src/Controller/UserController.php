<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Services\FileUploader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private $repository;

    public function __construct(UserRepository $userRepository) 
    {
        $this->repository = $userRepository;
    }

    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    public function show(int $id, PostRepository $postRepository)
    {
        $user = $this->repository->find($id);
        // WATCH! Another solution could be better  
        $user_posts = $postRepository->findBy(['user' => $id]);
        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'posts' => $user_posts
        ]);
    }

    public function register(
        Request $request, 
        UserPasswordEncoderInterface $passwordEncoder, 
        FileUploader $fileUploader)
    {
        $newUser = new User();
        $newUser->setUuid();
        
        $form = $this->createForm(UserType::class, $newUser);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            /* @var UploadedFile $file */
            $file = $request->files->get('user')['profile_image'];

            if($file) {
                $filename = $fileUploader->updateFile($file, 'images');
                $user->setProfileImage($filename);
            }

            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('app_login') ;
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function update(Request $request, int $id, UserPasswordEncoderInterface $passwordEncoder, FileUploader $fileUploader)
    {
        $user = $this->repository->find($id);
        
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
                
        if($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            /* @var UploadedFile $file */
            $file = $request->files->get('user')['profile_image'];

            if($file) {
                $filename = $fileUploader->updateFile($file, 'images');
                $user->setProfileImage($filename);
            }

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'User updated!');


            return $this->redirectToRoute('user.profile', ['id' => $id]) ;
        }

        return $this->render('user/update.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
}
