
<?php if ($subjects): ?>
    <div class="pristupnost">Výsledky hľadania:</div>
    <?php include_partial('panel/list_lessons', array(
                'form' => $form,
                'subjects' => $subjects,
                'timetable' => $timetable,
                'timetable_id' => $timetable_id
            )); ?>
<?php else: ?>
    <?php if ($timetable): ?>
        <p>Upravovať rozvrh začnite vyhľadaním a začiarknutím predmetu alebo hodiny, ktorú chcete pridať</p>
    <?php else: ?>
        <p>Pomocou formuláru vyššie vyhľadajte predmety alebo hodiny</p>
    <?php endif; ?>
<?php endif; ?>
