<?php

$days = $layout->tableLayout();

// pocitadla pre stlpce
$counters = array();
for ($i = 0; $i < 5; $i++) {
    $col_counters = array();

    for ($j = 0; $j < count($days[$i]); $j++ ) {
        $col_counters[] = 0;
    }

    $counters[] = $col_counters;
}

if ($layout->isFMPHLike()) {
    $rowmins = 50; // pocet minut na jeden riadok tabulky
    $rowspanmins = 45; // pocet minut dlzky, za ktore sa ma vygenerovat jeden rowspan
    $time_header_spans = 1; // kolko riadkov zabera hlavicka s casom
}
else {
    $rowmins = $rowspanmins = 5;
    $time_header_spans = 10; // na kazdych 50 min
}
$mintime = 490;
$maxtime = 1190;
$mintime = min($mintime, Candle::floorTo($layout->getLessonMinTime(), 50, 40));
$maxtime = max($maxtime, Candle::ceilTo($layout->getLessonMaxTime(), 50, 40));
?>
<table id="rozvrh" <?php if (!$layout->isFMPHLike()) echo 'class="precise"' ?>>
    <tr>
        <th class="zaciatok"><span class="pristupnost">Začiatok</span></th>
        <?php
            for ($day = 0; $day < 5; $day++) {
                $cols = count($days[$day]);
                if ($cols == 1) {
                    echo '<th>';
                }
                else {
                    echo '<th colspan="'.$cols.'">';
                }
                echo Candle::formatLongDay($day);
                echo '</th>';
            }
        ?>
    </tr>
    <?php
        for ($time = $mintime, $row_number = 0; $time < $maxtime; $time += $rowmins, $row_number++) {
            $lineStartingTimeHeader = ($row_number % $time_header_spans) == 0;
            if ($lineStartingTimeHeader) {
                echo '<tr class="startTimeHeader">';
                echo '<td '.Candle::formatRowspan($time_header_spans).' class="cas">'.Candle::formatTime($time).'</td>';
            }
            else {
                echo '<tr>';
            }
            for ($day = 0; $day < 5; $day++) {
                $column_count = count($days[$day]);
                foreach ($days[$day] as $ix=>$col) {
                    $cell_classes = array();

                    if ($ix == 0) {
                        $cell_classes[] = 'startOfDayColumn';
                    }
                    if ($ix == $column_count-1) {
                        $cell_classes[] = 'endOfDayColumn';
                    }
                    
                    $pos = $counters[$day][$ix];
                    if ($pos >= count($days[$day][$ix])) {
                        // tento stlpec je hotovy
                        $cell_classes[] = 'emptyCell';
                        echo Candle::formatTD($cell_classes).'</td>';
                        continue;
                    }
                    $lesson = $days[$day][$ix][$pos];
                    if ($lesson['end']<=$time) {
                        // je treba sa posunut dalej
                        $counters[$day][$ix]++;
                        $pos = $counters[$day][$ix];
                        if ($pos >= count($days[$day][$ix])) {
                            // tento stlpec je hotovy
                            $cell_classes[] = 'emptyCell';
                            echo Candle::formatTD($cell_classes).'</td>';
                            continue;
                        }
                        $lesson = $days[$day][$ix][$pos];
                    }
                    // treba vypisat bunku?
                    if ($lesson['start']==$time) {
                        $rowspan = intval(($lesson['end']-$lesson['start'])/$rowspanmins);
                        $highlighted = $timetable->isLessonHighlighted($lesson['id']);
                        $cell_classes[] = 'hodina';
                        if ($highlighted) {
                            $cell_classes[] = 'highlighted';
                        }
                        $teachers = Candle::formatShortNameList($lesson['Teacher']);
                        $lessonTimeInfo = Candle::formatShortDay($lesson['day']) . ' ' .
                                                Candle::formatTime($lesson['start']). '-' .
                                                Candle::formatTime($lesson['end']);
                        $lessonTitle = $lesson['Room']['name'] . ', ' .
                                       $lessonTimeInfo . ', ' . 
                                       $lesson['Subject']['short_code'] .
                                       ': '.$teachers;

                        $cell_classes[] = Candle::getLessonTypeHTMLClass($lesson['LessonType']);
                        echo Candle::formatTD($cell_classes, $rowspan, $lessonTitle);
                        include_partial('timetable/cell', array('lesson'=>$lesson, 'highlighted'=>$highlighted, 'editable'=>$editable));
                        echo '</td>';
                    }
                    else if ($lesson['start']<$time && $lesson['end']>$time) {
                        // treba nevypisat nic
                    }
                    else {
                        // treba vypisat prazdnu bunku
                        $cell_classes[] = 'emptyCell';
                        echo Candle::formatTD($cell_classes).'</td>';
                    }
                }
            }
            echo '</tr>';
        }
    ?>
</table>
<?php include_partial('timetable/list', array('timetable'=>$timetable, 'layout'=>$layout)); ?>
