<ul class="vysledky_hladania">

<?php foreach ($rooms as $room):?>

    <li><?php echo link_to($room['name'], array('sf_route'=>'room_show', 'sf_subject'=>$room)); ?></li>

<?php endforeach; ?>

</ul>

<?php if (count($rooms) == 50) {
    // TODO toto by mohlo z DB zistit, ci sa prekrocil alebo len dosiahol
    // tento limit + limit by sa mal dat nastavovat
    echo '<div>Výsledky vyhľadávania limitované na 50 výsledkov, skúste zadať presnejšie kritériá</div>';
}
?>