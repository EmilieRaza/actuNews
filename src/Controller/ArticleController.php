<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
     * @Route("/admin/creer-un-article.html", name="create_article", methods={"GET|POST"})
     * @param Request $request
     * @param SluggerInterface $slugger
     * @return Response
     */
    public function createArticle(Request $request,SluggerInterface $slugger,EntityManagerInterface $entityManager):Response {#injection de dependance #}

        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article)->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $article = $form->getData();

            #on récupère de cette façon le fichier du formulaire grâce à getData()
            #cela nous retourne un objet de type UploadedFile
            
            $file = $form->get('photo')->getData();
            
            #l 'alias nous servira pour l'url de l'article, 
            #il est basé sur le titre, nous avons donc besoin de "nettoyer".
            #le slugger nous aide à faire ça , il supprime les espace et le caractères indésirables et remplace par des tirets (-)
            $article->setAlias($slugger->slug($article->getTitle()));
            



            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('default_home');
        }


        return $this->render('article/form_article.html.twig',[
            'form' => $form->createView()
        ]);

    }
}