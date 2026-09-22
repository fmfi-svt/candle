<?php use_helper('Candle') ?>
<h2 class="pristupnost">Akcie rozvrhu miestnosti</h2>
<ul id="rozvrh_akcie" class="actions">
<?php
    echo candle_print_action();
    echo candle_action('files-o', 'Duplikovať do vlastného rozvrhu', array('sf_route'=>'room_timetable_duplicate', 'sf_subject'=>$room));
    echo candle_action('sign-out', 'Exportovať', array('sf_route'=>'room_timetable_export', 'sf_subject'=>$room));
?>
</ul>
