<?php

slot('title', $studentGroup['name']);

slot('panel');
include_component('panel','panel',array());
end_slot();

slot('top');
include_component('timetable','top');
include_partial('studentGroup/menu', array('studentGroup_id'=>$studentGroup_id));
end_slot();

?>
<h1 class="posunuty"><?php echo $studentGroup['name']; ?></h1>

<div>
<?php include_partial('timetable/table',
        array(  'timetable'=>$timetable,
                'layout'=>$layout,
                'editable'=>false
        )); ?>
</div>