<?php

slot('title', $timetable->getName());

slot('top');
include_component('timetable','top');
include_partial('timetablePublished/menu', array('timetable_slug'=>$timetable_slug));
end_slot();

slot('header');
?>
<h1>Exportovať rozvrh: <?php echo $timetable->getName(); ?></h1>
<?php end_slot(); ?>
<?php include_partial('timetable/exportOptions', array('url'=>array('sf_route'=>'timetable_show_published', 'slug'=>$timetable_slug)));