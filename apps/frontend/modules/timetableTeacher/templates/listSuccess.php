<?php

slot('title', 'Rozvrhy učiteľov');

slot('header');
?>
<h1>Rozvrhy učiteľov</h1>
<?php end_slot(); ?>
<p>
    <?php foreach ($groups as $group => $teachers): ?>
        <a href="#<?php echo $group?>"><?php echo $group ?></a>
    <?php endforeach; ?>
</p>
<?php foreach ($groups as $group => $teachers): ?>
    <h2 id="<?php echo $group ?>"><?php echo $group; ?></h2>
    <ul>
        <?php foreach ($teachers as $teacher): ?>
        <li><?php echo link_to(Candle::formatReversedLongName($teacher), array('sf_route' => 'timetable_teacher_show', 'sf_subject' => $teacher)) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endforeach; ?>
