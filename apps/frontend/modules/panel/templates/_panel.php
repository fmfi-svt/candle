<?php use_helper('Candle') ?>
<div id="panel_throbber" class="sidebar__throbber"><i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i> Načítavam...</div>

<section class="sidebar__block" id="panel_cast_hodiny">
    <h2 class="sidebar__block_title">Predmety a hodiny</h2>
    <?php include_stylesheets_for_form($lessonForm) ?>
    <?php include_javascripts_for_form($lessonForm) ?>

    <form method="post" action="" class="sidebar__block_input">
        <?php echo $lessonForm['showLessons']->renderLabel();
              echo $lessonForm['showLessons']->render(array('placeholder' => '(názov/kód predmetu, učiteľ, miestnosť)')) ?><button type="submit">Hľadaj</button>
    </form>
    <div class="sidebar__block_content"><?php echo $lessonForm['showLessons']->renderError() ?></div>
    <div id="list_lessons_box" class="sidebar__block_results">
    <?php include_partial('panel/list_lessons_box', array(
            'form' => $lessonForm,
            'subjects' => $subjects,
            'timetable' => $timetable,
            'timetable_id' => $timetable_id
        )); ?>
    </div>
</section>

<section class="sidebar__block" id="panel_cast_ucitelia">
    <h2 class="sidebar__block_title">Rozvrhy pre učiteľov</h2>
    <?php include_stylesheets_for_form($teacherForm) ?>
    <?php include_javascripts_for_form($teacherForm) ?>

    <form method="post" action="" class="sidebar__block_input">
        <?php echo $teacherForm['showTeachers']->renderLabel();
              echo $teacherForm['showTeachers']->render(array('placeholder' => '(meno a/alebo priezvisko učiteľa)')) ?><button type="submit">Hľadaj</button>
    </form>
    <div class="sidebar__block_content"><?php echo $teacherForm['showTeachers']->renderError() ?></div>
    <div id="list_teachers_box" class="sidebar__block_results">
    <?php include_partial('panel/list_teachers_box', array(
            'teachers' => $teachers
        )); ?>
    </div>
    <div class="sidebar__block_results other_links">
        <?php echo link_to('Rozvrhy všetkých učiteľov', array('sf_route' => 'timetable_teacher_list')); ?>
    </div>
</section>

<section class="sidebar__block" id="panel_cast_miestnosti">
    <h2 class="sidebar__block_title">Rozvrhy pre miestnosti</h2>
    <?php include_stylesheets_for_form($roomForm) ?>
    <?php include_javascripts_for_form($roomForm) ?>

    <form method="post" action="" class="sidebar__block_input">
        <?php echo $roomForm['showRooms']->renderLabel();
              echo $roomForm['showRooms']->render(array('placeholder' => '(názov miestnosti)')) ?><button type="submit">Hľadaj</button>
    </form>
    <div class="sidebar__block_content"><?php echo $roomForm['showRooms']->renderError() ?></div>
    <div id="list_rooms_box" class="sidebar__block_results">
    <?php include_partial('panel/list_rooms_box', array(
            'rooms' => $rooms
        )); ?>
    </div>
    <div class="sidebar__block_results other_links">
        <?php echo link_to('Rozvrhy všetkých miestností', array('sf_route' => 'room_list')); ?>
    </div>
</section>

<section class="sidebar__block" id="panel_cast_kruzky">
    <h2 class="sidebar__block_title">Rozvrhy pre krúžky</h2>
    <?php include_stylesheets_for_form($studentGroupForm) ?>
    <?php include_javascripts_for_form($studentGroupForm) ?>

    <form method="post" action="" class="sidebar__block_input">
        <?php echo $studentGroupForm['showStudentGroups']->renderLabel();
              echo $studentGroupForm['showStudentGroups']->render(array('placeholder' => '(názov krúžku)')) ?><button type="submit">Hľadaj</button>
    </form>
    <div class="sidebar__block_content"><?php echo $studentGroupForm['showStudentGroups']->renderError() ?></div>
    <div id="list_studentGroups_box" class="sidebar__block_results">
    <?php include_partial('panel/list_studentGroups_box', array(
            'studentGroups' => $studentGroups
        )); ?>
    </div>
    <div class="sidebar__block_results other_links">
        <?php echo link_to('Rozvrhy všetkých krúžkov', array('sf_route' => 'studentGroup_list')); ?>
    </div>
</section>

<section class="sidebar__block" id="panel_cast_nastroje">
    <h2 class="sidebar__block_title">Nástroje</h2>
    <div class="sidebar__block_content">
        <ul>
            <li><?php echo link_to('Vyhľadávanie voľných miestností','@freeRoom_search_parameters'); ?></li>
            <li><?php echo link_to('Vyhľadávanie hodín podľa času','@lessonSearch_search_parameters'); ?></li>
            <li><?php echo link_to('Aktuálne prebiehajúca výučba','@lessonSearch_current'); ?></li>
            <li><?php echo link_to('Aktuálne voľné miestnosti','@freeRoom_current'); ?></li>
        </ul>
    </div>
</section>
