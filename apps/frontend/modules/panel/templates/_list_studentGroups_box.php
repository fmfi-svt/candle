<?php if ($studentGroups): ?>
    <div class="pristupnost">Výsledky hľadania:</div>
    <?php include_partial('panel/list_studentGroups', array(
                'studentGroups' => $studentGroups,
            )); ?>
<?php endif; ?>
