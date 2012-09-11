<h2 class="pristupnost">Akcie rozvrhu vyučujúceho</h2>
<ul id="rozvrh_akcie">
    <li id="menuPrintBefore"><?php echo link_to('Duplikovať', array('sf_route'=>'timetable_teacher_duplicate', 'sf_subject' => $teacher)); ?></li><!--
    --><li><?php echo link_to('Exportovať', array('sf_route'=>'timetable_teacher_export', 'sf_subject' => $teacher)); ?></li>
</ul>