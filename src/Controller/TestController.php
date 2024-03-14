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
use Doctrine\Common\Collections\ArrayCollection;

class TestController extends AbstractController
{
//    public function __construct(private Nbp $nbp, private  FloatRates $floatRates)
//    {
//        $this->nbp = $nbp;
//        $this->floatRates = $floatRates;
//    }

    public  function __construct(
        private SourceFactory $sourceFactory,
        private ExchangeManager $exchangeManager,
        private ExchangeDTO $exchangeDTO
    )
    {
        $this->sourceFactory = $sourceFactory;
        $this->exchangeManager = $exchangeManager;
        $this->exchangeDTO = $exchangeDTO;
    }

    #[Route('/test', priority: 10, name: 'app_test')]
    public function show(): Response
    {
        $result = $this->sourceFactory->createObject('NBP');

        if ($result != NULL) {
            $result = $result->getData();
//dd($result);
            $effectiveDate = $result['effectiveDate'];
            $rates = $result['rates'];
            $sourceId = $result['sourceId'];

            $this->exchangeDTO->setDTO($effectiveDate, $sourceId, $rates);
//            $dto = $this->exchangeDTO->setDTO($effectiveDate, $sourceId, $rates);

            dd($this->exchangeDTO);

//            $rates2 = new ArrayCollection($rates);
//            dd($result, $effectiveDate, $rates, $sourceId, $rates2);

//            $check = $this->exchangeManager->checkAndAddData($effectiveDate, $sourceId, $rates);
//        $result1 = $this->nbp->getData();
//        $result2 = $this->floatRates->getData();
        }


        dd($result);

        return $this->render('exchange/show.html.twig', [

        ]);
    }

}
