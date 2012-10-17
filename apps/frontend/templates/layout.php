<?php
$env = sfConfig::get('sf_environment');
if ($env == 'kiosk') {
    include 'layout_kiosk.php';
}
else {
    include 'layout_web.php';
}