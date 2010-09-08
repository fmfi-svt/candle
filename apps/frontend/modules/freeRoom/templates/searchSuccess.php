<?php

slot('title', 'Vyhľadávanie voľných miestností');


slot('panel');
include_component('panel','panel');
end_slot();

slot('top');
include_component('timetable','top');
end_slot();

?>
<h1 class="posunuty">Vyhľadávanie voľných miestností</h1>

<?php echo form_tag('@freeRoom_search'); ?>
<?php echo $form; ?>
<button type="submit">Hľadať</button>
<?php echo '</form>'; ?>

<?php if ($roomIntervals): ?>

<table><thead><tr><th>Deň</th><th>Od</th><th>Do</th><th>Miestnosť</th><th>Kapacita miestnosti</th></tr></thead>
<?php foreach ($roomIntervals as $interval) {
    foreach ($interval['printableIntervals'] as $timeInterval) {
        echo '<tr>';
        echo '<td>';
        echo Candle::formatShortDay($timeInterval->getStartDay());
        echo '</td>';
        echo '<td>';
        echo Candle::formatTime($timeInterval->getStartTime());
        echo '</td>';
        echo '<td>';
        echo Candle::formatTime($timeInterval->getEndTime());
        echo '</td>';
        echo '<td>';
        echo $interval['Room']['name'];
        echo '</td>';
        echo '<td>';
        echo $interval['Room']['capacity'];
        echo '</td>';
        echo '</tr>';
    }
    
}
?>
</table>

<?php endif; ?>