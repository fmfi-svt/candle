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
<script type="text/javascript" >var timetableEditor_changeLessonsURL="<?php echo url_for('@timetable_change_lessons?id='.$timetable_id); ?>";</script>
<?php echo form_tag('@timetable_lesson_action?id='.$timetable_id, array('id'=>'timetable_editor_form')); ?><div>

<div id="timetable_editor_command_bar">

Všetky
<select name="selection_source" size="1" id="timetable_editor_selection_source">
    <option value="selection" id="selection_source_selection">označené</option>
    <option value="highlight" id="selection_source_highlight">zvýraznené</option>
</select>

hodiny v rozvrhu

<button name="selection_action" type="submit" value="highlight" id="timetable_editor_selection_action_highlight">Zvýrazňovať</button>
<button name="selection_action" type="submit" value="unhighlight" id="timetable_editor_selection_action_unhighlight">Nezvýrazňovať</button>
<button name="selection_action" type="submit" value="remove" id="timetable_editor_selection_action_remove">Odstrániť</button>

</div> <!-- timetable_editor_command_bar -->

<?php include_partial('timetable/table', array('timetable'=>$timetable, 'layout'=>$layout, 'editable'=>true)); ?>
</div>
<?php echo '</form>' ?>
</div>
