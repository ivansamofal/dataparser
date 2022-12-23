<?php

require_once 'ApiService.php';
require_once 'AppService.php';
require '../vendor/autoload.php';

use app\ApiService;
use app\AppService;
use GuzzleHttp\Client;

$appService = new AppService(new ApiService(new Client()));//todo DI
echo implode("\n", $appService->execute($argv[1]));
