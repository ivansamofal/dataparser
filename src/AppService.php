<?php

namespace app;

use app\Exceptions\NoBinResultException;

class AppService
{
    private const RATE_EU = 0.01;
    private const RATE_NOT_EU = 0.02;
    private const EUR_CURRENCY = 'EUR';

    private ApiService $apiService;

    /**
     * @param ApiService $apiService
     */
    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * @param string $fileName
     * @return string
     * @throws NoBinResultException
     */
    public function execute(string $fileName)
    {
        $data = $this->apiService->getData($fileName);

        return $this->parseData($data);
    }

    /**
     * @param string $data
     * @return string
     * @throws NoBinResultException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function parseData(string $data): array
    {
        $inputArray = explode("\n", $data);
        $result = [];

        foreach ($inputArray as $row) {
            if (empty($row)) break;
            $rowObject = json_decode($row);
            $binResultDecoded = $this->apiService->getBinResult($rowObject->bin);
            $isEu = $this->isEu($binResultDecoded->country->alpha2 ?? '');
            $rate = $this->apiService->getRateByCurrency($rowObject->currency);

            $amountFixed = 0;

            if ($this->isEur($rowObject->currency) || $rate == 0) {
                $amountFixed = $rowObject->amount;
            }

            if ((!$this->isEur($rowObject->currency) || $rate > 0) && $rate != 0) {
                $amountFixed = $rowObject->amount / $rate;
            }

            $result[] = $amountFixed * ($isEu ? self::RATE_EU : self::RATE_NOT_EU);
        }

        return $result;
    }

    /**
     * @param string $code
     * @return bool
     */
    private function isEu(string $code): bool
    {
        $codes = [
            'AT' => null,
            'BE' => null,
            'BG' => null,
            'CY' => null,
            'CZ' => null,
            'DE' => null,
            'DK' => null,
            'EE' => null,
            'ES' => null,
            'FI' => null,
            'FR' => null,
            'GR' => null,
            'HR' => null,
            'HU' => null,
            'IE' => null,
            'IT' => null,
            'LU' => null,
            'LV' => null,
            'MT' => null,
            'NL' => null,
            'PO' => null,
            'PT' => null,
            'RO' => null,
            'SE' => null,
            'SI' => null,
            'SK' => null,
        ];

        return key_exists($code, $codes);
    }

    /**
     * @param string $currency
     * @return bool
     */
    private function isEur(string $currency): bool
    {
        return $currency === self::EUR_CURRENCY;
    }
}