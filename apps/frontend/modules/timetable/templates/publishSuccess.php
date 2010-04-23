<?php

slot('top');
include_component('timetable','top',array('timetable'=>$timetable, 'timetable_id'=>$timetable_id));
end_slot();

?>
<h1>Publikovať rozvrh</h1>
<?php echo form_tag('@timetable_publish_do?id='.$timetable_id); ?>
<?php echo $form; ?>
<button type="submit">Publikovať rozvrh</button>
</form>

