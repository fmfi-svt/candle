<?php

slot('top');
include_component('timetable','top',array('timetable'=>$timetable, 'timetable_id'=>$timetable_id));
end_slot();

slot('header');
?>
<h1>Premenovať rozvrh: <?php echo $timetable->getName(); ?></h1>
<?php end_slot(); ?>
<?php echo form_tag(array('sf_route'=>'timetable_rename_do', 'id'=>$timetable_id)); ?>
<?php echo $form; ?>
<button type="submit">Premenovať rozvrh</button>
<?php echo '</form>'; ?>
