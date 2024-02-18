<?php

interface SourceFactoryInterface
{
    public static function createObject(string $source): SourceInterface;
}