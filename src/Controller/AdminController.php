<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route(path="/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route(
     *     path="",
     *     name="admin_dashboard"
     * )
     */
    public function dashboardAction()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findBy(['isAdmin' => false]);

        return $this->render('Admin/dashboard.html.twig', ['users' => $users]);
    }

    /**
     * @Route(
     *     path="/delete-user/{id}",
     *     name="user_delete"
     * )
     */
    public function deleteUserAction(User $user)
    {
        $this->getDoctrine()->getManager()->remove($user);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('admin_dashboard');
    }
}
