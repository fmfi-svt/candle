<?php

slot('title', $timetable->getName());

slot('top');
include_component('timetable','top');
include_partial('timetablePublished/menu', array('timetable_slug'=>$timetable_slug));
end_slot();

?>
<h1><?php echo $timetable->getName(); ?></h1>

<div>
<?php include_partial('timetable/table',
        array(  'timetable'=>$timetable,
                'layout'=>$layout,
                'editable'=>false
        )); ?>
</div>