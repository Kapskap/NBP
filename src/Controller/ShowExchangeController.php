<?php

namespace App\Controller;

use App\Entity\Exchange;
use App\Repository\ExchangeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ShowExchangeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        return $this->render('base.html.twig');
    }

    #[Route('/browse/{date}', name: 'app_browse')]
    public function browseExchange(Request $request, EntityManagerInterface $entityManager, string $date = null): Response
    {
        //pobieranie najnowszej daty
        if ($date == NULL) {
            $exchangeRepository = $entityManager->getRepository(Exchange::class);
            $date = $exchangeRepository->findOneBy([], ['importAt' => 'DESC']);
            $date = $date->getImportAt()->format("Y-m-d");
        }

        //formularz do obsługi daty
        $form = $this->createFormBuilder()
            ->add('query', TextType::class, [
                'label' => ' ',
                'data' => $date,
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Szukaj'
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $date = $form->get('query')->getData();
        }

        //pobieranie danych z wybraną datą
        $exchange = $entityManager->getRepository(Exchange::class)->findByDate($date);

        return $this->render('exchange/browse.html.twig', [
            'form' => $form->createView(),
            'exchange' => $exchange,
        ]);
    }

    #[Route('/show/{currency}', name: 'app_show')]
    public function show($currency, ExchangeRepository $exchangeRepository): Response
    {
        $exchange = $exchangeRepository->findBy(['currency' => $currency], ['importAt' => 'DESC']);
        if ($exchange == NULL) {
            throw $this->createNotFoundException('Nie znaleziono waluty o nazwie '.$currency);
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
        ]);
    }

}
