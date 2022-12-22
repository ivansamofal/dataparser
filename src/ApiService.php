<?php

namespace app;

require_once 'Exceptions/NoBinResultException.php';

use app\Exceptions\NoBinResultException;
use GuzzleHttp\Client;

class ApiService
{
    private const BIN_LIST_URL = 'https://lookup.binlist.net/';
    private const RATES_URL = 'https://api.exchangeratesapi.io/latest';

    private Client $client;
    private array $cachedCurrencyRates = [];

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $fileName
     * @return string
     */
    public function getData(string $fileName): string
    {
        return (string) file_get_contents($fileName);
    }

    /**
     * @param string $bin
     * @return \StdClass
     * @throws NoBinResultException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getBinResult(string $bin): \StdClass
    {
        try {
            $binResults = $this->client->request('GET', self::BIN_LIST_URL . $bin);
            $binResults = $binResults->getBody()->getContents();
        } catch (\Throwable $e) {
            $binResults = null;
        }

        if (!$binResults) {
            throw new NoBinResultException();
        }

        return json_decode($binResults);
    }

    /**
     * @param string $currency
     * @return float
     */
    public function getRateByCurrency(string $currency): float
    {
        //todo add access_key to correct work
        try {
            if (!empty($this->cachedCurrencyRates[$currency])) {
                $rateObject = $this->cachedCurrencyRates[$currency];
            } else {
                $rateObject = $this->client->request('GET', self::RATES_URL);
                $rateObject = json_decode($rateObject->getBody()->getContents());
                $this->cachedCurrencyRates[$currency] = $rateObject;
            }

            return $rateObject->rates->{$currency} ?? 0.0;
        } catch (\Throwable $exception) {
            return 0.0;
        }
    }
}