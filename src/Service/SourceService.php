<?php

namespace App\Service;

use App\Repository\SourceRepository;
use App\Entity\Source;

Class SourceService
{
    public function __construct(private SourceRepository $sourceRepository)
    {
        $this->SourceRepository = $sourceRepository;
    }

    public function getSourceIdByName(string $sourceName): int
    {
        $source = $this->sourceRepository->findOneBy(['name' => $sourceName]);
        if ($source == NULL) {
           throw new \InvalidArgumentException("Nieprawidłowe źródło danych");
        }
        $sourceID = $source->getId();
        return $sourceID;
    }

    public function getSourceName(): array
    {
        $sourcesAll = $this->sourceRepository->findAll();
        foreach ($sourcesAll as $source)
        {
            $sourceName[] = $source->getName();
        }
        return $sourceName;
    }

    public function getSourceNameAndId(): array
    {
        $sourcesAll = $this->sourceRepository->findAll();
        foreach ($sourcesAll as $source)
        {
            $sourceName = $source->getName();
            $sources[$sourceName] = $source->getId();
        }
        return $sources;
    }
}
