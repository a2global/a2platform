<?php

namespace A2Global\A2Platform\Bundle\AuthBundle\Controller;

use A2Global\A2Platform\Bundle\AuthBundle\Form\RegistrationFormType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/authentication/', name: 'authentication_')]
class AuthController extends AbstractController
{
    #[Route('', name: 'default')]
    public function defaultAction()
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('authentication_login');
        }

        if($this->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('admin_default');
        }

        return $this->render('@Core/frontend/login.html.twig');
    }

    #[Route('registration', name: 'registration')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            if ($user->getId() === 1) {
                $user->setRoles(['ROLE_ADMIN']);
                $entityManager->flush();
            }

            return $this->redirectToRoute('authentication_login');
        }

        return $this->render('@Auth/registration.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
//        if ($this->getUser()) {
//            return $this->redirectToRoute('target_path');
//        }
//
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('@Core/frontend/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route('logout', name: 'logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
