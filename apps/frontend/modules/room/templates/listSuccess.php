<?php

slot('title', 'Rozvrhy miestností');

slot('header');
?>
<h1>Rozvrhy miestností</h1>
<?php end_slot(); ?>
<p>
    <?php foreach ($groups as $group => $rooms): ?>
        <a href="#<?php echo $group?>"><?php echo $group ?></a>
    <?php endforeach; ?>
</p>
<?php foreach ($groups as $group => $rooms): ?>
    <h2 id="<?php echo $group ?>"><?php echo $group; ?></h2>
    <ul>
        <?php foreach ($rooms as $room): ?>
        <li><?php echo link_to($room['name'], array('sf_route' => 'room_show', 'sf_subject' => $room)) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endforeach; ?>
