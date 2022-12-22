<?php


namespace Tests\Unit;

require_once 'src/AppService.php';
require_once 'src/ApiService.php';
require 'vendor/autoload.php';

use app\ApiService;
use Tests\Support\UnitTester;

class ApiCest
{
    private ApiService $apiService;

    public function _before(UnitTester $I, ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    // tests
    public function isInstanceOfTest(UnitTester $I)
    {
        $res = $this->apiService->getData('src/input.txt');
        $lines = explode("\n", $res);
        $I->assertNotEmpty($lines[0]);
        $I->assertInstanceOf(\StdClass::class, json_decode($lines[0]));
        $I->assertEquals('45717360', json_decode($lines[0])->bin);
    }

    public function checkBinResultTest(UnitTester $I)
    {
        $binResult = $this->apiService->getBinResult('45717360');
        $I->assertInstanceOf(\StdClass::class, $binResult);
        $I->assertEquals('DK', $binResult->country->alpha2);
    }
}
