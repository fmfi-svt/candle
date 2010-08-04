<?php

slot('title', $room['name']);

slot('panel');
include_component('panel','panel',array());
end_slot();

slot('top');
include_component('timetable','top');
include_partial('room/menu', array('room_id'=>$room_id));
end_slot();

?>
<h1 class="posunuty"><?php echo $room['name']; ?></h1>

<div>
<?php include_partial('timetable/table',
        array(  'timetable'=>$timetable,
                'layout'=>$layout,
                'editable'=>false
        )); ?>
</div>