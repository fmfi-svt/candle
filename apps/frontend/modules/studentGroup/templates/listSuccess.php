<?php

slot('title', 'Rozvrhy krúžkov');

slot('header');
?>
<h1>Rozvrhy krúžkov</h1>
<?php end_slot(); ?>
<p>
    <?php foreach ($groups as $group => $studentGroups): ?>
        <a href="#<?php echo $group?>"><?php echo $group ?></a>
    <?php endforeach; ?>
</p>
<?php foreach ($groups as $group => $studentGroups): ?>
    <h2 id="<?php echo $group ?>"><?php echo $group; ?></h2>
    <ul>
        <?php foreach ($studentGroups as $studentGroup): ?>
        <li><?php echo link_to($studentGroup['name'], array('sf_route' => 'studentGroup_show', 'sf_subject' => $studentGroup)) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endforeach; ?>
