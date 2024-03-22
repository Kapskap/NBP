<?php

namespace App\Controller;

use App\Entity\Exchange;
use App\Entity\Currency;
use App\Repository\ExchangeRepository;
use App\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

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
        $form = $this->createFormBuilder()
            ->add('date', TextType::class, [
                'label' => ' ',
                'data' => $date,
                'required' => false,
            ])
            ->add('source', ChoiceType::class, [
                'choices'  => [
                    'Wszystko' => null,
                    'Narodowy Bank Polski' => '1',
                    'Float Rates' => '2',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Szukaj'
            ])
            ->setMethod('GET')
            ->getForm();

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
    public function show(int $currencyId, ExchangeRepository $exchangeRepository, int $divider = 100000000): Response
    {
        $exchange = $exchangeRepository->findBy(['currency' => $currencyId], ['importAt' => 'DESC']);
        if ($exchange == NULL) {
            throw $this->createNotFoundException('Nie znaleziono waluty o identyfikatorze '.$currencyId);
        }

        foreach ($exchange as $key => $value) {
            $mid[] = $value->getMid();
        }

        //Obliczanie różnic w walucie jako liczba ($sub) oraz jako % ($sc)
        $sub[$key] = NULL;
        $sc[$key] = NULL;
        for($i = $key; $i>0; $i--){
            $sub[$i-1] = $mid[$i-1] - $mid[$i];
            $sc[$i-1] = 100*$sub[$i-1]/$mid[$i-1];
        }

        return $this->render('exchange/show.html.twig', [
            'exchange'=>$exchange,
            'sub'=>$sub,
            'sc'=>$sc,
            'divider'=>$divider,
        ]);
    }

}
