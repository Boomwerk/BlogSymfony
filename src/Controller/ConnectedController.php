<?php

namespace App\Controller;

use App\Entity\Users;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ConnectedController extends AbstractController
{

    #[Route('/check', name: 'check')]
    public function check(){

        if($this->getUser()->getRoles()[0] === "ROLE_USER"){
            return $this->redirectToRoute('profile');
        }else if($this->getUser()->getRoles()[0] === "ROLE_ADMIN"){
            return $this->redirectToRoute('admin');
        }

        

    }


    #[Route('/profile', name: 'profile')]
    public function index(): Response
    {

        $pseudo = $this->getUser()->getPseudo();
        
        return $this->render('connected/index.html.twig', [
            'pseudo' => $pseudo,
        ]);
    }

}
