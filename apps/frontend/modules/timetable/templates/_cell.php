<div class="wrap"><!--
    --><div class="predmet_wrap"><div class="predmet"><?php echo $lesson['Subject']['name']; ?></div></div><!--
    --><div class="hodina_info"><div class="miestnost"><?php echo $lesson['Room']['name']; ?></div><!--
    --><div class="typ"><abbr title="<?php echo $lesson['LessonType']['name']; ?>"><?php echo Candle::upper($lesson['LessonType']['code']); ?></abbr></div></div>
    <?php if ($highlighted): ?>
    <div class="pristupnost">[zvýraznená hodina]</div>
    <?php endif; ?>

    <?php if ($editable): ?>
        <?php $cb_id = 'timetable_lesson_selection_cb_'.$lesson['id'] ?>
        <input type="checkbox" id="<?php echo $cb_id; ?>" name="lesson_selection[]" value="<?php echo $lesson['id']?>"
        title="Označiť túto hodinu" /><label class="pristupnost" for="<?php echo $cb_id; ?>">Zobraziť v rozvrhu: <?php echo Candle::formatShortDay($lesson['day']) . ' ' . Candle::formatTime($lesson['start']) . ' ' . $lesson['Subject']['name'] ?></label>
    <?php endif; ?>
    
</div>
