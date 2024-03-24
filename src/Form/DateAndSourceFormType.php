<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Repository\ExchangeRepository;


class DateAndSourceFormType extends AbstractType
{
    public function __construct(
        private ExchangeRepository $exchangeRepository,
    )
    {
        $this->ExchangeRepository = $exchangeRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //pobieranie najnowszej daty
        if (!isset($date)) {
            $date = $this->exchangeRepository->findOneBy([], ['importAt' => 'DESC']);
            $date = $date->getImportAt()->format("Y-m-d");
        }

        $builder
            ->add('date', TextType::class, [
                'label' => ' ',
                'data' => $date,
                'required' => false,
            ])
            ->add('source', ChoiceType::class, [
                'choices'  => [
                    'Wszystko' => null,
                    'Narodowy Bank Polski' => '1',
                    'Float Rates' => '2',
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Szukaj'
            ]);
    }

}