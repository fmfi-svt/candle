<?php

slot('title', Candle::formatLongName($teacher));

slot('panel');
include_component('panel','panel',array());
end_slot();

slot('top');
include_component('timetable','top');
include_partial('timetableTeacher/menu', array('teacher'=>$teacher));
end_slot();

slot('additionalHeadTags');?>
<link rel="canonical" href="<?php echo url_for(array('sf_route' => 'timetable_teacher_show', 'sf_subject' => $teacher)); ?>" />
<?php
end_slot();

slot('header');
?>
<h1><?php echo Candle::formatLongName($teacher); ?></h1>
<?php end_slot(); ?>

<div>
<?php include_partial('timetable/table',
        array(  'timetable'=>$timetable,
                'layout'=>$layout,
                'editable'=>false
        )); ?>
</div>
<?php include_partial('timetable/footer',
        array('url'=>array('sf_route'=>'timetable_teacher_show', 'sf_subject'=>$teacher)));
?>