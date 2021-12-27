<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegisterController extends AbstractController
{
    /**
     * @Route("/inscription", name="register", methods={"GET"})
     * @param Request $request
     * @return Response
     */
    public function register(Request $resquest):Response
    {   
        # Nouvelle instance de la classe User
        $user = new User();

        #matérialisation du formulaire déclaré dans RegisterType.php
        $form = $this->createForm(RegisterType::class, $user);
        
        # La méthode handlRequest() sert à récupérer les données du formulaire
        $form->handleRequest($resquest);

        return $this->render('register/register.html.twig',[
            'form' => $form->createView()
        ]);

    }

}