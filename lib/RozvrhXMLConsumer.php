<?php

/**

    Copyright 2012 Martin Sucha

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
 * Interface for consumers of parse events from RozvrhXMLParser
 */

interface RozvrhXMLConsumer
{
    /**
     * @param array $location current location within XML
     *              array(
     *                  'line' => integer,
     *                  'column' => integer,
     *                  'byte_offset' => integer,
     *                  'element_path' => array(string),
     *              )
     *              see RozvrhXMLParser->getCurrentLocation()
     * @param array $data
     *              array(
     *                  'verzia' => integer unix time,
     *                  'semester' => string,
     *                  'skolrok' => string,
     *              )
     */
    public function consumeRozvrh(array $location, array $data);
    
    /**
     * @param array $location current location within XML
     *              array(
     *                  'line' => integer,
     *                  'column' => integer,
     *                  'byte_offset' => integer,
     *                  'element_path' => array(string),
     *              )
     *              see RozvrhXMLParser->getCurrentLocation()
     * @param array $data
     *              array(
     *                  'id' => string,
     *                  'popis' => string,
     *              )
     */
    public function consumeTypHodiny(array $location, array $data);
    
    /**
     * @param array $location current location within XML
     *              array(
     *                  'line' => integer,
     *                  'column' => integer,
     *                  'byte_offset' => integer,
     *                  'element_path' => array(string),
     *              )
     *              see RozvrhXMLParser->getCurrentLocation()
     * @param array $data
     *              array(
     *                  'id' => string,
     *                  'popis' => string,
     *              )
     */
    public function consumeTypMiestnosti(array $location, array $data);
    
    /**
     * @param array $location current location within XML
     *              array(
     *                  'line' => integer,
     *                  'column' => integer,
     *                  'byte_offset' => integer,
     *                  'element_path' => array(string),
     *              )
     *              see RozvrhXMLParser->getCurrentLocation()
     * @param array $data
     *              array(
     *                  'nazov' => string,
     *                  'kapacita' => string,
     *                  'typ' => string,
     *              )
     */
    public function consumeMiestnost(array $location, array $data);
    
    /**
     * @param array $location current location within XML
     *              array(
     *                  'line' => integer,
     *                  'column' => integer,
     *                  'byte_offset' => integer,
     *                  'element_path' => array(string),
     *              )
     *              see RozvrhXMLParser->getCurrentLocation()
     * @param array $data
     *              array(
     *                  'id' => string,
     *                  'priezvisko' => string,
     *                  'meno' => string,
     *                  'iniciala' => string,
     *                  'katedra' => string,
     *                  'oddelenie' => string,
     *                  'login' => string,
     *              )
     */
    public function consumeUcitel(array $location, array $data);
    
    /**
     * @param array $location current location within XML
     *              array(
     *                  'line' => integer,
     *                  'column' => integer,
     *                  'byte_offset' => integer,
     *                  'element_path' => array(string),
     *              )
     *              see RozvrhXMLParser->getCurrentLocation()
     * @param array $data
     *              array(
     *                  'id' => string,
     *                  'nazov' => string,
     *                  'kod' => string,
     *                  'kratkykod' => string,
     *                  'kredity' => string,
     *                  'rozsah' => string,
     *              )
     */
    public function consumePredmet(array $location, array $data);
    
    /**
     * @param array $location current location within XML
     *              array(
     *                  'line' => integer,
     *                  'column' => integer,
     *                  'byte_offset' => integer,
     *                  'element_path' => array(string),
     *              )
     *              see RozvrhXMLParser->getCurrentLocation()
     * @param array $data
     *              array(
     *                  'id' => string,
     *                  'den' => string,
     *                  'zaciatok' => string,
     *                  'koniec' => string,
     *                  'miestnost' => string,
     *                  'trvanie' => string,
     *                  'predmet' => string,
     *                  'ucitelia' => string,
     *                  'kruzky' => string,
     *                  'typ' => string,
     *                  'zviazanehodiny' => string,
     *                  'oldid' => string,
     *                  'zviazanieoldid' => string,
     *                  'poznamka' => string,
     *              )
     */
    public function consumeHodina(array $location, array $data);
}