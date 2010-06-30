<?php

slot('title', Candle::formatLongName($teacher));

slot('top');
include_component('timetable','top', 
        array('timetable'=>$timetable,
              'timetable_url'=>url_for('@timetable_teacher_show?id='.$teacher_id)));
include_partial('timetableTeacher/menu', array('teacher_id'=>$teacher_id));
end_slot();

?>
<div>
<?php include_partial('timetable/table',
        array(  'timetable'=>$timetable,
                'layout'=>$layout,
                'editable'=>false
        )); ?>
</div>