<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\ImageType;
use App\Form\SettingInfoType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SettingController extends AbstractController
{



    #[Route('/edit/password', name:"editPassword", methods:"POST" )]
    public function editPassword(Request $request, UserPasswordHasherInterface $hashpass, ManagerRegistry $doctrine):Response
    {


        if($request->isMethod('POST')){

            $thisUser = $this->getUser();
            $em = $doctrine->getManager();
            $userRepository = $doctrine->getRepository(Users::class)->find($this->getUser()->getId());

            if(!empty($request->request->get('lastPassword')) AND !empty($request->request->get('newPassword')) AND !empty($request->request->get('confirmPassword')) ){

                if($request->request->get('newPassword') === $request->request->get('confirmPassword')){

                    if($hashpass->isPasswordValid($userRepository, $request->request->get('lastPassword'))){

                        $hash = $hashpass->hashPassword($userRepository, $request->request->get('newPassword'));

                        $thisUser->setPassword($hash);

                        $em->flush();

                        $this->addFlash('success', 'le mot de passe à bien été mis à jour');


                    }else{
                        $this->addFlash('error', 'l\'ancien mot de passe est incorrect');
                    }

                }else{
                    $this->addFlash('error', 'les mots de passe ne correspondent pas !');
                }

            }else{
                $this->addFlash('error', 'veuillez remplire les champs !');
            }


        }
        return $this->redirectToRoute('profile', ["id" => $thisUser->getId()]);
    }



    #[Route('/edit/img', name: 'editImg')]
    public function editImg(SluggerInterface $slugger, Request $request, ManagerRegistry $doctrine):Response
    {
        $user = $this->getUser();

        $form = $this->createForm(ImageType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            
            

            if($form->get('url_img')->getData()){

                $img = $form->get('url_img')->getData();
                $originalFileName = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFileName = $slugger->slug($originalFileName);
            

                $newFilename = $this->getUser()->getPseudo() .".".  $img->guessExtension();


                try{
                    $img->move(
                        $this->getParameter('img_directory'),
                        $newFilename
                    );
                }catch(FileException $e){
                    return $e->getMessage();
                }

                $user->setUrlImg($newFilename);
                
               $em = $doctrine->getManager();
               $em->flush();
               
               $this->addFlash('success' , "les infos ont bien été mis à jour !");

               
            }


        }


        return $this->redirectToRoute('profile', ["id" => $user->getId()]);
       
    }



    #[Route('/edit/info', name: 'editInfo')]
    public function editInfo(Request $request, ManagerRegistry $doctrine):Response
    {
        $user = $this->getUser();
        $formInfo = $this->createForm(SettingInfoType::class, $user, ["action" => $this->generateUrl('editInfo')]);
    
        $formInfo->handleRequest($request);
        
        if($formInfo->isSubmitted() && $formInfo->isValid()){          

           $em = $doctrine->getManager();
           $em->persist($user);
           $em->flush();

            $this->addFlash('success' , "les infos ont bien été mis à jour !");

            

        }

        
        

        return $this->redirectToRoute('profile', ["id" => $user->getid()]);


    }

}