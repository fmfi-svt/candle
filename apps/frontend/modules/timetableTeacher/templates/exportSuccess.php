<?php

slot('title', Candle::formatLongName($teacher));

slot('top');
include_component('timetable','top');
include_partial('timetableTeacher/menu', array('teacher'=>$teacher));
end_slot();

slot('header');
?>
<h1>Exportovať rozvrh učiteľa: <?php echo Candle::formatLongName($teacher); ?></h1>
<?php end_slot(); ?>
<?php include_partial('timetable/exportOptions', array('url'=>array('sf_route'=>'timetable_teacher_show', 'sf_subject'=>$teacher)));