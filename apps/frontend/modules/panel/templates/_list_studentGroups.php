<ul class="vysledky_hladania">

<?php foreach ($studentGroups as $studentGroup):?>

    <li><?php echo link_to($studentGroup['name'], array('sf_route'=>'studentGroup_show', 'sf_subject'=>$studentGroup)); ?></li>

<?php endforeach; ?>

</ul>

<?php if (count($studentGroups) == 50) {
    // TODO toto by mohlo z DB zistit, ci sa prekrocil alebo len dosiahol
    // tento limit + limit by sa mal dat nastavovat
    echo '<div>Výsledky vyhľadávania limitované na 50 výsledkov, skúste zadať presnejšie kritériá</div>';
}
?>