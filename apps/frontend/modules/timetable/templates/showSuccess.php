<?php

slot('panel');
include_component('timetable','panel',array('timetable'=>$timetable, 'timetable_id'=>$timetable_id));
end_slot();

slot('top');
include_component('timetable','top', array('timetable'=>$timetable));
end_slot();

// pocitadla pre stlpce
$days = $layout->getDays();

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
    $mintime = 490; // TODO zmenit casy generovania tabuliek
    $maxtime = 1190;
    $time_header_spans = 1; // kolko riadkov zabera hlavicka s casom 
}
else {
    $rowmins = $rowspanmins = 5;
    $time_header_spans = 12; // na kazdu hodinu
    $mintime = Candle::floorTo($layout->getLessonMinTime(), 60);
    $maxtime = Candle::ceilTo($layout->getLessonMaxTime(), 60);
}

?>
<form method="post" action=""><div>
<table id="rozvrh" <?php if (!$layout->isFMPHLike()) echo 'class="precise"' ?>>
    <tr>
        <th class="pristupnost">Začiatok</th>
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
                echo '<td '.Candle::formatRowspan($time_header_spans).'>'.Candle::formatTime($time).'</td>';
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
                    if ($lesson->getEnd()<=$time) {
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
                    if ($lesson->getStart()==$time) {
                        $rowspan = intval($lesson->getLength()/$rowspanmins);
                        echo '<td class="hodina" '.Candle::formatRowspan($rowspan).'>';
                        echo $lesson->getSubject();
                        echo '<br />';
                        echo Candle::formatTime($lesson->getEnd());
                        echo '</td>';
                    }
                    else if ($lesson->getStart()<$time && $lesson->getEnd()>$time) {
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
</div></form>
