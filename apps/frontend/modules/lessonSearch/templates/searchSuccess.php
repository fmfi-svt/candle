<?php

$rawThisUrl = $sf_data->getRaw('thisUrl');

slot('title', 'Vyhľadávanie hodín podľa času');


slot('panel');
include_component('panel','panel');
end_slot();

slot('top');
include_component('timetable','top');
end_slot();

?>
<h1 class="posunuty">Vyhľadávanie hodín podľa času</h1>

<?php echo form_tag('@lessonSearch_search'); ?>
<?php echo $form->renderGlobalErrors(); ?>

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
<button type="submit">Hľadať</button>
<?php echo '</form>'; ?>

<?php if ($lessonIntervals): ?>
<hr />
<h2>Výsledky vyhľadávania</h2>
<?php

    if ($queryIntervals):

        echo '<p>';
        echo sprintf('Dotazu na hodiny v (');

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

        if (count($queryIntervals) == 0) {
            echo '∅';
        }

        echo ')</p>';

    endif;

    if (count($lessonIntervals) == 0):
        echo '<p>Výsledkom vyhľadávania nevyhovuje žiaden záznam</p>';
    else:

?>
<table class="vysledky_podrobneho_hladania">
    <thead>
        <tr><th colspan="3">Čas</th><th colspan="3">Hodina</th></tr>
        <tr><th>Deň</th><th>Od</th><th>Do</th><th>Predmet</th><th>Učiteľ</th><th>Miestnosť</th></tr>
    </thead>
    <tbody>
<?php foreach ($lessonIntervals as $lesson) {
    echo '<tr>';
    echo '<td>';
    echo Candle::formatShortDay($lesson['day']);
    echo '</td>';
    echo '<td>';
    echo Candle::formatTime($lesson['start']);
    echo '</td>';
    echo '<td>';
    echo Candle::formatTime($lesson['end']);
    echo '</td>';
    echo '<td>';
    echo link_to($lesson['Subject']['name'], array_merge($rawThisUrl, array('showLessons'=>$lesson['Subject']['name'])));
    echo '</td>';
    echo '<td>';
    $first = true;
    foreach ($lesson['Teacher'] as $teacher) {
        if (!$first) echo ', ';
        $first = false;
        echo link_to(Candle::formatLongName($teacher), array('sf_route'=>'timetable_teacher_show', 'id'=>$teacher['id']));
    }
    echo '</td>';
    echo '<td>';
    echo link_to($lesson['Room']['name'], array('sf_route'=>'room_show', 'id'=>$lesson['Room']['id']));
    echo '</td>';
    echo '</tr>';
    
}
?>
    </tbody>
</table>

<?php
echo link_to('CSV', array_merge($rawThisUrl, array('sf_format'=>'csv')));

    endif;
    
endif; ?>