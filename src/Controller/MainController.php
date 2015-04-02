<?php
/**
 * Created by PhpStorm.
 * User: AlexanderC <alexanderc@pycoding.biz>
 * Date: 4/2/15
 * Time: 11:44
 */

namespace SERAPI\Controller;

use ExchangeRates\Client;
use ExchangeRates\ExchangeRate;


/**
 * Class MainController
 * @package ERAPI\Controller
 */
class MainController extends AbstractController
{
    /**
     * @param string $provider
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getProviderRatesAction($provider = 'curs_md')
    {
        $client = $this->getClient($provider);
        $rates = $client->parse(new \DateTime('now', new \DateTimeZone('UTC')));

        $ratesPlain = [];

        /** @var ExchangeRate $rate */
        foreach($rates->getCollection() as $rate) {
            $rateItem = [
                $rate->getCountry() => [
                    $rate->getLocalCurrency() => [
                        $rate->getBank() => [
                            $rate->getHumanizedType() => [
                                $rate->getMainCurrency() => $rate->getExchangeRate()
                            ]
                        ]
                    ]
                ]
            ];

            $ratesPlain = array_merge_recursive($ratesPlain, $rateItem);
        }

        return $this->application->json($ratesPlain);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getProviderAction()
    {
        return $this->application->json([
            'curs_md', 'point_md'
        ]);
    }

    /**
     * @param string $provider
     * @return Client
     */
    protected function getClient($provider)
    {
        return Client::create($provider);
    }
}