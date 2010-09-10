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
        (názov/kód predmetu, učiteľ, miestnosť)
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
                <p>Upravovať rozvrh začnite vyhľadaním a začiarknutím predmetu alebo hodiny, ktorú chcete pridať</p>
            <?php else: ?>
                <p>Pomocou formuláru vyššie vyhľadajte predmety alebo hodiny</p>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
<div class="panel_cast" id="panel_cast_ucitelia">
    <h2><a href="#panel_cast_ucitelia">Rozvrhy pre učiteľov</a></h2>
    <div class="panel_cast_obsah">
        <?php include_stylesheets_for_form($teacherForm) ?>
        <?php include_javascripts_for_form($teacherForm) ?>

        <form method="post" action=""><div class="panel_search">
            <?php echo $teacherForm['showTeachers']->renderLabel();
                echo $teacherForm['showTeachers'] ?><button type="submit">Hľadaj</button>
            <?php echo $teacherForm['showTeachers']->renderError() ?>
        </div></form>

        (meno a/alebo priezvisko učiteľa)

        <?php if ($teachers): ?>
            <div class="pristupnost">Výsledky hľadania:</div>
            <?php include_partial('panel/list_teachers', array(
                        'teachers' => $teachers,
                    )); ?>
        <?php endif; ?>
    </div>
</div>
<div class="panel_cast" id="panel_cast_miestnosti">
    <h2><a href="#panel_cast_miestnosti">Rozvrhy pre miestnosti</a></h2>
    <div class="panel_cast_obsah">
        <?php include_stylesheets_for_form($roomForm) ?>
        <?php include_javascripts_for_form($roomForm) ?>

        <form method="post" action=""><div class="panel_search">
            <?php echo $roomForm['showRooms']->renderLabel();
                echo $roomForm['showRooms'] ?><button type="submit">Hľadaj</button>
            <?php echo $roomForm['showRooms']->renderError() ?>
        </div></form>

        (názov miestnosti)

        <?php if ($rooms): ?>
            <div class="pristupnost">Výsledky hľadania:</div>
            <?php include_partial('panel/list_rooms', array(
                        'rooms' => $rooms,
                    )); ?>
        <?php endif; ?>
    </div>
</div>
<div class="panel_cast" id="panel_cast_kruzky">
    <h2><a href="#panel_cast_kruzky">Rozvrhy pre krúžky</a></h2>
    <div class="panel_cast_obsah">
        <?php include_stylesheets_for_form($studentGroupForm) ?>
        <?php include_javascripts_for_form($studentGroupForm) ?>

        <form method="post" action=""><div class="panel_search">
            <?php echo $studentGroupForm['showStudentGroups']->renderLabel();
                echo $studentGroupForm['showStudentGroups'] ?><button type="submit">Hľadaj</button>
            <?php echo $studentGroupForm['showStudentGroups']->renderError() ?>
        </div></form>

        (názov krúžku)

        <?php if ($studentGroups): ?>
            <div class="pristupnost">Výsledky hľadania:</div>
            <?php include_partial('panel/list_studentGroups', array(
                        'studentGroups' => $studentGroups,
                    )); ?>
        <?php endif; ?>
    </div>
</div>

<div class="panel_cast_no_collapse" id="panel_cast_nastroje">
    <h2>Nástroje</h2>
    <div class="panel_cast_obsah">
        <ul><li>
        <?php echo link_to('Vyhľadávanie voľných miestností','@freeRoom_search_parameters'); ?>
        </li></ul>
    </div>
</div>
