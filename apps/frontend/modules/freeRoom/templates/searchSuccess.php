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
<?php echo $form->renderGlobalErrors(); ?>

<div class="form_box">
<?php echo $form['requiredAmount']->renderLabel(); ?>
    <div class="form_box_content">
        <div>
            <?php echo $form['requiredAmount']; ?>
        </div>
        <div>
            <?php echo $form['requiredAmount']->renderError(); ?>
        </div>
        <div>
            <?php echo $form['requiredAmount']->renderHelp(); ?>
        </div>
    </div>
</div>
<div class="form_box">
<?php echo $form['searchIntervals']->renderLabel(); ?>
    <div class="form_box_content">
        <div>
            <?php echo $form['searchIntervals']->render(array('class'=>'fullWidth')); ?>
        </div>
        <div>
            <?php echo $form['searchIntervals']->renderError(); ?>
        </div>
        <div>
            <?php echo $form['searchIntervals']->renderHelp(); ?>
        </div>
    </div>
</div>
<div class="form_box">
<?php echo $form['minimalRoomCapacity']->renderLabel(); ?>
    <div class="form_box_content">
        <div>
            <?php echo $form['minimalRoomCapacity']; ?>
        </div>
        <div>
            <?php echo $form['minimalRoomCapacity']->renderError(); ?>
        </div>
        <div>
            <?php echo $form['minimalRoomCapacity']->renderHelp(); ?>
        </div>
    </div>
</div>
<button type="submit">Hľadať</button>
<?php echo '</form>'; ?>

<?php if ($roomIntervals): ?>
<hr />
<h2>Výsledky vyhľadávania</h2>
<?php

    if ($queryIntervals):

        echo '<p>';
        echo sprintf('Dotazu na prienik pohyblivého aspoň %d minútového intervalu so zjednotením intervalov ', $minLength);

        $first = true;
        foreach ($queryIntervals as $queryInterval) {
            if (!$first) {
                echo " ∪ ";
            }
            $first = false;

            echo '&lt;';
            echo $queryInterval;
            echo '&gt;';
        }

        echo '</p>';

    endif;

    if (count($roomIntervals) == 0):
        echo '<p>Výsledkom vyhľadávania nevyhovuje žiaden záznam</p>';
    else:

?>

<table class="vysledky_podrobneho_hladania">
    <thead>
        <tr><th colspan="4">Voľno</th><th colspan="3">Miestnosť</th></tr>
        <tr><th>Deň</th><th>Od</th><th>Do</th><th>Dĺžka</th><th>Názov</th><th>Kapacita</th><th>Typ</th></tr>
    </thead>
    <tbody>
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
        if (!$timeInterval->isEmpty() && $timeInterval->getEndTime() == 0) {
            echo '24:00';
        }
        else {
            echo Candle::formatTime($timeInterval->getEndTime());
        }
        echo '</td>';
        echo '<td>';
        echo Candle::formatTimeAmount($timeInterval->getLength());
        echo '</td>';
        echo '<td>';
        echo $interval['Room']['name'];
        echo '</td>';
        echo '<td>';
        echo $interval['Room']['capacity'];
        echo '</td>';
        echo '<td>';
        echo $interval['Room']['RoomType']['name'];
        echo '</td>';
        echo '</tr>';
    }
    
}
?>
    </tbody>
</table>

<?php

    endif;
    
endif; ?>