<?php

namespace App\Controller;

use SebastianBergmann\CodeCoverage\Report\Html\Renderer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    #[Route('/service', name: 'app_service')]
    public function index(): Response
    {
        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }
    #[Route('/showService/{service_name}', name: 'show_service')]  
    public function showService($service_name){
        return $this->render('service/showService.html.twig',['twig_service' => $service_name,]); //it sends to the .twig file
    }
    public function goToIndex(): Response{
        return $this->redirectToRoute('bonjour');
    }
}
