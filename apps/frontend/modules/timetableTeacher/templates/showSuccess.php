<?php

slot('title', Candle::formatLongName($teacher));

slot('panel');
include_component('panel','panel',array());
end_slot();

slot('top');
include_component('timetable','top');
include_partial('timetableTeacher/menu', array('teacher'=>$teacher));
end_slot();

?>
<h1 class="posunuty"><?php echo Candle::formatLongName($teacher); ?></h1>

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