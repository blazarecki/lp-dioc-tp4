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
        // FIXME: Récupérer les utilisateurs non admin
        $users = [];

        return $this->render('Admin/dashboard.html.twig', ['users' => $users]);
    }

    public function deleteUserAction(User $user)
    {
        // FIXME: Supprime l'utilisateur est redirige sur /admin, la route doit être /delete-user/1
    }
}
