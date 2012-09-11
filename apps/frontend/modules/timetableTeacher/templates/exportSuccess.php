<?php

slot('title', Candle::formatLongName($teacher));

slot('top');
include_component('timetable','top');
include_partial('timetableTeacher/menu', array('teacher'=>$teacher));
end_slot();

?>
<h1>Exportovať rozvrh učiteľa: <?php echo Candle::formatLongName($teacher); ?></h1>
<?php include_partial('timetable/exportOptions', array('url'=>array('sf_route'=>'timetable_teacher_show', 'sf_subject'=>$teacher)));