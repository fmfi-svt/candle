<?php

slot('panel');
include_component('timetable','panel');
end_slot();

slot('top');
include_component('timetable','top',array('newSelected'=>true));
end_slot();

?>
<h1>Vytvoriť nový rozvrh</h1>
<?php echo form_tag('timetable/create'); ?>
<?php echo $form; ?>
<button type="submit">Vytvoriť rozvrh</button>
</form>
