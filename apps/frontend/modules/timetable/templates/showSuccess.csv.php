"Deň","Začiatok","Koniec","Trvanie","Miestnosť","Forma","Názov predmetu","Vyučujúci"
<?php

/**
 * Escapni CSV field ak treba (podla RFC 4180)
 * referencia: http://tools.ietf.org/html/rfc4180
 *
 * @param string $field obsah policka
 * @param string $delimiter oddelovac policok
 * @param string $enclosure escapovacia obalka
 * @return string
 */
function csv_esc($field, $delimiter=',', $enclosure='"') {
    $needs_escape = strlen($field) == 0;
    $needs_escape = $needs_escape || (strpos($field, $delimiter)!== false);
    $needs_escape = $needs_escape || (strpos($field, $enclosure)!== false);
    $needs_escape = $needs_escape || (strpos($field, "\n")!== false);
    $needs_escape = $needs_escape || (strpos($field, "\r")!== false);
    if (!$needs_escape) {
        return $field;
    }
    return $enclosure.str_replace($enclosure, $enclosure.$enclosure, $field).$enclosure;
}

foreach ($layout->getLessons() as $lesson) {
    echo csv_esc(Candle::formatShortDay($lesson['day']));
    echo ',';
    echo csv_esc(Candle::formatTime($lesson['start']));
    echo ',';
    echo csv_esc(Candle::formatTime($lesson['end']));
    echo ',';
    if ($layout->isFMPHLike()) {
        $trvanie = (($lesson['end']-$lesson['start'])/45).' v.hod.';
    }
    else {
        $trvanie = ($lesson['end']-$lesson['start']).' min.';
    }
    echo csv_esc($trvanie);
    echo ',';
    echo csv_esc($lesson['Room']['name']);
    echo ',';
    echo csv_esc($lesson['LessonType']['name']);
    echo ',';
    echo csv_esc($lesson['Subject']['name']);
    echo ',';
    $teachers = '';
    $first = true;
    foreach ($lesson['Teacher'] as $teacher) {
        if (!$first) {
            $teachers .= ', ';
        }
        $first = false;
        $teachers .= Candle::formatShortName($teacher);
    }
    echo csv_esc($teachers);
    echo "\r\n"; // vid rfc uvedene vyssie
}
