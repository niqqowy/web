<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Math;

$math = new Math();

echo "Math operations:\n";
echo "2 + 3 = " . $math->add(2, 3) . "\n";
echo "10 - 4 = " . $math->subtract(10, 4) . "\n";
echo "6 * 7 = " . $math->multiply(6, 7) . "\n";
echo "15 / 3 = " . $math->divide(15, 3) . "\n";