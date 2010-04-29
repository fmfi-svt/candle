<?php echo form_tag('@timetable_change_lessons?id='.$timetable_id, array('id'=>'panel_change_lessons')); ?>
<div>

<ul class="vysledky_hladania">

<?php foreach ($subjects as $subject):

$lessons = $subject['Lessons'];

$allSelected = true;
foreach ($lessons as $lesson) {
    if (!$timetable->hasLesson($lesson['id'])) {
        $allSelected = false;
        break;
    }
}

?>

<li class="predmet">
    <div class="predmet_header">
        <?php $cb_id = "panel_subject_cb_".$subject['id']; ?>
        <input id="<?php echo $cb_id; ?>" class="predmet_check"
               type="checkbox" name="subject[]"
               value="<?php echo $subject['id']?>"
               <?php if ($allSelected) echo 'checked="checked"' ?>
               title="Zobraziť predmet"/>
        <label for="<?php echo $cb_id; ?>" class="predmet_nazov"><?php echo $subject['name'] ?></label>
        <div class="predmet_info"
            <span class="predmet_kod"><?php echo $subject['short_code'] ?></span>
            <?php if ($subject['rozsah']): ?>
                <span class="rozsah"><?php echo $subject['rozsah'] ?></span>
            <?php endif; ?>
        </div>
        <?php if ($allSelected): ?>
        <input type="hidden" name="subjectBefore[]" value="<?php echo $subject['id']?>" />
        <?php endif; ?>
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
          <td><abbr class="lesson-type lesson-type-<?php echo $lesson['LessonType']['code'] ?>" title="<?php echo $lesson['LessonType']['name']?>"><span class="lesson-type-in"><?php echo $lesson['LessonType']['code'] ?></span><span class="lesson-type-image"></span></abbr></td>
          <td><?php echo Candle::formatShortDay($lesson['day']) ?></td>
          <td><?php echo Candle::formatTime($lesson['start']) ?></td>
          <td><?php echo $lesson['Room']['name'] ?></td>
          <td><?php foreach ($lesson['Teacher'] as $i => $teacher):
                        if ($i>0) echo ', ';
                        echo Candle::nbsp(Candle::formatShortName($teacher));
                    endforeach; ?>
          </td>
          <td class="last">
            <?php if ($timetable->hasLesson($lesson['id'])): ?>
                <input type="hidden" name="lessonBefore[]" value="<?php echo $lesson['id']?>" />
            <?php endif; ?>
            <?php $cb_id = 'panel_lesson_cb_'.$lesson['id'] ?>
            <input type="checkbox" id="<?php echo $cb_id; ?>"
                   name="lesson[]"
                   value="<?php echo $lesson['id']?>"
                   <?php if ($timetable->hasLesson($lesson['id'])) echo ' checked="checked"';?>
                   title="Zobraziť v rozvrhu"
                /><label class="pristupnost" for="<?php echo $cb_id; ?>"><?php echo Candle::formatShortDay($lesson['day']) . ' ' . Candle::formatTime($lesson['start']) . ' ' . $subject['name'] ?></label></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

</li>

<?php endforeach; ?>

</ul>

<button type="submit" class="jshide">Zmeniť hodiny</button>

</div>
<?php echo '</form>' ?>
