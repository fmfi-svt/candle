<?php
/*
 * Karta jednej hodiny v mriežke rozvrhu.
 *
 * Premenné: $lesson, $highlighted (bool), $editable (bool),
 *           $stacked (bool, karta je jednou z viacerých prekrývajúcich sa hodín),
 *           $style (string, inline štýl s polohou karty)
 */
$shortCode = $lesson['Subject']['short_code'];
$subjectInfoLink = Candle::makeSubjectInfoLink($shortCode);
$subjectName = $lesson['Subject']['name'];
if ($subjectInfoLink) {
    $subjectName = link_to($subjectName, $subjectInfoLink, array('class'=>'subjectName'));
}
else {
    $subjectName = '<span class="subjectName">'.$subjectName.'</span>';
}

$teachers = Candle::formatShortNameList($lesson['Teacher']);
$lessonTimeInfo = Candle::formatShortDay($lesson['day']) . ' ' .
                  Candle::formatTime($lesson['start']) . '-' .
                  Candle::formatTime($lesson['end']);
$lessonTitle = $lesson['Room']['name'] . ', ' . $lessonTimeInfo . ', ' .
               $shortCode . ': ' . $teachers;

$classes = array('lecture_container__lecture', 'hodina', Candle::getLessonTypeHTMLClass($lesson['LessonType']));
if ($highlighted) {
    $classes[] = 'highlighted';
}
if (!empty($stacked)) {
    $classes[] = 'lecture_container__lecture--stacked';
}
?>
<div class="<?php echo implode(' ', $classes) ?>" title="<?php echo $lessonTitle ?>"<?php if (!empty($style)) echo ' style="'.$style.'"' ?>>
    <p class="lecture_container__lecture_room"><?php
        echo link_to($lesson['Room']['name'], array('sf_route'=>'room_show', 'sf_subject'=>$lesson['Room']));
    ?></p>
    <p class="lecture_container__lecture_name"><?php echo $subjectName ?></p>
    <?php if ($lesson['note'] !== null && $lesson['note'] !== ''): ?>
    <p class="lecture_container__lecture_note"><?php echo $lesson['note'] ?></p>
    <?php endif; ?>
    <abbr class="lecture_container__lecture_type" title="<?php echo $lesson['LessonType']['name'] ?>"><?php echo Candle::upper($lesson['LessonType']['code']) ?></abbr>
    <?php if ($highlighted): ?>
    <span class="pristupnost">[zvýraznená hodina]</span>
    <?php endif; ?>
    <?php if ($editable): ?>
        <?php $cb_id = 'timetable_lesson_selection_cb_'.$lesson['id'] ?>
        <input type="checkbox" class="lecture_container__lecture_select" id="<?php echo $cb_id; ?>" name="lesson_selection[]" value="<?php echo $lesson['id']?>"
        title="Označiť túto hodinu" /><label class="pristupnost" for="<?php echo $cb_id; ?>">Označiť hodinu: <?php echo Candle::formatShortDay($lesson['day']) . ' ' . Candle::formatTime($lesson['start']) . ' ' . $lesson['Subject']['name'] ?></label>
    <?php endif; ?>
</div>
