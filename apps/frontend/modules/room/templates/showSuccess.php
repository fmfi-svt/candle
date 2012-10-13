<?php

slot('title', $room['name']);

slot('panel');
include_component('panel','panel',array());
end_slot();

slot('top');
include_component('timetable','top');
include_partial('room/menu', array('room'=>$room));
end_slot();

slot('header');
?>
<h1>Rozvrh miestnosti <?php echo $room['name']; ?></h1>
<?php end_slot(); ?>

<p>
    Typ miestnosti: <?php echo $room['RoomType']['name']; ?><br />
    Kapacita: <?php echo $room['capacity']; ?><br />
</p>

<div>
<?php include_partial('timetable/table',
        array(  'timetable'=>$timetable,
                'layout'=>$layout,
                'editable'=>false
        )); ?>
</div>
<div>
<?php include_partial('timetable/footer',
        array('url'=>array('sf_route'=>'room_show', 'sf_subject'=>$room)));
?>
</div>