<?php

slot('title', $timetable->getName());

slot('top');
include_component('timetable','top', array('timetable'=>$timetable, 'timetable_id'=>$timetable_id));
include_component('timetable','editMenu', array('timetable'=>$timetable, 'timetable_id'=>$timetable_id));
end_slot();

?>
<h1>Importovať hodiny do rozvrhu: <?php echo $timetable->getName(); ?></h1>

<p>Do rozvrhu je možné vložiť predmety podľa zoznamu ECTS kódov predmetu (napr. 1-INF-165),
jednotlivé kódy oddeľujte buď čiarkou, alebo znakom pre nový riadok</p>

<?php echo form_tag('@timetable_importDo?id='.$timetable_id); ?><div>
    <textarea name="text" cols="50" rows="10"></textarea>
    <div><button type="submit">Importovať</button></div>
</div><?php echo '</form>';