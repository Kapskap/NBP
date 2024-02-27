<?php

namespace App\Service\Interfaces;

interface SourceFactoryInterface
{
    public function createObject(string $source): SourceInterface;
}