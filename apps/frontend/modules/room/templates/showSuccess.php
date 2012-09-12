<?php

slot('title', $room['name']);

slot('panel');
include_component('panel','panel',array());
end_slot();

slot('top');
include_component('timetable','top');
include_partial('room/menu', array('room'=>$room));
end_slot();

?>
<h1 class="posunuty"><?php echo $room['name']; ?></h1>

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