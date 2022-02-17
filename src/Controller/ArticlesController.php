<?php


namespace App\Controller;

use App\Entity\Articles;
use App\Form\ArticlesType;
use App\Repository\ArticlesRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticlesController extends AbstractController
{

    #[Route('Articles/create', name:"createArticle")]
    public function createArticle(Request $request, ManagerRegistry $doctrine):Response
    {
        $article = new Articles();
        $form = $this->createForm(ArticlesType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            

            $article->setCreatedAt(new \DateTime());
            $article->setUsers($this->getUser());
            
            $em = $doctrine->getManager();

            $em->persist($article);
            $em->flush();

            $this->addFlash('succes', "l'article à bien été ajouté !");

            

        }


        return $this->render("connected/articles/create.html.twig",[
            "formArticle" => $form->createView()
        ]);

    }

    #[Route('/articles', name:'articles')]
    public function showArticles(ArticlesRepository $repo): Response
    {
        

        return $this->render('connected/articles/showArticles.html.twig',[
            "articles" => $repo->findThreeLastArticle()
        ]);

    }




}