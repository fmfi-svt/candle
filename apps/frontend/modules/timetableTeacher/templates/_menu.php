<?php use_helper('Candle') ?>
<h2 class="pristupnost">Akcie rozvrhu vyučujúceho</h2>
<ul id="rozvrh_akcie" class="actions">
<?php
    echo candle_print_action();
    echo candle_action('files-o', 'Duplikovať do vlastného rozvrhu', array('sf_route'=>'timetable_teacher_duplicate', 'sf_subject' => $teacher));
    echo candle_action('sign-out', 'Exportovať', array('sf_route'=>'timetable_teacher_export', 'sf_subject' => $teacher));
?>
</ul>
