<?php

namespace App\Service;

use App\Entity\Currency;
use Doctrine\ORM\EntityManagerInterface;

class CurrencyService
{
    public function __construct(private EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createOrUpdateCurrencyArray(array $rates) 
    {
        $i = 0;
        foreach ($rates as $rate) 
        {
            $currency = $this->em->getRepository(Currency::class)->findOneBy(['currencyCode' => $rate['code']]);
            if (!$currency)
            {
                $currency = (new Currency())
                    ->setCurrencyCode($rate['code'])
                    ->setName($rate['currency'])
                    ->setExchangeRate($rate['mid'])
                ;

                $this->em->persist($currency);
            }
            else
                $currency->setExchangeRate($rate['mid']);

            $i++;

            if ($i === 100 || $i === (count($rates) - 1))
                $this->em->flush();
        }
    }
}