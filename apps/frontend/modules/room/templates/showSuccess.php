<?php

slot('title', $room['name']);
slot('timetable_page', '1');

slot('panel');
include_component('panel','panel',array());
end_slot();

slot('top');
include_component('timetable','top');
end_slot();

slot('actions');
include_partial('room/menu', array('room'=>$room));
end_slot();

slot('header');
?>
<h1>Rozvrh miestnosti <?php echo $room['name']; ?></h1>
<span class="header_subtitle"><?php echo $room['RoomType']['name']; ?>, kapacita <?php echo $room['capacity']; ?></span>
<?php end_slot(); ?>

<?php include_partial('timetable/table',
        array(  'timetable'=>$timetable,
                'layout'=>$layout,
                'editable'=>false
        )); ?>
<?php include_partial('timetable/footer',
        array('url'=>array('sf_route'=>'room_show', 'sf_subject'=>$room)));
?>
