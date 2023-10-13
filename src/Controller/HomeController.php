<?php
namespace App\Controller; //montrer que le travail est sous mon projet

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController{
    #[Route('/bonjour')]
    public function index(){
        return new Response('Bonjour à tous !');
    }

}
