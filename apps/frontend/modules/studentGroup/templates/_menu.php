<?php use_helper('Candle') ?>
<h2 class="pristupnost">Akcie rozvrhu krúžku</h2>
<ul id="rozvrh_akcie" class="actions">
<?php
    echo candle_print_action();
    echo candle_action('files-o', 'Duplikovať do vlastného rozvrhu', array('sf_route'=>'studentGroup_timetable_duplicate', 'sf_subject'=>$studentGroup));
    echo candle_action('sign-out', 'Exportovať', array('sf_route'=>'studentGroup_timetable_export', 'sf_subject'=>$studentGroup));
?>
</ul>
