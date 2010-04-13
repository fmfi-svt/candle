<div class="wrap"><!--
    --><div class="predmet_wrap"><div class="predmet"><?php echo $lesson['Subject']['name']; ?></div></div><!--
    --><div class="miestnost"><?php echo $lesson['Room']['name']; ?></div><!--
    --><div class="typ"><abbr title="<?php echo $lesson['LessonType']['name']; ?>"><?php echo $lesson['LessonType']['code']; ?></abbr></div>
    <?php if ($highlighted): ?>
    <div class="pristupnost">[zvýraznená hodina]</div>
    <?php endif; ?>
    <input type="hidden" name="lessonBefore[]" value="<?php echo $lesson['id']?>" />
    <?php $cb_id = 'timetable_lesson_cb_'.$lesson['id'] ?>
    <input type="checkbox" id="<?php echo $cb_id; ?>" name="lesson[]" value="<?php echo $lesson['id']?>" checked="checked"
    title="Zobraziť túto hodinu v rozvrhu" /><label class="pristupnost" for="<?php echo $cb_id; ?>">Zobraziť v rozvrhu: <?php echo Candle::formatShortDay($lesson['day']) . ' ' . Candle::formatTime($lesson['start']) . ' ' . $lesson['Subject']['name'] ?></label>
    <?php if ($highlighted): ?>
        <input type="hidden" name="lessonHighlightedBefore[]" value="<?php echo $lesson['id']?>" />
    <?php endif; ?>
    <?php $cb_id = 'timetable_lesson_highlight_cb_'.$lesson['id'] ?>
    <input type="checkbox" id="<?php echo $cb_id; ?>" name="lessonHighlighted[]" value="<?php echo $lesson['id']?>"
           <?php if($highlighted) echo 'checked="checked"'; ?>
    title="Zvýrazniť túto hodinu v rozvrhu" /><label class="pristupnost" for="<?php echo $cb_id; ?>">Zvýrazniť v rozvrhu: <?php echo Candle::formatShortDay($lesson['day']) . ' ' . Candle::formatTime($lesson['start']) . ' ' . $lesson['Subject']['name'] ?></label>
    
</div>
