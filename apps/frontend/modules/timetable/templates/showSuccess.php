<?php

slot('title', $timetable->getName());

slot('panel');
include_component('timetable','panel',array('timetable'=>$timetable, 'timetable_id'=>$timetable_id));
end_slot();

slot('top');
include_component('timetable','top', array('timetable'=>$timetable, 'timetable_id'=>$timetable_id));
end_slot();

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
    $mintime = 490; 
    $maxtime = 1190;
    $mintime = min($mintime, 40+Candle::floorTo($layout->getLessonMinTime()-40, 50));
    $maxtime = max($maxtime, 40+Candle::ceilTo($layout->getLessonMaxTime()-40, 50));
    $time_header_spans = 1; // kolko riadkov zabera hlavicka s casom 
}
else {
    $rowmins = $rowspanmins = 5;
    $time_header_spans = 12; // na kazdu hodinu
    $mintime = Candle::floorTo($layout->getLessonMinTime(), 60);
    $maxtime = Candle::ceilTo($layout->getLessonMaxTime(), 60);
}

?>
<?php echo form_tag('@timetable_change_lessons?id='.$timetable_id); ?><div>
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
            echo '<tr>';
            if (($row_number % $time_header_spans) == 0) {
                echo '<td '.Candle::formatRowspan($time_header_spans).' class="cas">'.Candle::formatTime($time).'</td>';
            }
            for ($day = 0; $day < 5; $day++) {
                foreach ($days[$day] as $ix=>$col) {
                    $pos = $counters[$day][$ix];
                    if ($pos >= count($days[$day][$ix])) {
                        // tento stlpec je hotovy
                        echo '<td></td>';
                        continue;
                    }
                    $lesson = $days[$day][$ix][$pos];
                    if ($lesson['end']<=$time) {
                        // je treba sa posunut dalej
                        $counters[$day][$ix]++;
                        $pos = $counters[$day][$ix];
                        if ($pos >= count($days[$day][$ix])) {
                            // tento stlpec je hotovy
                            echo '<td></td>';
                            continue;
                        }
                        $lesson = $days[$day][$ix][$pos];
                    }
                    // treba vypisat bunku?
                    if ($lesson['start']==$time) {
                        $rowspan = intval(($lesson['end']-$lesson['start'])/$rowspanmins);
                        $highlighted = $timetable->isLessonHighlighted($lesson['id']);
                        $additionalClass = '';
                        if ($highlighted) {
                            $additionalClass .= ' highlighted';
                        }
                        echo '<td class="hodina'.$additionalClass.'" '.Candle::formatRowspan($rowspan).'>';
                        include_partial('timetable/cell', array('lesson'=>$lesson, 'highlighted'=>$highlighted));
                        echo '</td>';
                    }
                    else if ($lesson['start']<$time && $lesson['end']>$time) {
                        // treba nevypisat nic
                    }
                    else {
                        // treba vypisat prazdnu bunku
                        echo '<td></td>';
                    }
                }
            }
            echo '</tr>';
        }
    ?>
</table>
</div>
<div>
    <button type="submit" class="jshide">Upraviť rozvrh</button>
</div>
<?php echo '</form>' ?>
