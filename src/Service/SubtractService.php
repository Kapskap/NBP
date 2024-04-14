<?php

namespace App\Service;

class SubtractService
{
    public function subtract(array $mid):array
    {
        //Obliczanie różnic w walucie jako liczba ($subtract) oraz jako w % ($subtractInPercent)
        $howMany = count($mid)-1;
        $subtract[$howMany] = NULL;
        $subtractInPercent[$howMany] = NULL;

        for($i = 0; $i < $howMany; $i++){
            $subtract[$i] = $mid[$i] - $mid[$i+1];
            $subtractInPercent[$i] = 100 * $subtract[$i] / $mid[$i];
        }

        return array($subtract, $subtractInPercent);
    }
}