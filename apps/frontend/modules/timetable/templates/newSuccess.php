<?php

slot('top');
include_component('timetable','top',array('newSelected'=>true));
end_slot();

slot('header');
?>
<h1>Vytvoriť nový rozvrh</h1>
<?php end_slot(); ?>
<?php echo form_tag('timetable/create'); ?>
<?php echo $form; ?>
<button type="submit">Vytvoriť rozvrh</button>
</form>
