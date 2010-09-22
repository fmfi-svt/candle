<?php

$codes = array();

foreach ($layout->getLessons() as $lesson) {
    $code = $lesson['Subject']['short_code'];
    if (empty($code)) continue;
    $codes[$code] = true;
}

ksort($codes);

foreach ($codes as $code=>$dummy) {
    echo $code;
    echo "\n";
}
