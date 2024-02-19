<?php

namespace App\Service\Interfaces;

interface SourceFactoryInterface
{
    public static function createObject(string $source): SourceInterface;
}