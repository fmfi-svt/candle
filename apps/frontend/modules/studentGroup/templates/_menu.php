<h2 class="pristupnost">Akcie rozvrhu krúžku</h2>
<ul id="rozvrh_akcie">
    <li id="menuPrintBefore"><?php echo link_to('Duplikovať', array('sf_route'=>'studentGroup_timetable_duplicate', 'sf_subject'=>$studentGroup)); ?></li><!--
    --><li><?php echo link_to('Exportovať', array('sf_route'=>'studentGroup_timetable_export', 'sf_subject'=>$studentGroup)); ?></li>
</ul>