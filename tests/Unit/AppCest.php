<?php


namespace Tests\Unit;

require_once 'src/AppService.php';
require_once 'src/ApiService.php';
require 'vendor/autoload.php';

use app\ApiService;
use app\AppService;
use Tests\Support\UnitTester;

class AppCest
{
    private AppService $appService;
    private ApiService $apiService;

    public function _before(UnitTester $I, AppService $appService)
    {
        $this->appService = $appService;
    }

    // tests
    public function isArrayResultTest(UnitTester $I)
    {
        $I->assertIsArray($this->appService->execute('src/input.txt'));
    }
}
