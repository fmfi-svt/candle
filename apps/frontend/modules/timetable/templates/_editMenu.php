<h2 class="pristupnost">Akcie aktívneho rozvrhu</h2>
<ul id="rozvrh_akcie">
    <li><?php echo link_to('Uložiť', '@timetable_save?id='.$timetable_id); ?></li><!--
    --><li><?php echo link_to('Duplikovať', '@timetable_duplicate?id='.$timetable_id); ?></li><!--
    --><li><a href="#">Importovať</a></li><!--
    --><li><?php echo link_to('Exportovať', '@timetable_export?id='.$timetable_id); ?></li><!--
    --><li><a href="#">Publikovať</a></li><!--
    --><li><?php echo link_to('Zmazať', '@timetable_delete?id='.$timetable_id); ?></li>
</ul>