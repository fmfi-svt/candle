<?php
/*
 * Mriežka rozvrhu: hlavička s dňami, stĺpec s časmi a päť stĺpcov dní.
 * Hodiny sú karty umiestnené v stĺpci dňa absolútne podľa času začiatku
 * a dĺžky. Hodiny, ktoré sa časovo prekrývajú, sú poukladané na seba.
 *
 * Premenné: $timetable (EditableTimetable), $layout (TimetableLayout), $editable (bool)
 */
use_helper('Candle');

$slotMinutes = 50; // jeden riadok mriežky = 50 minút (45 min hodina + 5 min prestávka)
$slotHeight = 51;  // výška riadku v px, musí sedieť s $slot-height v web/scss/_variables.scss
$pxPerMinute = $slotHeight / $slotMinutes;
$stackOffset = 35; // o koľko px je každá ďalšia prekrývajúca sa karta užšia

$mintime = 490;  // 8:10
$maxtime = 1190; // 19:50
$mintime = min($mintime, Candle::floorTo($layout->getLessonMinTime(), $slotMinutes, 40));
$maxtime = max($maxtime, Candle::ceilTo($layout->getLessonMaxTime(), $slotMinutes, 40));
$slotCount = intval(($maxtime - $mintime) / $slotMinutes);
$tableHeight = $slotCount * $slotHeight;

$byDays = $layout->groupByDays();
$lessonCount = count($layout->getLessons());
?>
<div class="content__timetable">
    <div class="timetable__days" id="timetable__days">
        <?php for ($day = 0; $day < 5; $day++): ?>
        <div class="timetable__day" data-day="<?php echo $day ?>"><?php echo Candle::formatLongDay($day) ?></div>
        <?php endfor; ?>
    </div>
    <div class="timetable" id="rozvrh"
         data-start="<?php echo $mintime ?>" data-slot-minutes="<?php echo $slotMinutes ?>" data-slot-height="<?php echo $slotHeight ?>"
         style="height: <?php echo $tableHeight ?>px;">
        <div class="timetable__current_time hidden" id="timetable__current_time"></div>
        <?php if ($lessonCount == 0): ?>
        <div class="timetable__empty">
            <?php if ($editable): ?>
                Rozvrh je prázdny. Hodiny doň pridáte vyhľadaním predmetu v paneli vľavo a zaškrtnutím hodín, ktoré chcete v rozvrhu mať.
            <?php else: ?>
                V tomto rozvrhu nie sú žiadne hodiny.
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <div class="timetable__collum timetable__collum--times">
            <?php for ($time = $mintime; $time < $maxtime; $time += $slotMinutes): ?>
            <div class="timetable__cell"><?php echo Candle::formatTime($time) ?></div>
            <?php endfor; ?>
        </div>
        <?php for ($day = 0; $day < 5; $day++): ?>
        <div class="timetable__collum" data-day="<?php echo $day ?>">
            <?php foreach (candle_timetable_clusters($byDays[$day], $layout) as $cluster):
                $top = round(($cluster['start'] - $mintime) * $pxPerMinute);
                $height = round(($cluster['end'] - $cluster['start']) * $pxPerMinute);
                $stacked = count($cluster['lessons']) > 1;
                $containerClasses = array('lecture_container');
                if (!$stacked && $height <= $slotHeight) {
                    $containerClasses[] = 'lecture_container--size-1';
                }
            ?>
            <div class="<?php echo implode(' ', $containerClasses) ?>" style="top: <?php echo $top ?>px; height: <?php echo $height ?>px;">
                <?php foreach ($cluster['lessons'] as $index => $item):
                    $lesson = $item['lesson'];
                    $style = '';
                    if ($stacked) {
                        $lessonTop = round(($item['start'] - $cluster['start']) * $pxPerMinute) + 2;
                        $lessonHeight = round(($item['end'] - $item['start']) * $pxPerMinute) - 5;
                        $style = 'top: '.$lessonTop.'px; height: '.$lessonHeight.'px; bottom: auto; right: '.(4 + $stackOffset * $index).'px;';
                    }
                    include_partial('timetable/cell', array(
                        'lesson' => $lesson,
                        'highlighted' => $timetable->isLessonHighlighted($lesson['id']),
                        'editable' => $editable,
                        'stacked' => $stacked,
                        'style' => $style,
                    ));
                endforeach; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endfor; ?>
    </div>
</div>
<div class="timetable_below">
<?php include_partial('timetable/list', array('timetable'=>$timetable, 'layout'=>$layout)); ?>
</div>
