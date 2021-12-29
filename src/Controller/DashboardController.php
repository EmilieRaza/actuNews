<?php

namespace App\Controller;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

 /**
 * @Route("/admin")
 */

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard/", name="admin_dashboard", methods={"GET"})
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function showDashbord(EntityManagerInterface $entityManager):Response
    {
        $articles = $entityManager->getRepository(Article::class)->findAll();
        $categories = $entityManager->getRepository(Category::class)->findAll();
        $users = $entityManager->getRepository(User::class)->findAll();

        return $this->render('dashboard/show_dashboard.html.twig', [
            'articles' => $articles,
            'categories' => $categories,
            'users' => $users
        ]);
    }
}