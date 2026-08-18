<?php

namespace App\Controller;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("IS_AUTHENTICATED_REMEMBERED")]
class AdminController extends AbstractController {
    #[Route("/admin", name: "app_admin")]
    public function admin(UserRepository $userRepository) {
        // Newest logins First = DESC
        $users = $userRepository->findBy([], ['last_login' => 'DESC']);
        return $this->render("admin/admin.html.twig", [
            "users" => $users,
        ]);
    }

    #[Route("/admin/batch/{action}", name: "app_users_batch", methods: ["POST"], requirements: ["action" => "block|unblock|delete|clean"])]
    public function batchAction(string $action, UserRepository $userRepository, Request $request) 
    {
        $ids = $request->request->all('ids');
        
        if (empty($ids)) {
            $this->addFlash('error', 'No users selected.');
            return $this->redirectToRoute('app_admin');
        }
        match ($action) {
            'block'   => $c = $userRepository->updateBlockByIds($ids, true),
            'unblock' => $c = $userRepository->updateBlockByIds($ids, false),
            'delete'  => $c = $userRepository->deleteByIds($ids),
            'clean'  => $c = $userRepository->deleteByIds($ids, true),
        };
        $pastTense = [
            'block'   => 'blocked',
            'unblock' => 'unblocked',
            'delete'  => 'deleted',
            'clean'  => 'deleted',
        ];
        $this->addFlash('success', sprintf('%d users %s.', $c, $pastTense[$action]));
        return $this->redirectToRoute('app_admin');
    }
}