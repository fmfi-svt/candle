<ul class="vysledky_hladania">

<?php foreach ($teachers as $teacher):?>

    <li><?php echo link_to(Candle::formatLongName($teacher), array('sf_route'=>'timetable_teacher_show', 'sf_subject'=>$teacher)); ?></li>

<?php endforeach; ?>

</ul>

<?php if (count($teachers) == 50) {
    // TODO toto by mohlo z DB zistit, ci sa prekrocil alebo len dosiahol
    // tento limit + limit by sa mal dat nastavovat
    echo '<div>Výsledky vyhľadávania limitované na 50 výsledkov, skúste zadať presnejšie kritériá</div>';
}
?>