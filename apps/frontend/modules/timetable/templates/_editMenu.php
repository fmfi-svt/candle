<?php use_helper('Candle') ?>
<h2 class="pristupnost">Akcie aktívneho rozvrhu</h2>
<ul id="rozvrh_akcie" class="actions">
<?php
    if ($published_slug) {
        echo candle_action('share-alt', 'Rozvrh je zdieľaný ako "'.$published_slug.'", kliknutím zdieľanie zrušíte',
                '@timetable_unpublish?id='.$timetable_id,
                array('rel' => 'nofollow', 'class' => 'actions__link actions__link--active'));
    }
    else {
        echo candle_action('share-alt', 'Zdieľať', '@timetable_publish?id='.$timetable_id, array('rel' => 'nofollow'));
    }
    echo candle_action('floppy-o', 'Uložiť', '@timetable_save?id='.$timetable_id, array('rel' => 'nofollow', 'id' => 'menuSave'));
    echo candle_action('pencil', 'Premenovať', '@timetable_rename?id='.$timetable_id, array('rel' => 'nofollow'));
    echo candle_print_action();
    echo candle_action('files-o', 'Duplikovať', '@timetable_duplicate?id='.$timetable_id, array('rel' => 'nofollow'));
    echo candle_action('sign-in', 'Importovať', '@timetable_import?id='.$timetable_id, array('rel' => 'nofollow'));
    echo candle_action('sign-out', 'Exportovať', '@timetable_export?id='.$timetable_id, array('rel' => 'nofollow'));
    echo candle_action('trash', 'Zmazať', '@timetable_delete?id='.$timetable_id, array('rel' => 'nofollow'));
?>
</ul>
