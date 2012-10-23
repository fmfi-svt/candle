<?php

slot('title', $room['name']);

slot('top');
include_component('timetable','top');
include_partial('room/menu', array('room'=>$room));
end_slot();

slot('header');
?>
<h1>Exportovať rozvrh miestnosti: <?php echo $room['name'] ?></h1>
<?php end_slot(); ?>
<?php include_partial('timetable/exportOptions', array('url'=>array('sf_route'=>'room_show', 'sf_subject'=>$room)));