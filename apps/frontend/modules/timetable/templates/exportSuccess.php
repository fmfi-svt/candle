<?php

slot('title', $timetable->getName());

slot('top');
include_component('timetable','top', array('timetable'=>$timetable, 'timetable_id'=>$timetable_id));
include_component('timetable','editMenu', array('timetable'=>$timetable, 'timetable_id'=>$timetable_id));
end_slot();

?>
<h2>Exportovať rozvrh</h2>
<?php include_partial('timetable/exportOptions', array('url'=>array('sf_route'=>'timetable_show', 'id'=>$timetable_id)));