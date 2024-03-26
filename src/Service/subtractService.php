<?php

namespace App\Service;

class subtractService
{
    public function subtract(array $mid, int $howMany):array
    {
        $subtract[$howMany] = NULL;
        $subtractInPercent[$howMany] = NULL;

        for($i = $howMany; $i>0; $i--){
            $subtract[$i-1] = $mid[$i-1] - $mid[$i];
            $subtractInPercent[$i-1] = 100*$subtract[$i-1]/$mid[$i-1];
        }

        return array($subtract, $subtractInPercent);
    }
}