<?php require_once dirname(__FILE__).'/../bootstrap/unit.php';

$t = new lime_test(2);

$p = new RozsahParser();

$t->is_deeply($p->parse('2P + 3C'), array('P'=>2, 'C'=>3));
$t->is_deeply($p->parse('2K + 3C + 1L'), array('K'=>2, 'C'=>3, 'L'=>1));