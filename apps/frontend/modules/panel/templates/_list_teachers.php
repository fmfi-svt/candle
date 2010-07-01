<ul class="vysledky_hladania">

<?php foreach ($teachers as $teacher):?>

    <li><?php echo link_to(Candle::formatLongName($teacher), '@timetable_teacher_show?id='.$teacher['id']); ?></li>

<?php endforeach; ?>

</ul>