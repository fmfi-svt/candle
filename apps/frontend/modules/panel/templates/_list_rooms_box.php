<?php if ($rooms): ?>
    <div class="pristupnost">Výsledky hľadania:</div>
    <?php include_partial('panel/list_rooms', array(
                'rooms' => $rooms,
            )); ?>
<?php endif; ?>
