<?php echo form_tag('@timetable_change_lessons?id='.$timetable_id); ?>

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
        <input class="predmet_check" type="checkbox" name="subject[]" value="<?php echo $subject->getId()?>" <?php
                if ($allSelected) echo 'checked="checked"' ?>/>
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
          <td><?php echo $lesson->getLessonType()->getCode() ?></td>
          <td><?php echo Candle::formatShortDay($lesson->getDay()) ?></td>
          <td><?php echo Candle::formatTime($lesson->getStart()) ?></td>
          <td><?php echo $lesson->getRoom() ?></td>
          <td><?php foreach ($lesson->getTeacher() as $i => $teacher): 
                        if ($i>0) echo ', ';
                        echo $teacher->getShortName();
                    endforeach; ?>
          </td>
          <td class="last">
            <?php if ($timetable->hasLesson($lesson->getId())): ?>
                <input type="hidden" name="lessonBefore[]" value="<?php echo $lesson->getId()?>" />
            <?php endif; ?>
            <input type="checkbox" name="lesson[]" value="<?php echo $lesson->getId()?>" <?php
                if ($timetable->hasLesson($lesson->getId())) echo ' checked="checked"';
            ?> /></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

</li>

<?php endforeach; ?>

</ul>

<button type="submit" class="jshide">Zmeniť hodiny</button>

</form>
