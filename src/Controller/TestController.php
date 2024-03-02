<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Sources\Nbp;
use App\Service\Sources\FloatRates;
use App\Service\SourceFactory;
use App\Service\Manager\ExchangeManager;

class TestController extends AbstractController
{
//    public function __construct(private Nbp $nbp, private  FloatRates $floatRates)
//    {
//        $this->nbp = $nbp;
//        $this->floatRates = $floatRates;
//    }

    public  function __construct(private SourceFactory $sourceFactory, private ExchangeManager $exchangeManager)
    {
        $this->sourceFactory = $sourceFactory;
        $this->exchangeManager = $exchangeManager;
    }

    #[Route('/test', priority: 10, name: 'app_test')]
    public function show(): Response
    {
        $result = $this->sourceFactory->createObject('NBP');
        $sourceId = 1;
        if ($result != NULL) {
            $result = $result->getData();

            $effectiveDate = $result['effectiveDate'];
            $rates = $result['rates'];

            $check = $this->exchangeManager->checkAndAddData($effectiveDate, $sourceId, $rates);
//        $result1 = $this->nbp->getData();
//        $result2 = $this->floatRates->getData();
        }


        dd($result);

        return $this->render('exchange/show.html.twig', [

        ]);
    }

}
