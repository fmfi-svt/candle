<?php

slot('title', $studentGroup['name']);

slot('top');
include_component('timetable','top');
include_partial('studentGroup/menu', array('studentGroup_id'=>$studentGroup_id));
end_slot();

?>
<h1>Exportovať rozvrh krúžku: <?php echo $studentGroup['name'] ?></h1>
<?php include_partial('timetable/exportOptions', array('url'=>array('sf_route'=>'studentGroup_show', 'id'=>$studentGroup_id)));