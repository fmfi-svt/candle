<?php

slot('title', $studentGroup['name']);

slot('panel');
include_component('panel','panel',array());
end_slot();

slot('top');
include_component('timetable','top');
include_partial('studentGroup/menu', array('studentGroup'=>$studentGroup));
end_slot();

slot('header');
?>
<h1>Rozvrh krúžku <?php echo $studentGroup['name']; ?></h1>
<?php end_slot(); ?>

<div>
<?php include_partial('timetable/table',
        array(  'timetable'=>$timetable,
                'layout'=>$layout,
                'editable'=>false
        )); ?>
</div>
<?php include_partial('timetable/footer',
        array('url'=>array('sf_route'=>'studentGroup_show', 'sf_subject'=>$studentGroup)));
?>