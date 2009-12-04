<?php
require_once '../lib/config.php';

$start = microtime(true);

$c = new BaseBot();
$c = new BaseContext();

$end = microtime(true);

echo $start - $end;