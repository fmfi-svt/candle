<?php

slot('title', Candle::formatLongName($teacher));

slot('panel');
include_component('timetable','panel',array());
end_slot();

slot('top');
include_component('timetable','top');
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