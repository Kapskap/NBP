<?php

namespace App\Controller;

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
        private RateDTO $rateDTO
    )
    {
        $this->sourceFactory = $sourceFactory;
        $this->exchangeManager = $exchangeManager;
        $this->exchangeDTO = $exchangeDTO;
    }

    #[Route('/test', priority: 10, name: 'app_test')]
    public function show(): Response
    {
        $result = $this->sourceFactory->createObject('Narodowy Bank Polski');
//        $result = $this->sourceFactory->createObject('Float Rates');

        if ($result != NULL) {
            $result = $result->getData();
//dd($result);
            $effectiveDate = $result['effectiveDate'];
            $rates = $result['rates'];
            $sourceId = $result['sourceId'];

            $this->exchangeDTO->setDTO($effectiveDate, $sourceId, $rates);

dd($this->exchangeDTO->getRates()[2]->getMid()->getAmount());
dd($this->exchangeDTO->getRates()[2]->getMid()->getCurrency()->getCode());

//dd($this->exchangeDTO->getRates()[2]->getCurrency() );


            //money test
            $fiver = Money::PLN(500);
            $coupon = new Money(50, new currency('PLN'));
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
