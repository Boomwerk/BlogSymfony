<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\ImageType;
use App\Form\SettingInfoType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ConnectedController extends AbstractController
{

    #[Route('/check', name: 'check')]
    public function check()
    {

        if ($this->getUser()->getRoles()[0] === "ROLE_USER") {
            return $this->redirectToRoute('profile', ["id" => $this->getUser()->getId()]);
        } else if ($this->getUser()->getRoles()[0] === "ROLE_ADMIN") {
            return $this->redirectToRoute('admin');
        }
    }


   
    #[Route('/setting', name: 'setting')]
    public function setting(Request $request, ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        $formImg = $this->createForm(ImageType::class, $user, ["action" => $this->generateUrl('editImg')]);
        $formInfo = $this->createForm(SettingInfoType::class, $user, ["action" => $this->generateUrl('editInfo')]);



        return $this->render('connected/setting.html.twig', [
            'formImg' => $formImg->createView(),
            'formInfo' => $formInfo->createView()
        ]);
    } 
    
    
    #[Route('/profile/{id}', name: 'profile')]
    public function index(int $id = null, ManagerRegistry $doctrine): Response
    {

        $userUrl = $doctrine->getRepository(Users::class)->find($id);
        
        return $this->render('connected/index.html.twig', [
            "userUrl" => $userUrl,
            "idParameters" => $id
        ]);
    }

}
