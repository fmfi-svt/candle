<?php

slot('title', 'Vyhľadávanie voľných miestností');


slot('panel');
include_component('panel','panel');
end_slot();

slot('top');
include_component('timetable','top');
end_slot();

?>
<h1>Vyhľadávanie voľných miestností</h1>

<?php echo form_tag('@freeRoom_search'); ?>
<?php echo $form; ?>
<button type="submit">Hľadať</button>
<?php echo '</form>'; ?>

<?php if ($roomIntervals): ?>

<table><thead><tr><th>Čas</th><th>Miestnosť</th></tr></thead>
<?php foreach ($roomIntervals as $interval) {
    echo '<tr>';
    echo '<td>';
    $first = true;
    foreach ($interval['printableIntervals'] as $timeInterval) {
        if (!$first) {
            echo ', ';
        }
        $first = false;
        echo $timeInterval;
    }
    echo ' | ';
    echo $interval['day'].' '.$interval['start'].' '.$interval['end'];
    echo '</td>';
    echo '<td>';
    echo $interval['Room']['name'];
    echo '</td>';
    echo '</tr>';
}
?>
</table>

<?php endif; ?>