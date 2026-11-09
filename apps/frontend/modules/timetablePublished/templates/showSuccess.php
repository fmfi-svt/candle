<?php

slot('title', $timetable->getName());
slot('timetable_page', '1');

slot('top');
include_component('timetable','top');
end_slot();

slot('actions');
include_partial('timetablePublished/menu', array('timetable_slug'=>$timetable_slug));
end_slot();

slot('header');
?>
<h1><?php echo $timetable->getName(); ?></h1>
<?php end_slot(); ?>

<?php include_partial('timetable/table',
        array(  'timetable'=>$timetable,
                'layout'=>$layout,
                'editable'=>false
        )); ?>

<?php include_partial('timetable/footer',
        array('url'=>array('sf_route'=>'timetable_show_published', 'slug'=>$timetable_slug)));
?>
