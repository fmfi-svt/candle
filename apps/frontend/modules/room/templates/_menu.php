<h2 class="pristupnost">Akcie rozvrhu vyučujúceho</h2>
<ul id="rozvrh_akcie">
    <li  id="menuPrintBefore"><?php echo link_to('Duplikovať', array('sf_route'=>'room_timetable_duplicate', 'sf_subject'=>$room)); ?></li><!--
    --><li><?php echo link_to('Exportovať', array('sf_route'=>'room_timetable_export', 'sf_subject'=>$room)); ?></li>
</ul>