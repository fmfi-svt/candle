<?php require_once dirname(__FILE__).'/../bootstrap/unit.php';

/**

    Copyright 2010 Martin Sucha

    This file is part of Candle.

    Candle is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Candle is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Candle.  If not, see <http://www.gnu.org/licenses/>.

*/

/**
 *
 */


$t = new lime_test(7);

$v = new DayTimeSpecValidator();

try {
    $t->is_deeply($v->clean('Po 00:00-00:10'), array(array(0,10)));
}
catch (sfValidatorError $e) {
    $t->fail('This input did not validate');
}

try {
    $t->is_deeply($v->clean('Po 00:00-Po 00:10'), array(array(0,10)));
}
catch (sfValidatorError $e) {
    $t->fail('This input did not validate');
}

try {
    $t->is_deeply($v->clean('Po 1:10-2:20'), array(array(70,140)));
}
catch (sfValidatorError $e) {
    $t->fail('This input did not validate');
}

try {
    $t->is_deeply($v->clean('Ut 00:00-00:00'), array(array(24*60,24*60)), 'Funguje jeden den');
}
catch (sfValidatorError $e) {
    $t->fail('This input did not validate. Funguje jeden den.');
}

try {
    $t->is_deeply($v->clean('Ut 00:00-St 00:00'), array(array(24*60,2*24*60)), 'Funguju dva dni');
}
catch (sfValidatorError $e) {
    $t->fail('This input did not validate. Funguju dva dni');
}

try {
    $v->clean('St 10:00-00:10');
    $t->fail('Allows negative intervals');
}
catch (sfValidatorError $e) {
    $t->pass();
}

try {
    $t->is_deeply($v->clean('  Ut 00:00-St 00:00, Po 1:00-2:00, Ut 00:00-2:00'), array(array(24*60,2*24*60), array(60,120),array(24*60,26*60)), 'Funguju dva dni');
}
catch (sfValidatorError $e) {
    $t->fail('This input did not validate. Funguje zoznam specifikacii');
}
