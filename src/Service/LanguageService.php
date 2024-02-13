<?php

namespace App\Service;

use App\Repository\LanguageRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Language;


class LanguageService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function checkCode(string $code): int
    {
        $em = $this->entityManager;
        $languageRepository = $em->getRepository(Language::class);

        $languages = $languageRepository->findBy(['code' => $code]);
//        foreach ($languages as $language) {
//            $id = $language->getId();
//        }

        $id = $languages[0]->getId();
        var_dump($id);
//        dd($id);
        return $id;
    }
}