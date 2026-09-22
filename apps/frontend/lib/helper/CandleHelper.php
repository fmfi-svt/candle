<?php
/**

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
 * Pomocné funkcie pre šablóny nového dizajnu.
 *
 * V šablóne sa načítajú cez use_helper('Candle').
 */

/**
 * Položka lišty akcií rozvrhu: odkaz zobrazený ako ikona (Font Awesome)
 * s textovým popisom pre čítačky obrazovky a tooltipom.
 *
 * @param string $icon         názov ikony bez predpony "fa-" (napr. "floppy-o")
 * @param string $label        popis akcie
 * @param mixed  $internal_uri interná URI alebo pole s parametrami pre link_to
 * @param array  $options      ďalšie atribúty odkazu (id, rel, class...)
 */
function candle_action($icon, $label, $internal_uri, $options = array()) {
    $options = array_merge(array('title' => $label, 'class' => 'actions__link'), $options);
    $content = '<i class="fa fa-'.$icon.'" aria-hidden="true"></i>'
             . '<span class="pristupnost">'.$label.'</span>';
    return '<li>'.link_to($content, $internal_uri, $options).'</li>';
}

/**
 * Položka lišty akcií, ktorá vytlačí stránku (obsluhuje timetable.js).
 */
function candle_print_action() {
    return '<li><a href="#" id="timetablePrintButton" class="actions__link js-print" title="Tlačiť">'
         . '<i class="fa fa-print" aria-hidden="true"></i>'
         . '<span class="pristupnost">Tlačiť</span></a></li>';
}

/**
 * Farebné písmeno typu hodiny (P, C, K, S...) s názvom typu v tooltipe.
 */
function candle_lesson_type_badge($lessonType) {
    return '<abbr class="lesson-type '.Candle::getLessonTypeHTMLClass($lessonType).'"'
         . ' title="'.$lessonType['name'].'">'.Candle::upper($lessonType['code']).'</abbr>';
}

/**
 * Zistí, či požiadavka obsahuje niektorý z vyhľadávacích parametrov panelu.
 * V takom prípade sa panel zobrazí hneď vysunutý, aby boli vidno výsledky.
 */
function candle_panel_search_requested($request) {
    foreach (array('showLessons', 'showTeachers', 'showRooms', 'showStudentGroups') as $parameter) {
        if ($request->hasParameter($parameter)) {
            return true;
        }
    }
    return false;
}

/**
 * Koniec hodiny tak, ako sa zobrazuje v mriežke rozvrhu.
 *
 * $layout je TimetableLayout (v šablónach obalený do sfOutputEscaper dekorátora,
 * preto bez typovej kontroly).
 *
 * Matfyzácke hodiny (45 minút + 5 minút prestávka) sa vykresľujú aj s
 * prestávkou, aby 90-minútová hodina zaplnila presne dva riadky mriežky.
 */
function candle_lesson_visual_end($lesson, $layout) {
    $start = intval($lesson['start']);
    $end = intval($lesson['end']);
    if ($layout->isLessonFMPHLike($lesson)) {
        $end += intval(($end - $start) / 45) * 5;
    }
    // chybné dáta (hodina s nulovou dĺžkou) nech sú aspoň trochu viditeľné
    return max($end, $start + 5);
}

/**
 * Rozdelí hodiny jedného dňa do skupín, ktoré sa časovo prekrývajú.
 *
 * Hodiny v jednej skupine sa vykresľujú ako karty poukladané na seba.
 *
 * @return array pole skupín tvaru
 *               array('start' => int, 'end' => int,
 *                     'lessons' => array(array('lesson' => ..., 'start' => int, 'end' => int), ...))
 */
function candle_timetable_clusters($dayLessons, $layout) {
    $items = array();
    foreach ($dayLessons as $lesson) {
        $items[] = array(
            'lesson' => $lesson,
            'start' => intval($lesson['start']),
            'end' => candle_lesson_visual_end($lesson, $layout),
        );
    }
    usort($items, 'candle_compare_lesson_items');

    $clusters = array();
    $current = null;
    foreach ($items as $item) {
        if ($current !== null && $item['start'] < $current['end']) {
            $current['lessons'][] = $item;
            $current['end'] = max($current['end'], $item['end']);
        }
        else {
            if ($current !== null) {
                $clusters[] = $current;
            }
            $current = array('start' => $item['start'], 'end' => $item['end'], 'lessons' => array($item));
        }
    }
    if ($current !== null) {
        $clusters[] = $current;
    }
    return $clusters;
}

function candle_compare_lesson_items($a, $b) {
    if ($a['start'] != $b['start']) {
        return $a['start'] - $b['start'];
    }
    return $a['end'] - $b['end'];
}
