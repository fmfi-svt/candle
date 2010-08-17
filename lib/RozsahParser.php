<?php

/**
 * Parser for rozsah string from AIS2
 */
class RozsahParser {

    /**
     *
     * @param string $rozsah (e.g. '2P + 3C')
     * @return array('P'=>2, 'C'=>3)
     */
    public function parse($rozsah) {
        $rozsah = str_replace(array(' ', "\t", "\n"), '', $rozsah);

        $subparts = explode('+', $rozsah);

        $result = array();

        foreach ($subparts as $subpart) {
            $matches = array();
            if (!preg_match('@^(\d+)([A-Z])$@', $subpart, $matches)) {
                throw new Exception('Invalid subpart in rozsah');
            }
            if (isset($result[$matches[2]])) {
                throw new Exception('Already existing subpart');
            }
            $result[$matches[2]] = intval($matches[1]);
        }
        
        return $result;
    }

}