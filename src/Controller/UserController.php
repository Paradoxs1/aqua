<?php
/**
 * Created by PhpStorm.
 * User: Paradoxs
 * Date: 01.03.2018
 * Time: 13:43
 */

namespace App\Controller;

use App\Form\UserEditForm;
use App\Form\UserRegistrationForm;
use App\Service\MessageManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Security\LoginFormAuthenticator;
use App\Entity\User;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class UserController extends Controller
{
    /**
     * @Route("/register", name="user_register")
     */
    public function registerAction(Request $request, LoginFormAuthenticator $authenticator, GuardAuthenticatorHandler $guardAuthenticatorHandler)
    {
        $form = $this->createForm(UserRegistrationForm::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Welcome ' . $user->getEmail());

            return $guardAuthenticatorHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $authenticator,
                    'main'
                );
        }

        return $this->render('user/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/users/{id}", name="user_show")
     */
    public function showAction(User $user)
    {
        return $this->render('user/show.html.twig', array(
            'user' => $user
        ));
    }

    /**
     * @Route("/user/{id}/edit", name="user_edit")
     */
    public function editAction(Request $request, User $user, MessageManager $messageManager,  $id)
    {
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(UserEditForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form = $form->getData();

            $user->setFirstName($form->getFirstName());
            $user->setLastName($form->getLastName());
            $user->setEmail($form->getEmail());
            $user->setUniversityName($form->getUniversityName());
            $user->setIsScientist($form->getIsScientist());

            $em->persist($user);
            $em->flush();

            $this->addFlash('success',
                $messageManager->getEncouragingMessage());

            return $this->redirectToRoute('user_show', ['id' => $id]);

        } elseif ($form->isSubmitted()) {
            $this->addFlash(
                'error',
                $messageManager->getDiscouragingMessage()
            );
        }

        return $this->render('user/edit.html.twig', [
            'userForm' => $form->createView()
        ]);
    }
}