<?php

namespace App\Controller;

use App\Entity\Exchange;
use App\Repository\ExchangeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ShowExchangeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('base.html.twig');
    }

    #[Route('/browse', name: 'app_browse')]
    public function browseExchange(EntityManagerInterface $entityManager): Response
    {
        //pobieranie wpisu z najnowszą datę
        $exchangeRepository = $entityManager->getRepository(Exchange::class);
        $date = $exchangeRepository->findOneBy([], ['importAt' => 'DESC']);
        $date = $date->getImportAt()->format("Y-m-d");

        //pobieranie danych z najnowszą datą
        $exchange = $entityManager->getRepository(Exchange::class)->findLatest($date);

        return $this->render('exchange/browse.html.twig', [
            'exchange' => $exchange,
        ]);
    }

    #[Route('/show/{id}', name: 'app_show')]
    public function show($id, ExchangeRepository $exchangeRepository): Response
    {
 //       dd($currency);
        $exchange = $exchangeRepository->find($id);
        if (!$exchange) {
            throw $this->createNotFoundException('Nie znaleziono id '.$id);
        }
//dd($currencies);
        return $this->render('exchange/show.html.twig', ['exchange'=>$exchange]);
    }

}
