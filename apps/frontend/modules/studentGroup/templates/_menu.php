<h2 class="pristupnost">Akcie rozvrhu krúžku</h2>
<ul id="rozvrh_akcie">
    <li><?php echo link_to('Duplikovať', array('sf_route'=>'studentGroup_timetable_duplicate', 'id'=>$studentGroup_id)); ?></li><!--
    --><li><?php echo link_to('Exportovať', array('sf_route'=>'studentGroup_timetable_export', 'id'=>$studentGroup_id)); ?></li>
</ul>