<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default_home", methods={"GET"})
     */
    public function home(EntityManagerInterface $entityManger,CategoryRepository $categoryRepository)
    {
        #recupération de tous les articles en base de donnéee
        $articles = $entityManger->getRepository(Article::class)->findAll();
        $categories = $categoryRepository->findAll(); 
        return $this->render('default/home.html.twig',[
            'articles' => $articles,
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/render-categories",name="render_categories", method={"GET"})
     * @param EntityManagerInterface $entityManager
     * @param Response
     */
    public function renderCategories(EntityManagerInterface $entityManger):Response
    {
        $categories =$entityManger->getRepository(Category::class)->findAll();
       
        return $this->render('rendered/nav_categories.html.twig',[
            'categories'=>$categories,
        ]);
    }


}