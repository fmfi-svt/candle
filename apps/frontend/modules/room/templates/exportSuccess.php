<?php

slot('title', $room['name']);

slot('top');
include_component('timetable','top');
include_partial('room/menu', array('room'=>$room));
end_slot();

?>
<h1>Exportovať rozvrh miestnosti: <?php echo $room['name'] ?></h1>
<?php include_partial('timetable/exportOptions', array('url'=>array('sf_route'=>'room_show', 'sf_subject'=>$room)));