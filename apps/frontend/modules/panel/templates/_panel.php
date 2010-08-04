<div class="panel_cast" id="panel_cast_hodiny">
    <h2><a href="#panel_cast_hodiny">Predmety a hodiny</a></h2>
    <div class="panel_cast_obsah">
        <?php include_stylesheets_for_form($lessonForm) ?>
        <?php include_javascripts_for_form($lessonForm) ?>

        <form method="post" action=""><div class="panel_search">
            <?php echo $lessonForm['showLessons']->renderLabel();
                echo $lessonForm['showLessons'] ?><button type="submit">Hľadaj</button>
            <?php echo $lessonForm['showLessons']->renderError() ?>
        </div></form>
        <?php if ($subjects): ?>
            <div class="pristupnost">Výsledky hľadania:</div>
            <?php include_partial('panel/list_lessons', array(
                        'form' => $lessonForm,
                        'subjects' => $subjects,
                        'timetable' => $timetable,
                        'timetable_id' => $timetable_id
                    )); ?>
        <?php else: ?>
            <?php if ($timetable): ?>
                <div>Upravovať rozvrh začnite vyhľadaním a začiarknutím predmetu alebo hodiny, ktorú chcete pridať</div>
            <?php else: ?>
                <div>Pomocou formuláru vyššie vyhľadajte predmety alebo hodiny</div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<div class="panel_cast" id="panel_cast_ucitelia">
    <h2><a href="#panel_cast_ucitelia">Učitelia</a></h2>
    <div class="panel_cast_obsah">
        <?php include_stylesheets_for_form($teacherForm) ?>
        <?php include_javascripts_for_form($teacherForm) ?>

        <form method="post" action=""><div class="panel_search">
            <?php echo $teacherForm['showTeachers']->renderLabel();
                echo $teacherForm['showTeachers'] ?><button type="submit">Hľadaj</button>
            <?php echo $teacherForm['showTeachers']->renderError() ?>
        </div></form>

        <?php if ($teachers): ?>
            <div class="pristupnost">Výsledky hľadania:</div>
            <?php include_partial('panel/list_teachers', array(
                        'teachers' => $teachers,
                    )); ?>
        <?php endif; ?>
    </div>
</div>
<div class="panel_cast" id="panel_cast_miestnosti">
    <h2><a href="#panel_cast_miestnosti">Miestnosti</a></h2>
    <div class="panel_cast_obsah">
        <?php include_stylesheets_for_form($roomForm) ?>
        <?php include_javascripts_for_form($roomForm) ?>

        <form method="post" action=""><div class="panel_search">
            <?php echo $roomForm['showRooms']->renderLabel();
                echo $roomForm['showRooms'] ?><button type="submit">Hľadaj</button>
            <?php echo $roomForm['showRooms']->renderError() ?>
        </div></form>

        <?php if ($rooms): ?>
            <div class="pristupnost">Výsledky hľadania:</div>
            <?php include_partial('panel/list_rooms', array(
                        'rooms' => $rooms,
                    )); ?>
        <?php endif; ?>
    </div>
</div>
<div class="panel_cast" id="panel_cast_kruzky">
    <h2><a href="#panel_cast_kruzky">Krúžky</a></h2>
    <div class="panel_cast_obsah">
        <form method="post" action=""><div class="panel_search">
            <label for="hladaj_kruzky" class="pristupnost">Hľadaj krúžky</label><input id="hladaj_kruzky" type="text" /><button type="submit">Hľadaj</button>
        </div></form>
    </div>
</div>
