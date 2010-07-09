<?php

slot('title', $timetable->getName());

if (!$sf_request->isXmlHttpRequest()) {
    slot('panel');
    include_component('timetable','panel',array('timetable'=>$timetable, 'timetable_id'=>$timetable_id));
    end_slot();

    slot('top');
    include_component('timetable','top', array('timetable'=>$timetable, 'timetable_id'=>$timetable_id));
    include_component('timetable', 'editMenu', array('timetable'=>$timetable, 'timetable_id'=>$timetable_id));
    end_slot();
}

?>
<div id="timetable_editor">
<?php echo form_tag('@timetable_change_lessons?id='.$timetable_id, array('id'=>'timetable_editor_form')); ?><div>

<div id="timetable_editor_command_bar">

Všetky
<select name="selection_source" size="1">
    <option value="selection" id="selection_source_selection">vybrané</option>
    <option value="highlight" id="selection_source_highlight">zvýraznené</option>
</select>

hodiny v rozvrhu

<button name="selection_action" type="submit">Odstrániť</button>
<button name="selection_action" type="highlight">Zvýrazňovať</button>
<button name="selection_action" type="unhighlight">Nezvýrazňovať</button>

</div> <!-- timetable_editor_command_bar -->

<?php include_partial('timetable/table', array('timetable'=>$timetable, 'layout'=>$layout, 'editable'=>true)); ?>
</div>
<div>
    <button type="submit" class="jshide">Upraviť rozvrh</button>
</div>
<?php echo '</form>' ?>
</div>
