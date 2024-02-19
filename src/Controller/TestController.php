<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Sources\Nbp;
use App\Service\Sources\FloatRates;

class TestController extends AbstractController
{
    public function __construct(private Nbp $nbp, private  FloatRates $floatRates)
    {
        $this->nbp = $nbp;
        $this->floatRates = $floatRates;
    }
    #[Route('/test', name: 'app_test')]
    public function show(): Response
    {
        $result1 = $this->nbp->getData();
        $result2 = $this->floatRates->getData();

        dd($result1, $result2);

        return $this->render('exchange/show.html.twig', [

        ]);
    }

}
