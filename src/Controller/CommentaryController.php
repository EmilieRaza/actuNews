<?php

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentaryController extends AbstractController
{
    /**
     * @Route("/ajouter-un-commentaire?article_id={id}", name="add_commentary", methods={"GET|POST"})
     * @param Article $article
     * @param Request $request
     * @param EntityManagerInterface $EntityManager
     * @param Response $Response
     */
    public function addCommentary(Article $article, Request $request,EntityManagerInterface $entityManager): Response
    {
        return $this->render('commentary/index.html.twig', [
            'controller_name' => 'CommentaryController',
        ]);
    }
}
