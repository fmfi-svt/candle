<?xml version="1.0" encoding="utf-8" ?>
<timetable version="0.1" name="<?php echo $timetable->getName(); ?>">
<?php foreach ($layout->getLessons() as $lesson): ?>
    <lesson id="<?php echo $lesson['id']; ?>">
        <type><?php echo $lesson['LessonType']['name']; ?></type>
        <room><?php echo $lesson['Room']['name']; ?></room>
        <subject><?php echo $lesson['Subject']['name']; ?></subject>
        <start><?php echo Candle::formatTime($lesson['start']); ?></start>
        <end><?php echo Candle::formatTime($lesson['end']); ?></end>
        <?php foreach ($lesson['Teacher'] as $teacher): ?>
        <teacher><?php echo Candle::formatShortName($teacher)?></teacher>
        <?php endforeach; ?>
        <?php if ($lesson['note'] !== null): ?>
        <note><?php echo $lesson['note']; ?></note>
        <?php endif; ?>
    </lesson>
<?php endforeach; ?>
</timetable>