<?php

slot('top');
include_component('timetable','top');
end_slot();

?>
<h1>Vytvoriť nový rozvrh</h1>
<?php echo form_tag('timetable/create'); ?>
<?php echo $form; ?>
<button type="submit">Vytvoriť rozvrh</button>
</form>
