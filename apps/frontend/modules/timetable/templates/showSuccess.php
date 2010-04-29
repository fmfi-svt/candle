<?php

slot('title', $timetable->getName());

slot('panel');
include_component('timetable','panel',array('timetable'=>$timetable, 'timetable_id'=>$timetable_id));
end_slot();

slot('top');
include_component('timetable','top', array('timetable'=>$timetable, 'timetable_id'=>$timetable_id));
include_component('timetable', 'editMenu', array('timetable'=>$timetable, 'timetable_id'=>$timetable_id));
end_slot();

?>
<?php echo form_tag('@timetable_change_lessons?id='.$timetable_id, array('id'=>'timetable_editor_form')); ?><div>
<?php include_partial('timetable/table', array('timetable'=>$timetable, 'layout'=>$layout, 'editable'=>true)); ?>
</div>
<div>
    <button type="submit" class="jshide">Upraviť rozvrh</button>
</div>
<?php echo '</form>' ?>
