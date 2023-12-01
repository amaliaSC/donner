<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\User as UserUser;

/**
 * @Route("/user", name="user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/profil/{id}", name="profil")
     */
    public function profil(int $id): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        return $this->render('user/profil.html.twig', ['user' => $user]);
    }


    /**
     * @Route("/edit", name="edit_profil")
     */
    public function edit(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if ($this->getUser()) {
            $user = $this->getUser();
            $editUserForm = $this->createForm(EditUserType::class, $user);

            $editUserForm->handleRequest($request);
            if ($editUserForm->isSubmitted() && $editUserForm->isValid()) {

                // $user->setPassword(
                //     $passwordEncoder->encodePassword(
                //         $user,
                //         $editUserForm->get('password')->getData()
                //     )
                // );

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->flush();

                return $this->render('user/profil.html.twig', ["user" => $user]);
            }
            return $this->render('user/edit.html.twig', [
                "editUserForm" => $editUserForm->createView()
            ]);
        }

        return $this->redirectToRoute('app_login');
    }
}
