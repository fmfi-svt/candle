<?php if ($teachers): ?>
    <div class="pristupnost">Výsledky hľadania:</div>
    <?php include_partial('panel/list_teachers', array(
                'teachers' => $teachers,
            )); ?>
<?php endif; ?>
