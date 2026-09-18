<?php

slot('title', $timetable->getName());
slot('timetable_page', '1');

if ($addSlots) {
    slot('panel');
    include_component('panel','panel',array('timetable'=>$timetable, 'timetable_id'=>$timetable_id));
    end_slot();

    slot('top');
    include_component('timetable','top', array('timetable'=>$timetable, 'timetable_id'=>$timetable_id));
    end_slot();

    slot('actions');
    include_component('timetable', 'editMenu', array('timetable'=>$timetable, 'timetable_id'=>$timetable_id, 'published_slug'=>$published_slug));
    end_slot();

    slot('additionalRawJavascript');
    echo 'var candleTimetableEditor_changeToken = \'' . $changeToken . '\';';
    echo 'document.candleTimetableEditor_timetableId = ' . $timetable_id . ';';
    echo 'document.candleTimetableEditor_modified = ' . ($timetable->isModified() ? 'true' : 'false') . ';';
    end_slot();

    if($published_slug) {
        $publishedInternalUrl = array('sf_route'=>'timetable_show_published', 'slug'=>$published_slug);
        echo '<div id="timetable_full_url">';
        echo '<i class="fa fa-share-alt" aria-hidden="true"></i> Rozvrh zverejnený na: ';
        echo link_to(url_for($publishedInternalUrl, true),$publishedInternalUrl);
        echo '</div>';
    }
}
// Pozor: pri obnovení rozvrhu cez AJAX (onlyTimetable) musí byť div#timetable_editor
// prvým uzlom odpovede, timetable_editor.js ním nahrádza pôvodný element.
?>
<div id="timetable_editor">
<script type="text/javascript" >var timetableEditor_changeLessonsURL="<?php echo url_for('@timetable_change_lessons?id='.$timetable_id); ?>";</script>
<?php echo form_tag('@timetable_lesson_action?id='.$timetable_id, array('id'=>'timetable_editor_form')); ?><div>

<?php slot('header') ?>
<h1><?php echo $timetable->getName(); ?></h1>
<?php end_slot(); ?>

<div id="timetable_editor_command_bar" class="editor_bar">
    <div class="editor_bar__commands">
        <select name="selection_source" size="1" id="timetable_editor_selection_source" title="Ktoré hodiny v rozvrhu">
            <option value="selection" id="selection_source_selection">Označené</option>
            <option value="selection_inv" id="selection_source_selection_inv">Neoznačené</option>
            <option value="highlight" id="selection_source_highlight">Zvýraznené</option>
            <option value="highlight_inv" id="selection_source_highligh_inv">Nezvýraznené</option>
            <option value="all" id="selection_source_all">Všetky</option>
        </select>
        <span>hodiny v rozvrhu</span>
        <button name="selection_action" type="submit" value="highlight" id="timetable_editor_selection_action_highlight">Zvýrazňovať</button>
        <button name="selection_action" type="submit" value="unhighlight" id="timetable_editor_selection_action_unhighlight">Nezvýrazňovať</button>
        <button name="selection_action" type="submit" value="remove" id="timetable_editor_selection_action_remove" class="editor_bar__danger">Odstrániť</button>
    </div>
    <div id="timetable_editor_info" class="editor_bar__info">
        Súčet kreditov predmetov v rozvrhu: <?php echo $timetable->sumCredits(); ?>
    </div>
</div> <!-- timetable_editor_command_bar -->

<?php include_partial('timetable/table', array('timetable'=>$timetable, 'layout'=>$layout, 'editable'=>true)); ?>
</div>
<?php echo '</form>' ?>
</div>
<?php if ($addSlots):
    include_partial('timetable/footer',
            array('url'=>array('sf_route'=>'timetable_show', 'id'=>$timetable_id)));
endif; ?>
