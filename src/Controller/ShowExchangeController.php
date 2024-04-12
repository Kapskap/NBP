<?php

namespace App\Controller;

use App\Entity\Exchange;
use App\Repository\ExchangeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\DateAndSourceFormType;
use App\Service\SubtractService;


class ShowExchangeController extends AbstractController
{
    #[Route('/{date}', name: 'app_browse')]
    public function browseExchange(Request $request, EntityManagerInterface $entityManager, string $date = null, int $sourceId = null, int $divider = 100000000): Response
    {
        //pobieranie najnowszej daty
        if ($date === NULL) {
            $exchangeRepository = $entityManager->getRepository(Exchange::class);
            $date = $exchangeRepository->findOneBy([], ['importAt' => 'DESC']);
            $date = $date->getImportAt()->format("Y-m-d");
        }

        //formularz do obsługi daty
        $form = $this->createForm(DateAndSourceFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $date = $form->get('date')->getData();
            $sourceId = $form->get('source')->getData();
//            $this->redirectToRoute('app_browse',[$date]);
        }

        //pobieranie danych z wybraną datą i źródłem danych
        if ($sourceId !== NULL) {
            $exchange = $entityManager->getRepository(Exchange::class)->findByDateAndSourceId($date, $sourceId);
        }
        else{
            $exchange = $entityManager->getRepository(Exchange::class)->findByDate($date);
        }

        return $this->render('exchange/browse.html.twig', [
            'form' => $form->createView(),
            'exchange' => $exchange,
            'divider'=>$divider
        ]);
    }

    #[Route('/show/{currencyId}', name: 'app_show')]
    public function show(
        int $currencyId,
        ExchangeRepository $exchangeRepository,
        SubtractService $subtractService,
        int $divider = 100000000,
    ): Response
    {
        $exchange = $exchangeRepository->findBy(['currency' => $currencyId], ['importAt' => 'DESC']);
        if ($exchange == NULL) {
            throw $this->createNotFoundException('Nie znaleziono waluty o identyfikatorze '.$currencyId);
        }

        foreach ($exchange as $value) {
            $mid[] = $value->getMid();
        }

        $sub = $subtractService->subtract($mid);
        $subtract = $sub[0];
        $subtractInPercent = $sub[1];

        return $this->render('exchange/show.html.twig', [
            'exchange'=>$exchange,
            'subtract'=>$subtract,
            'subtractInPercent'=>$subtractInPercent,
            'divider'=>$divider,
        ]);
    }

}
