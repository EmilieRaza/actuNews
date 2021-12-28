<?php
namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class RegisterController extends AbstractController
{
    /**
     * @Route("/inscription", name="register", methods={"GET|POST"})
     * @param Request $request
     * @return Response
     */
    public function register(Request $resquest, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager):Response
    {   
        # Nouvelle instance de la classe User
        $user = new User();

        #matérialisation du formulaire déclaré dans RegisterType.php
        $form = $this->createForm(RegisterType::class, $user);
        
        # La méthode handlRequest() sert à récupérer les données du formulaire
        $form->handleRequest($resquest);
        
        #si le formulaire est soumis et valide alors on traie le formulaire
        if($form->isSubmitted() && $form->isValid()){   
            #la methode getData() permet de récupérer les données du formulaire. comme on a passé $user à la à la créatiob du form, on peut reinjecter les données dans notre objet User
            $user = $form->getData();

            #on paramettre la propriété  de notre objet User
            $user->setCreatedAt(new DateTime());
            
            #on Set le password grâce au passWordHasher pour envoyer en BDD un mdp hashé
            $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));
            
            #On set le role du user à ROLE_USER. dans Symfony les roles ont une place à part pour permettre de différencier les utilisateur par leur role dans l'application
            $user->setRoles(['ROLE_USER']);

            #Pour insérer en BDD, on va utiliser l'ORM de symfony, Doctrine et son outil entityManager. Ce dernier nous permettra de persister les données en BDD. 
            # $entityManager est un container.
            $entityManager->persist($user);

            #Grace à la methode flush() on vide notre entityMangager des données précédémentcontinues.
            $entityManager->flush();

            #on redirige l'utilisateur sur la page de connexion
            return $this->redirectToRoute('app_login');

        }
        return $this->render('register/register.html.twig',[
            'form' => $form->createView()
        ]);

    }

}