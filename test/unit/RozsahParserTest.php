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


$t = new lime_test(2);

$p = new RozsahParser();

$t->is_deeply($p->parse('2P + 3C'), array('P'=>2, 'C'=>3));
$t->is_deeply($p->parse('2K + 3C + 1L'), array('K'=>2, 'C'=>3, 'L'=>1));