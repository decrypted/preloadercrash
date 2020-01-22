<?php

error_reporting(-1);

echo "crash running ".PHP_EOL;

$check_classes = ['CW_Preload','CWCache'];

foreach ($check_classes as $check_class) {
    echo "Class $check_class does ".(class_exists($check_class, false) ? 'exist': 'NOT e').PHP_EOL;
}

