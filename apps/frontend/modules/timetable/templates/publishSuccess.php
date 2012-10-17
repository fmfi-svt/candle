<?php

slot('top');
include_component('timetable','top',array('timetable'=>$timetable, 'timetable_id'=>$timetable_id));
end_slot();

slot('header');
?>
<h1>Zverejniť rozvrh: <?php echo $timetable->getName(); ?></h1>
<?php end_slot(); ?>
<?php echo form_tag(array('sf_route'=>'timetable_publish_do', 'id'=>$timetable_id)); ?>
<?php echo $form['_csrf_token']; ?>
<?php echo $form['slug']->renderError(); ?><?php echo $form['slug']->renderLabel(); ?><?php echo $form['slug']; ?>


<div>
<button type="submit">Zverejniť rozvrh</button>
</div>
<?php echo '</form>'; ?>

<hr />
<div>
Náhľad rozvrhu:
<h2><?php echo $timetable->getName(); ?></h2>
<?php include_partial('timetable/table',
        array(  'timetable'=>$timetable,
                'layout'=>$layout,
                'editable'=>false
        )); ?>
</div>

