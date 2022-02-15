<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\InscriptionType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHash): Response
    {
        $user = new Users();

        $form = $this->createForm(InscriptionType::class, $user);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $hash = $passwordHash->hashPassword($user,$user->getPassword());
            $user->setPassword($hash);

            $user->setUrlImg("default.png");
            $user->setRoles(["ROLE_USER"]);
           
            $manager = $doctrine->getManager();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', 'Bienvenue parmis nous !');

        }

        return $this->render('home/index.html.twig', [
            'register' => $form->createView(),
        ]);
    }

    #[Route('/login', name:'login')]
    public function login(AuthenticationUtils $auth):Response
    {

        dump($auth);
        $error = $auth->getLastAuthenticationError();

        $lastUserName = $auth->getLastUsername();

        return $this->render('home/connexion.html.twig', [
            'last_username' => $lastUserName,
            'errors' => $error
        ]);
    }
}