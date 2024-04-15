<?php

namespace App\Service;

use App\Repository\SourceRepository;
use App\Entity\Source;

Class SourceService
{
    public function __construct(private SourceRepository $sourceRepository)
    {
        $this->SourceRepository = $this->sourceRepository;
    }

    public function getSourceId(string $sourceName): int
    {
        $source = $this->sourceRepository->getIdByName($sourceName);
        if ($source == NULL) {
           throw new \InvalidArgumentException("Nieprawidłowe źródło danych");
        }
        $sourceID = $source[0]->getId();
        return $sourceID;
    }

    public function getSource(): array
    {
        return [
            'Narodowy Bank Polski',
            'Float Rates',
            'Coin Cap',
        ];
    }
}
