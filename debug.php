<?php

require 'vendor/autoload.php';

$et = new \Cn\Xu42\ExpressTracking\Service\ExpressTrackingService();

$comCodes = $et->getComCodes('3101260212281');
var_dump($comCodes);
$result = $et->query('3101260212281', $comCodes);
var_dump($result);

