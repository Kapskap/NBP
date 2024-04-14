<?php

namespace App\Controller;

use App\Service\SourceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Sources\Nbp;
use App\Service\Sources\FloatRates;
use App\Service\SourceFactory;
use App\Service\Manager\ExchangeManager;
use App\Service\Dto\ExchangeDTO;
use App\Service\Dto\RateDTO;
use Money\Currency;
use Money\Money;


class TestController extends AbstractController
{


    public  function __construct(
        private SourceFactory $sourceFactory,
        private ExchangeManager $exchangeManager,
        private ExchangeDTO $exchangeDTO,
        private RateDTO $rateDTO,
        private SourceService $sourceService
    )
    {

    }

    #[Route('/test', priority: 10, name: 'app_test')]
    public function show(): Response
    {
        $sourceName = 'Narodowy Bank Polski';
        $sourceId = $this->sourceService->getSourceId($sourceName);

        $resultObject = $this->sourceFactory->createObject($sourceName);

        if ($resultObject != NULL) {
            $result = $resultObject->getData();

            $effectiveDate = $result['effectiveDate'];

            $rates = $result['rates'];
            $midCode = $result['midCode'];

            $this->exchangeDTO->setDTO($effectiveDate, $sourceId, $rates, $midCode); //Zamiana tablicy na obiekt DTO
//dd($result);
            $check = $this->exchangeManager->AddData($this->exchangeDTO);

            if ($check == true) {
                dd('Dane zostały pobrane poprawnie');
            } else {
                dd('Dane z podaną datą już istnieją');
            }
//dd($this->exchangeDTO->getRates()[2]->getMid()->getAmount());
//dd($this->exchangeDTO->getRates()[2]->getMid()->getCurrency()->getCode());
//dd($this->exchangeDTO->getRates()[0], $this->exchangeDTO->getRates()[0]->getMid()->getCurrency()->getCode()) ;

//dd($this->exchangeDTO->getRates()[0]->getCurrency() );


            //money test
            $money = "USD";
            $fiver = Money::$money(500);
            $coupon = new Money(50, new currency($money));
            $fiver  = $fiver->subtract($coupon);
 dd($fiver, $coupon);



//            $check = $this->exchangeManager->AddData($effectiveDate, $sourceId, $rates);
//        $result1 = $this->nbp->getData();
//        $result2 = $this->floatRates->getData();
        }


        dd($result);

        return $this->render('exchange/show.html.twig', [

        ]);
    }

}
