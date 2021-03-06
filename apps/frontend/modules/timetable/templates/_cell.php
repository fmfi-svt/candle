<?php
$shortCode = $lesson['Subject']['short_code'];
$subjectInfoLink = Candle::makeSubjectInfoLink($shortCode);
$subjectName = $lesson['Subject']['name'];
if ($subjectInfoLink) {
    $subjectName = link_to($subjectName, $subjectInfoLink, array('class'=>'subjectName'));
}
else {
    $subjectName = '<span class="subjectName">'.$subjectName.'</span>';
}
?>
<div class="wrap"><!--
    --><div class="predmet_wrap"><div class="predmet"><?php echo $subjectName; ?></div></div><!--
    --><div class="hodina_info"><div class="miestnost"><?php
                echo link_to($lesson['Room']['name'], array('sf_route'=>'room_show', 'sf_subject'=>$lesson['Room'])); 
    ?></div><!--
    --><div class="typ"><abbr title="<?php echo $lesson['LessonType']['name']; ?>"><?php echo Candle::upper($lesson['LessonType']['code']); ?></abbr></div></div>
    <?php if ($highlighted): ?>
    <div class="pristupnost">[zvýraznená hodina]</div>
    <?php endif; ?>

    <?php if ($lesson['note'] !== null): ?>
        <div class="poznamka"><?php echo $lesson['note']; ?></div>
    <?php endif; ?>
    
    <?php if ($editable): ?>
        <?php $cb_id = 'timetable_lesson_selection_cb_'.$lesson['id'] ?>
        <input type="checkbox" id="<?php echo $cb_id; ?>" name="lesson_selection[]" value="<?php echo $lesson['id']?>"
        title="Označiť túto hodinu" /><label class="pristupnost" for="<?php echo $cb_id; ?>">Zobraziť v rozvrhu: <?php echo Candle::formatShortDay($lesson['day']) . ' ' . Candle::formatTime($lesson['start']) . ' ' . $lesson['Subject']['name'] ?></label>
    <?php endif; ?>
    
</div>
