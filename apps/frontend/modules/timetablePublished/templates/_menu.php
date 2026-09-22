<?php use_helper('Candle') ?>
<h2 class="pristupnost">Akcie zverejneného rozvrhu</h2>
<ul id="rozvrh_akcie" class="actions">
<?php
    echo candle_print_action();
    echo candle_action('files-o', 'Duplikovať do vlastného rozvrhu', '@timetable_duplicate_published?slug='.$timetable_slug);
    echo candle_action('sign-out', 'Exportovať', '@timetable_export_published?slug='.$timetable_slug);
?>
</ul>
