<table id="rozvrhList" class="vysledky_podrobneho_hladania">
    <tr>
        <th>Deň</th>
        <th>Od</th>
        <th>Do</th>
        <th>Miestnosť</th>
        <th>Typ</th>
        <th>Kód</th>
        <th>Predmet</th>
        <th>Vyučujúci</th>
        <th>Poznámka</th>
    </tr>
<?php foreach ($layout->getLessons() as $lesson): 
        $shortCode = $lesson['Subject']['short_code'];
        $subjectInfoLink = Candle::makeSubjectInfoLink($shortCode);
        $subjectName = $lesson['Subject']['name'];
?>
    <tr>
        <td><?php echo Candle::formatShortDay($lesson['day']) ?></td>
        <td><?php echo Candle::formatTime($lesson['start']) ?></td>
        <td><?php echo Candle::formatTime($lesson['end']) ?></td>
        <td><?php echo link_to($lesson['Room']['name'], array('sf_route'=>'room_show', 'sf_subject'=>$lesson['Room'])) ?></td>
        <td><?php echo $lesson['LessonType']['name'] ?></td>
        <td><?php echo $shortCode ?></td>
        <td><?php if ($subjectInfoLink):
                    echo link_to($subjectName, $subjectInfoLink);
                  else:
                    echo $subjectName;
                  endif;
        ?></td>
        <td><?php
        $first = true;
        foreach ($lesson['Teacher'] as $teacher) {
            if (!$first) {
                echo ', ';
            }
            $shortName = Candle::formatShortName($teacher);
            if ($shortName == '') continue;
            $first = false;
            echo link_to($shortName, array('sf_route' => 'timetable_teacher_show', 'sf_subject' => $teacher));
        }
        ?></td>
        <td><?php echo ($lesson['note'] === null) ? '' : $lesson['note']; ?></td>
    </tr>
<?php endforeach; ?>
</table>