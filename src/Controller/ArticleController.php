<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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
            #on set l'auteur de l'article grace au user provider de symfony
            $article->setAuthor($this->getUser());

            $article = $form->getData();

            #on récupère de cette façon le fichier du formulaire grâce à getData()
            #cela nous retourne un objet de type UploadedFile
            $file = $form->get('photo')->getData();

          
            
            #l 'alias nous servira pour l'url de l'article, 
            #il est basé sur le titre, nous avons donc besoin de "nettoyer".
            #le slugger nous aide à faire ça , il supprime les espace et le caractères indésirables et remplace par des tirets (-)
            $article->setAlias($slugger->slug($article->getTitle()));

            if($file){
                // $allowedMimeTypes = ['image/jpeg', 'image/png'];

                // if(in_array($file->getMimeType(), $allowedMimeTypes)){
                
                #Nous allons construire le nouveau nom du fichier 

                $originalFilename= pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                //on utilise concaténation pour ajouter un point '.'
                // Assainir le nom grace au slugger 
                $safeFilename = $article->getAlias();
                // $safeFilename = $slugger->slug($originalFilename); // si on veut mettre le nom original du fichier
                $extension = '.'.$file->guessExtension();

                # on va reconstruire le nom du fichier
                $newFilename = $safeFilename.'_'.uniqid().$extension;
                // dd($newFilename);

                try{
                    #on entoure la methode move d'un try /catch car elle lance (throw) une exception
                    $file->move($this->getParameter('uploads_dir'),$newFilename);
                
                    # on set la nouvelle valeur ($newFilename) de la propriété 'photo' de l'objet Article 
                    $article->setPhoto($newFilename);

                }catch(FileException $exception){
                    //code a executer si une erreur est attrapée
                }
                
            }
            



            $entityManager->persist($article);
            $entityManager->flush();
            $this->addFlash('success', 'Vous avez crée un nouvel article!');

            return $this->redirectToRoute('default_home');
        }


        return $this->render('article/form_article.html.twig',[
            'form' => $form->createView()
        ]);

    }


    /**
     * @Route("/admin/editer-un-article_{id}", name="edit_article", methods={"GET|POST"})
     * @param Article $article
     * @param Request $request
     * @return Response
     */
    public function editArticle(Article $article, Request $request,SluggerInterface $slugger,EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleType::class, $article,[
            'photo' => $article->getPhoto()
        ])->handleRequest($request);

        $originalPhoto = $article->getPhoto();

        if ($form->isSubmitted() && $form->isValid()) {

            $article = $form->getData();
            $article->setAlias($slugger->slug($article->getTitle()));

            $article->setUpdatedAt(new DateTime());

            /** @var UploadedFile $file */
            $file  = $form->get('photo')->getData();

            if($file){
                $extension = '.'.$file->guessExtension();
                $safeFilename = $article->getAlias();

                $newFilename = $safeFilename.'_'.uniqid().$extension;

                try{

                    $file->move($this->getParameter('uploads_dir'),$newFilename);

                    $article->setPhoto($newFilename);

                }catch(FileException $exception){
                    dd("test");
                }
            }
            else {
            
                $article->setPhoto($originalPhoto);
            }
                $entityManager->persist($article);
                $entityManager->flush();

                $this->addFlash('success', "L'article".$article->getTitle()." à bien été modifié!");

                return $this->redirectToRoute('admin_dashboard');

                
        } 
        
        return $this->render('article/form_article.html.twig', [
            'form' => $form->createView(),
            'article' =>$article,
        ]);
    }

    /**
     * @Route("/voir-un-article_{id}", name="show_article", methods={"GET"})
     * @param Article $article
     * @param Request $request
     * @return Response
     */
    public function showArticle(Article $article):Response
    {
        return $this->render('article/show_article.html.twig', [
            'article' => $article
        ]);
    }
}