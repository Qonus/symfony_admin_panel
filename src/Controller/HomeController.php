<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("IS_AUTHENTICATED_REMEMBERED")]
class HomeController extends AbstractController {
    #[Route("/", name: "home")]
    public function home() {
        return $this->render("index.html.twig");
    }
}