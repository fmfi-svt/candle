<?php

// Tato stranka sa automaticky refreshuje, takze vypiname analytics
slot('no_analytics', '');

slot('title', 'Aktuálne voľné miestnosti');

slot('top');
include_component('timetable','top');
end_slot();

slot('header');
?>
<h1>Aktuálne voľné miestnosti</h1>
<span class="header_subtitle">v čase <?php echo date('H:i', $queryTime); ?></span>
<ul class="quickswitch">
    <?php $options = array(0,15,30,60);
        foreach ($options as $option) {
            ?><li<?php echo ($option == $offsetMinutes)?' class="active"':'';
            ?>><?php echo link_to('+'.$option.'m', array('sf_route' => 'freeRoom_current', 'offset' => $option)); ?></li><?php
        }
    ?>
</ul>
<?php
end_slot();

if (count($freeRoomIntervals) == 0):
    echo 'Momentálne nie je voľná žiadna miestnosť.';
else:
?>
<p>Tu zobrazené miestnosti nemusia byť v skutočnosti voľné, pretože sa dajú rezervovať aj mimo rozvrhu v AISe!</p>
<?php

$cols = array(0, intval((count($freeRoomIntervals) + 1) / 2), count($freeRoomIntervals));
echo '<div class="aktualne_wrap">';
for ($j = 0; $j < count($cols) - 1; $j++):
?>
<table class="aktualne <?php echo 'stlpec' . $j ?>">
    <thead>
        <tr><th>Čas</th><th>Miestnosť</th></tr>
    </thead>
    <tbody>
<?php
for ($i = $cols[$j]; $i < $cols[$j + 1]; $i++) {
    $interval = $freeRoomIntervals[$i];
    echo '<tr>';
    echo '<td>';
    echo Candle::formatTime($interval['start'], true);
    echo '&#x2011;';
    echo Candle::formatTime($interval['end'], true);
    echo '</td>';
    echo '<td>';
    echo link_to($interval['Room']['name'], array('sf_route'=>'room_show', 'sf_subject'=>$interval['Room']));
    echo '</td>';
    
    echo '</tr>';
    
}
?>
    </tbody>
</table>
<?php endfor;
echo '</div>';
endif;
