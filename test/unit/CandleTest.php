<?php require_once dirname(__FILE__).'/../bootstrap/unit.php';

$t = new lime_test(11);

$t->is(Candle::formatTime(0),'0:00');
$t->is(Candle::formatTime(5),'0:05');
$t->is(Candle::formatTime(60),'1:00');
$t->is(Candle::formatTime(65),'1:05');
$t->is(Candle::formatTime(990),'16:30');
$t->is(Candle::formatTime(1090),'18:10');
$t->is(Candle::formatShortDay(0),'Po');
$t->is(Candle::formatShortDay(1),'Ut');
$t->is(Candle::formatShortDay(2),'St');
$t->is(Candle::formatShortDay(3),'Št');
$t->is(Candle::formatShortDay(4),'Pi');
