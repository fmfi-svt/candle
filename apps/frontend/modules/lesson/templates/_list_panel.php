<?php echo form_tag('@timetable_change_lessons?id='.$timetable_id); ?>
<div>

<ul class="vysledky_hladania">

<?php foreach ($subjects as $subject):

$lessons = $subject->getLessons();

$allSelected = true;
foreach ($lessons as $lesson) {
    if (!$timetable->hasLesson($lesson->getId())) {
        $allSelected = false;
        break;
    }
}

?>

<li class="predmet">
    <div class="predmet_header">
        <div class="predmet_nazov"><?php echo $subject->getName() ?></div>
        <div class="predmet_kod"><?php echo $subject->getShortCode() ?></div>
        <?php if ($allSelected): ?>
        <input type="hidden" name="subjectBefore[]" value="<?php echo $subject->getId()?>" />
        <?php endif; ?>
        <?php $cb_id = "panel_subject_cb_".$subject->getId(); ?>
        <input id="<?php echo $cb_id; ?>" class="predmet_check" type="checkbox" name="subject[]" value="<?php echo $subject->getId()?>" <?php
                if ($allSelected) echo 'checked="checked"' ?>/>
        <label class="pristupnost" for="<?php echo $cb_id; ?>">Zobraziť v rozvrhu celý predmet: <?php echo $subject; ?></label>
    </div>

    <table>
      <thead>
        <tr>
          <th>Typ</th><th>Deň</th><th>Čas</th><th>Kde</th><th>Kto</th><th>Zobraziť</th>
        </tr>
      </thead>
        
      <tbody>
        <?php foreach ($lessons as $lesson): ?>
        <tr>
          <td><abbr class="lesson-type lesson-type-<?php echo $lesson->getLessonType()->getCode() ?>" title="<?php echo $lesson->getLessonType()?>"><span class="lesson-type-in"><?php echo $lesson->getLessonType()->getCode() ?></span><span class="lesson-type-image"></span></abbr></td>
          <td><?php echo Candle::formatShortDay($lesson->getDay()) ?></td>
          <td><?php echo Candle::formatTime($lesson->getStart()) ?></td>
          <td><?php echo $lesson->getRoom() ?></td>
          <td><?php foreach ($lesson->getTeacher() as $i => $teacher): 
                        if ($i>0) echo ', ';
                        echo Candle::nbsp($teacher->getShortName());
                    endforeach; ?>
          </td>
          <td class="last">
            <?php if ($timetable->hasLesson($lesson->getId())): ?>
                <input type="hidden" name="lessonBefore[]" value="<?php echo $lesson->getId()?>" />
            <?php endif; ?>
            <?php $cb_id = 'panel_lesson_cb_'.$lesson->getId() ?>
            <input type="checkbox" id="<?php echo $cb_id; ?>" name="lesson[]" value="<?php echo $lesson->getId()?>" <?php
                if ($timetable->hasLesson($lesson->getId())) echo ' checked="checked"';
            ?> /><label class="pristupnost" for="<?php echo $cb_id; ?>">Zobraziť v rozvrhu: <?php echo Candle::formatShortDay($lesson->getDay()) . ' ' . Candle::formatTime($lesson->getStart()) . ' ' . $lesson->getSubject() ?></label></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

</li>

<?php endforeach; ?>

</ul>

<button type="submit" class="jshide">Zmeniť hodiny</button>

</div>
</form>
