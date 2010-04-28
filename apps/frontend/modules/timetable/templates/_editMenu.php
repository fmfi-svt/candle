<h2 class="pristupnost">Akcie aktívneho rozvrhu</h2>
<ul id="rozvrh_akcie">
    <li><?php
        if($published_slug) {
            echo link_to($published_slug, '@timetable_show_published?slug='.$published_slug);
            echo '</li><li>';
            echo link_to('Nepublikovať', '@timetable_unpublish?id='.$timetable_id);
        }
        else {
            echo link_to('Publikovať', '@timetable_publish?id='.$timetable_id);
        }
        ?></li><!--
    --><li><?php echo link_to('Uložiť', '@timetable_save?id='.$timetable_id); ?></li><!--
    --><li><?php echo link_to('Duplikovať', '@timetable_duplicate?id='.$timetable_id); ?></li><!--
    --><li><?php echo link_to('Importovať', '@timetable_import?id='.$timetable_id); ?></li><!--
    --><li><?php echo link_to('Exportovať', '@timetable_export?id='.$timetable_id); ?></li><!--
    --><li><?php echo link_to('Zmazať', '@timetable_delete?id='.$timetable_id); ?></li>
</ul>