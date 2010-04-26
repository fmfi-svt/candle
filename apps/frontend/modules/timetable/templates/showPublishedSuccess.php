<?php

slot('title', $timetable->getName());

slot('top');
include_component('timetable','top', array('timetable'=>$timetable));
end_slot();

?>
<div>
<?php include_partial('timetable/table',
        array(  'timetable'=>$timetable,
                'layout'=>$layout,
                'editable'=>false
        )); ?>
</div>