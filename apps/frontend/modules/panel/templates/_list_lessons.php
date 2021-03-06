<?php
if ($timetable):
    echo form_tag('@timetable_change_lessons?id='.$timetable_id, array('id'=>'panel_change_lessons'));
endif
?>
<div>

<ul class="vysledky_hladania">

<?php foreach ($subjects as $subject):

$subjectInfoLink = Candle::makeSubjectInfoLink($subject['short_code']);

$lessons = $subject['Lessons'];

if ($timetable) {
    $allSelected = true;
    foreach ($lessons as $lesson) {
        if (!$timetable->hasLesson($lesson['id'])) {
            $allSelected = false;
            break;
        }
    }
}

?>

<li class="predmet">
    <div class="predmet_header">
        <?php if ($timetable): ?><!--
            <?php $cb_id = "panel_subject_cb_".$subject['id']; ?>
            --><input id="<?php echo $cb_id; ?>" class="predmet_check"
                   type="checkbox" name="subject[]"
                   value="<?php echo $subject['id']?>"
                   <?php if ($allSelected) echo 'checked="checked"' ?>
                   title="Zobraziť predmet"/><!--
            --><label for="<?php echo $cb_id; ?>" class="predmet_nazov"><?php echo $subject['name'] ?></label>
        <?php else: ?>
            <span class="predmet_nazov"><?php echo $subject['name'] ?></span>
        <?php endif ?>

        <div class="predmet_info">
            <table>
                <thead class="pristupnost">
                    <tr>
                        <th>ECTS kód</th>
                        <th>Rozsah</th>
                        <th>Odkazy</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php if ($subject['short_code']): ?>
                            <td class="predmet_kod"><?php echo $subject['short_code'] ?></td>
                        <?php else: ?>
                            <td class="predmet_kod">&nbsp;</td>
                        <?php endif; ?>
                        <?php if ($subject['rozsah']): ?>
                            <td class="rozsah"><?php echo $subject['rozsah'] ?></td>
                        <?php else: ?>
                            <td class="rozsah">&nbsp;</td
                        <?php endif; ?>
                        <?php if ($subjectInfoLink): ?>
                            <td class="odkazy"><a href="<?php echo $subjectInfoLink; ?>">viac info...</a></td>
                        <?php else: ?>
                            <td class="odkazy">&nbsp;</td
                        <?php endif; ?>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php if ($timetable && $allSelected): ?>
        <input type="hidden" name="subjectBefore[]" value="<?php echo $subject['id']?>" />
        <?php endif; ?>
    </div>

    <table class="vysledky">
      <thead>
        <tr>
          <th>Typ</th><th>Kedy</th><th>Kde</th><th>Kto</th>
          <?php if ($timetable): ?>
            <th>Zobraziť</th>
          <?php endif ?>
        </tr>
      </thead>
        
      <tbody>
        <?php foreach ($lessons as $lesson): ?>
        <tr>
            <td class="lesson-type-cell"><abbr class="lesson-type <?php echo Candle::getLessonTypeHTMLClass($lesson['LessonType']); ?>" title="<?php echo $lesson['LessonType']['name']?>"><span class="lesson-type-in"><?php echo $lesson['LessonType']['code'] ?></span><span class="lesson-type-image"></span></abbr></td>
          <td class="kedy"><?php echo Candle::formatShortDay($lesson['day']) ?>&nbsp;<?php echo Candle::formatTime($lesson['start']) ?></td>
          <td class="kde"><?php echo $lesson['Room']['name'] ?></td>
          <td class="kto"><?php foreach ($lesson['Teacher'] as $i => $teacher):
                        if ($i>0) echo ', ';
                        echo Candle::nbsp(Candle::formatShortName($teacher));
                    endforeach; ?>
          </td>
          <?php if ($timetable): ?>
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
          <?php endif ?>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

</li>

<?php endforeach; ?>

</ul>

<?php if (count($subjects) == 50) {
    // TODO toto by mohlo z DB zistit, ci sa prekrocil alebo len dosiahol
    // tento limit + limit by sa mal dat nastavovat
    echo '<div>Výsledky vyhľadávania limitované na 50 výsledkov, skúste zadať presnejšie kritériá</div>';
}
?>
    
<?php if ($timetable): ?>
<button type="submit" class="jshide">Zmeniť hodiny</button>
<?php endif ?>

</div>
<?php if ($timetable) echo '</form>' ?>
