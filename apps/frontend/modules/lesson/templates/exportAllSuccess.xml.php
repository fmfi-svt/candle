<?php echo '<?xml version="1.0" encoding="utf-8" ?>'; ?>
<lessons>
<?php foreach ($lessons as $lesson): ?>
    <lesson id="<?php echo $lesson['id']; ?>">
        <type><?php echo $lesson['LessonType']['name']; ?></type>
        <room><?php echo $lesson['Room']['name']; ?></room>
        <subject><?php echo $lesson['Subject']['name']; ?></subject>
        <subject_code><?php echo $lesson['Subject']['short_code']; ?></subject_code>
        <day><?php echo Candle::formatShortDay($lesson['day']); ?></day>
        <start><?php echo Candle::formatTime($lesson['start']); ?></start>
        <end><?php echo Candle::formatTime($lesson['end']); ?></end>
<?php foreach ($lesson['Teacher'] as $teacher): ?>
        <teacher login="<?php echo $teacher['login']; ?>"><?php echo Candle::formatShortName($teacher); ?></teacher>
<?php endforeach; ?>
<?php if ($lesson['note'] !== null): ?>
        <note><?php echo $lesson['note']; ?></note>
<?php endif; ?>
    </lesson>
<?php endforeach; ?>
</lessons>
