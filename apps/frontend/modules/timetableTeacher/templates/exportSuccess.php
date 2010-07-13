<?php

slot('title', Candle::formatLongName($teacher['name']));

slot('top');
include_component('timetable','top');
include_partial('timetableTeacher/menu', array('teacher_id'=>$teacher_id));
end_slot();

?>
<h2>Exportovať rozvrh</h2>
<?php include_partial('timetable/exportOptions', array('url'=>array('sf_route'=>'timetable_teacher_show', 'id'=>$teacher_id)));