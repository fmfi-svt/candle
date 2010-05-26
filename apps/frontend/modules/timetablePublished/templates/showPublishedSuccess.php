<?php

slot('title', $timetable->getName());

slot('top');
include_component('timetable','top', 
        array('timetable'=>$timetable,
              'timetable_url'=>url_for('@timetable_show_published?slug='.$timetable_slug)));
include_partial('timetablePublished/publishedMenu', array('timetable_slug'=>$timetable_slug));
end_slot();

?>
<div>
<?php include_partial('timetable/table',
        array(  'timetable'=>$timetable,
                'layout'=>$layout,
                'editable'=>false
        )); ?>
</div>