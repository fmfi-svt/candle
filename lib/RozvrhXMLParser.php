<?php

/**

    Copyright 2010, 2011, 2012 Martin Sucha

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


class RozvrhXMLParser
{

  protected static $ucitelFields = array('priezvisko', 'meno', 'iniciala', 'katedra', 'oddelenie', 'login');
  protected static $miestnostFields = array('nazov', 'kapacita', 'typ');
  protected static $predmetFields = array('nazov', 'kod', 'kratkykod', 'kredity', 'rozsah');
  protected static $hodinaFields = array('den', 'zaciatok', 'koniec', 'miestnost', 'trvanie', 'predmet', 'ucitelia', 'kruzky', 'typ', 'zviazanehodiny', 'oldid', 'zviazaneoldid', 'poznamka');
  
  const STATE_ROOT = 0;
  const STATE_TYPY = 1;
  const STATE_TYPYMIESTNOSTI = 2;
  const STATE_UCITELIA = 3;
  const STATE_MIESTNOSTI = 4;
  const STATE_PREDMETY = 5;
  const STATE_HODINY = 6;
  
  /** @var resource parser */
  protected $parser;
  
  /** @var RozvrhXMLConsumer */
  protected $consumer;

  protected function getCurrentLocation()
  {
      return array(
          'line' => xml_get_current_line_number($this->parser),
          'column' => xml_get_current_column_number($this->parser),
          'byte_offset' => xml_get_current_byte_index($this->parser),
          'element_path' => $this->currentElementPath,
      );
  }
  
  public function formatLocation(array $location = null) {
      if ($location == null) {
          $location = $this->getCurrentLocation();
      }
      $text = 'Line ' . $location['line'];
      $text .= ', Column ' . $location['column'];
      $text .= ' (' . implode('.', $location['element_path']) . ')';
      return $text;
  }

  protected function rethrowParseError($parser, Exception $exception) {
      $message = 'Error at ';
      $message .= $this->formatLocation();
      $message .= ': ' . $exception->getMessage();
      throw new Exception($message, 0, $exception);
  }

  protected function parseError($parser, $description) {
      $message = 'Error at ';
      $message .= $this->formatLocation();
      $message .= ': ' . $description;
      throw new Exception($message);
  }

  protected function parseErrorUnexpectedElement($parser, $name) {
      $this->parseError($parser, 'Unexpected element: '.$name);
  }

  protected function setActiveField($parser, $name) {
      if ($this->dataField !== null) {
          $this->parseErrorUnexpectedElement($parser, $name);
      }
      if (isset($this->elementData[$name])) {
          $this->parseError($parser, 'Value for '.$name.' field already set');
      }
      $this->dataField = $name;
  }

  protected function parser_startElement($parser, $name, $attrs) {
      //echo '<'.$name.' '.$this->state."\n";
      $this->currentElementPath[] = $name;
      if ($this->state == self::STATE_ROOT) {
          if ($name == 'rozvrh') {
              if (!isset($attrs['verzia'])) {
                  $this->parseError($parser, 'Element "rozvrh" missing attribute "verzia"');
              }
              if (!isset($attrs['skolrok'])) {
                  $this->parseError($parser, 'Element "rozvrh" missing attribute "skolrok"');
              }
              if (!isset($attrs['semester'])) {
                  $this->parseError($parser, 'Element "rozvrh" missing attribute "semester"');
              }
              // Toto je az od PHP 5.3
              //$this->version = DateTime::createFromFormat('YmdHis', $attrs['verzia'])->getTimestamp();
              $v = $attrs['verzia'];
              $ty = intval(substr($v, 0, 4));
              $tm = intval(substr($v, 4, 2));
              $td = intval(substr($v, 6, 2));
              $th = intval(substr($v, 8, 2));
              $ti = intval(substr($v, 10, 2));
              $ts = intval(substr($v, 12, 2));
              $this->consumer->consumeRozvrh($this->getCurrentLocation(),
                      array(
                          'verzia' => mktime($th, $ti, $ts, $tm, $td, $ty),
                          'semester' => $attrs['semester'],
                          'skolrok' => $attrs['skolrok'],
                      ));
          }
          else if ($name == 'typy') {
              $this->state = self::STATE_TYPY;
          }
          else if ($name == 'typymiestnosti') {
              $this->state = self::STATE_TYPYMIESTNOSTI;
          }
          else if ($name == 'ucitelia') {
              $this->state = self::STATE_UCITELIA;
          }
          else if ($name == 'miestnosti') {
              $this->state = self::STATE_MIESTNOSTI;
          }
          else if ($name == 'predmety') {
              $this->state = self::STATE_PREDMETY;
          }
          else if ($name == 'hodiny') {
              $this->state = self::STATE_HODINY;
          }
          else {
              $this->parseErrorUnexpectedElement($parser, $name);
          }
      }
      else if ($this->state == self::STATE_TYPY) {
          if ($name == 'typ') {
              if ($this->elementData != null) {
                  $this->parseErrorUnexpectedElement ($parser, $name);
              }
              $this->elementData = array();
              $this->elementData['id'] = $attrs['id'];
              $this->elementData['popis'] = $attrs['popis'];
              try {
                $this->consumer->consumeTypHodiny($this->getCurrentLocation(),
                        $this->elementData);
              }
              catch (Exception $e) {
                $this->rethrowParseError($parser, $e);
              }
          }
          else {
              $this->parseErrorUnexpectedElement($parser, $name);
          }
      }
      else if ($this->state == self::STATE_TYPYMIESTNOSTI) {
          if ($name == 'typmiestnosti') {
              if ($this->elementData != null) {
                  $this->parseErrorUnexpectedElement ($parser, $name);
              }
              $this->elementData = array();
              $this->elementData['id'] = $attrs['id'];
              $this->elementData['popis'] = $attrs['popis'];
              try {
                $this->consumer->consumeTypMiestnosti($this->getCurrentLocation(),
                        $this->elementData);
              }
              catch (Exception $e) {
                $this->rethrowParseError($parser, $e);
              }
          }
          else {
              $this->parseErrorUnexpectedElement($parser, $name);
          }
      }
      else if ($this->state == self::STATE_UCITELIA) {
          if ($name == 'ucitel') {
              if ($this->elementData != null) {
                  $this->parseErrorUnexpectedElement ($parser, $name);
              }
              $this->elementData = array();
              $this->elementData['id'] = $attrs['id'];
              $this->dataFields = self::$ucitelFields;
          }
          else if ($this->dataFields != null) {
              $this->setActiveField($parser, $name);
          }
          else {
              $this->parseErrorUnexpectedElement($parser, $name);
          }
      }
      else if ($this->state == self::STATE_MIESTNOSTI) {
          if ($name == 'miestnost') {
              if ($this->elementData != null) {
                  $this->parseErrorUnexpectedElement ($parser, $name);
              }
              $this->elementData = array();
              $this->dataFields = self::$miestnostFields;
          }
          else if ($this->dataFields != null) {
              $this->setActiveField($parser, $name);
          }
          else {
              $this->parseErrorUnexpectedElement($parser, $name);
          }
      }
      else if ($this->state == self::STATE_PREDMETY) {
          if ($name == 'predmet') {
              if ($this->elementData != null) {
                  $this->parseErrorUnexpectedElement ($parser, $name);
              }
              $this->elementData = array();
              $this->elementData['id']=$attrs['id'];
              $this->dataFields = self::$predmetFields;
          }
          else if ($this->dataFields != null) {
              $this->setActiveField($parser, $name);
          }
          else {
              $this->parseErrorUnexpectedElement($parser, $name);
          }
      }
      else if ($this->state == self::STATE_HODINY) {
          if ($name == 'hodina') {
              if ($this->elementData != null) {
                  $this->parseErrorUnexpectedElement ($parser, $name);
              }
              $this->elementData = array();
              $this->elementData['id']=$attrs['id'];
              $this->dataFields = self::$hodinaFields;
          }
          else if ($this->dataFields != null) {
              $this->setActiveField($parser, $name);
          }
          else {
              $this->parseErrorUnexpectedElement($parser, $name);
          }
      }
      else {
          $this->parseError($parser, 'Some internal state messed, '.$this->state);
      }
  }

  protected function parser_endElement($parser, $name) {
      //echo '</'.$name."\n";
      $this->dataField = null;
      if ($this->state == self::STATE_TYPY) {
          if ($name == 'typy') {
              $this->state = self::STATE_ROOT;
          }
          else if ($name == 'typ') {
              $this->elementData = null;
          }
      }
      else if ($this->state == self::STATE_TYPYMIESTNOSTI) {
          if ($name == 'typymiestnosti') {
              $this->state = self::STATE_ROOT;
          }
          else if ($name == 'typmiestnosti') {
              $this->elementData = null;
          }
      }
      else if ($this->state == self::STATE_UCITELIA) {
          if ($name == 'ucitelia') {
              $this->state = self::STATE_ROOT;
          }
          else if ($name == 'ucitel') {
              $this->dataFields = null;
              try {
                $this->consumer->consumeUcitel($this->getCurrentLocation(),
                        $this->elementData);
              }
              catch (Exception $e) {
                  $this->rethrowParseError($parser, $e);
              }
              $this->elementData = null;
          }
      }
      else if ($this->state == self::STATE_MIESTNOSTI) {
          if ($name == 'miestnosti') {
              $this->state = self::STATE_ROOT;
          }
          else if ($name == 'miestnost') {
              $this->dataFields = null;
              try {
                $this->consumer->consumeMiestnost($this->getCurrentLocation(),
                        $this->elementData);
              }
              catch (Exception $e) {
                  $this->rethrowParseError($parser, $e);
              }
              $this->elementData = null;
          }
      }
      else if ($this->state == self::STATE_PREDMETY) {
          if ($name == 'predmety') {
              $this->state = self::STATE_ROOT;
          }
          else if ($name == 'predmet') {
              $this->dataFields = null;
              try {
                $this->consumer->consumePredmet($this->getCurrentLocation(),
                        $this->elementData);
              }
              catch (Exception $e) {
                  $this->rethrowParseError($parser, $e);
              }
              $this->elementData = null;
          }
      }
      else if ($this->state == self::STATE_HODINY) {
          if ($name == 'hodiny') {
              $this->state = self::STATE_ROOT;
          }
          else if ($name == 'hodina') {
              $this->dataFields = null;
              try {
                $this->consumer->consumeHodina($this->getCurrentLocation(),
                        $this->elementData);
              }
              catch (Exception $e) {
                  $this->rethrowParseError($parser, $e);
              }
              $this->elementData = null;
          }
      }
      // Remove last path element
      array_splice($this->currentElementPath, -1);
  }

  protected function parser_characterData($parser, $data) {
      if ($this->dataField == null) return;
      if (!isset($this->elementData[$this->dataField])) {
          $this->elementData[$this->dataField] = $data;
      }
      else {
          $this->elementData[$this->dataField] .= $data;
      }
  }
  
  public function __construct(/*RozvrhXMLConsumer*/ $consumer)
  {
    $this->parser = xml_parser_create();
    $this->consumer = $consumer;
    $this->state = self::STATE_ROOT;
    $this->elementData = null;
    xml_set_element_handler($this->parser, array($this, 'parser_startElement'), array($this, 'parser_endElement'));
    xml_set_character_data_handler($this->parser, array($this, 'parser_characterData'));
    xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, false);
  }
  
  /**
   *
   * @param string $data chunk of data
   * @param boolean $is_final true iff this is the last chunk of data
   */
  public function parse($data, $is_final = false)
  {
    $parseResult = xml_parse($this->parser, $data, $is_final);
    if ($parseResult == 0) {
        // parse failed
        $errorCode = xml_get_error_code($this->parser);
        $this->parseError($this->parser, 'Parse error '. $errorCode. ': '.xml_error_string($errorCode));
    }
  }
  
  public function close()
  {
      if ($this->parser !== null) {
        xml_parser_free($this->parser);
      }
      $this->parser = null;
  }
  
  public function __destruct()
  {
    $this->close();
  }
}
