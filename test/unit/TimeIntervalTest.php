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

$t = new lime_test(42);

$i1 = new TimeInterval(123,456);
$i2 = new TimeInterval(1440+123, 1440+456);
$i3 = new TimeInterval(1440, 2*1440);
$i4 = new TimeInterval(1440+123, 2*1440+1);
$i5 = new TimeInterval(234, 567);
$i6 = new TimeInterval(456, 789);
$i7 = new TimeInterval(500, 1000);
$i8 = new TimeInterval(2*1440+500, 4*1440+400);

$arr1 = array($i1, $i2, $i3, $i4, $i5, $i8);
$arr2 = array(new TimeInterval(123, 567),
                new TimeInterval(1440, 2*1440+1),
                new TimeInterval(2*1440+500, 4*1440+400)
            );
$arr3 = array(new TimeInterval(123, 567),
                new TimeInterval(1440, 2*1440),
                new TimeInterval(2*1440, 2*1440+1),
                new TimeInterval(2*1440+500, 3*1440),
                new TimeInterval(3*1440, 4*1440),
                new TimeInterval(4*1440, 4*1440+400)
            );

$triples = array(
    array(0, 123, 567),
    array(1, 0, 1440),
    array(2, 0, 1),
    array(2, 500, 1440),
    array(3, 0, 1440),
    array(4, 0, 400),

        );

$t->is($i1->getStart(),123);
$t->is($i1->getEnd(), 456);

$t->is($i1->getStartDay(),0);
$t->is($i1->getStartTime(),123);
$t->is($i1->getEndDay(),0);
$t->is($i1->getEndTime(),456);

$t->is($i2->getStartDay(), 1);
$t->is($i2->getStartTime(), 123);
$t->is($i2->getEndDay(), 1);
$t->is($i2->getEndTime(), 456);

$t->is($i1->overlapsDay(), false);
$t->is($i2->overlapsDay(), false);
$t->is($i3->overlapsDay(), false);
$t->is($i4->overlapsDay(), true);

$t->is($i1->contains(200), true);
$t->is($i1->contains(123), true);
$t->is($i1->contains(456), true);
$t->is($i1->contains(100), false);
$t->is($i1->contains(500), false);

$t->is($i1->intersects($i5), true);
$t->is($i5->intersects($i1), true);
$t->is($i1->intersects($i6), true);
$t->is($i6->intersects($i1), true);
$t->is($i1->intersects($i7), false);
$t->is($i7->intersects($i1), false);

$t->is($i1->extend($i5)->getStart(), 123);
$t->is($i1->extend($i5)->getEnd(), 567);
$t->is($i5->extend($i1)->getStart(), 123);
$t->is($i5->extend($i1)->getEnd(), 567);

$t->is($i1->union($i7), null);
$t->isnt($i1->union($i5), null);
$t->is($i1->union($i5)->getStart(), 123);
$t->is($i1->union($i5)->getEnd(), 567);

// tests of this test
$t->ok(TimeInterval::intervalArraysEqual($arr1, $arr1));
$t->ok(!TimeInterval::intervalArraysEqual($arr1, $arr2));

$t->ok(TimeInterval::intervalArraysEqual(TimeInterval::mergeIntervals($arr1), $arr2));

$t->ok(TimeInterval::intervalArraysEqual(TimeInterval::splitIntervalsByDays($arr2), $arr3),'splitIntervalsByDays');

$t->is_deeply(TimeInterval::convertIntervalsToTriplesArray($arr3), $triples, 'convertIntervalsToTriplesArray');

$t->ok(TimeInterval::intervalArraysEqual(array($i1->intersect($i5)), array(new TimeInterval(234, 456))));
$t->ok(TimeInterval::intervalArraysEqual(array($i1->intersect($i6)), array(new TimeInterval(456, 456))));
$t->is($i1->intersect($i7)->getLength(), 0);

$t->ok(TimeInterval::intervalArraysEqual($i5->intersectArray(array($i1, $i6)),
            array(new TimeInterval(234, 456), new TimeInterval(456, 567))));