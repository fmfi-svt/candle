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


$t = new lime_test(13);

$v = new TimeAmountValidator();

try {
    $t->is($v->clean('2h'), 120);
}
catch (sfValidatorError $e) {
    $t->fail('This input did not validate');
}

try {
    $t->is($v->clean('2h5m'), 125);
}
catch (sfValidatorError $e) {
    $t->fail('This input did not validate');
}

try {
    $t->is($v->clean('2h 5m'), 125);
}
catch (sfValidatorError $e) {
    $t->fail('This input did not validate');
}

try {
    $t->is($v->clean('2:05'), 125);
}
catch (sfValidatorError $e) {
    $t->fail('This input did not validate');
}

try{
    $t->is($v->clean('5m'), 5);
}
catch (sfValidatorError $e) {
    $t->fail('This input did not validate');
}

try {
    $t->is($v->clean('2.5h'), 150);
}
catch (sfValidatorError $e) {
    $t->fail('This input did not validate');
}

try {
    $t->is($v->clean('2,5h'), 150);
}
catch (sfValidatorError $e) {
    $t->fail('This input did not validate');
}

try {
    $t->is($v->clean('2vh'),90);
}
catch (sfValidatorError $e) {
    $t->fail('This input did not validate');
}

$t->is($v->clean('2 v.h'),90);
$t->is($v->clean('2 v.h.'),90);

try {
    $v->clean('jksdjdks');
    $t->fail();
}
catch (sfValidatorError $e) {
    $t->pass();
}

try {
    $v->clean('h5m');
    $t->fail();
}
catch (sfValidatorError $e) {
    $t->pass();
}

try {
    $v->clean('5hm');
    $t->fail();
}
catch (sfValidatorError $e) {
    $t->pass();
}
