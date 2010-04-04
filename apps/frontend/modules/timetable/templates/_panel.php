<div class="panel_cast" id="panel_cast_hodiny">
    <h2><a href="#panel_cast_hodiny">Hodiny</a></h2>
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
            <?php include_partial('lesson/list_panel', array('form' => $lessonForm, 'subjects' => $subjects)); ?>
        <?php endif; ?>
    </div>
</div>
<div class="panel_cast" id="panel_cast_ucitelia">
    <h2><a href="#panel_cast_ucitelia">Učitelia</a></h2>
    <div class="panel_cast_obsah">
        <form method="post" action=""><div class="panel_search">
            <label for="hladaj_ucitelov" class="pristupnost">Hľadaj učiteľov</label><input id="hladaj_ucitelov" type="text" /><button type="submit">Hľadaj</button>
        </div></form>
    </div>
</div>
<div class="panel_cast" id="panel_cast_miestnosti">
    <h2><a href="#panel_cast_miestnosti">Miestnosti</a></h2>
    <div class="panel_cast_obsah">
        <form method="post" action=""><div class="panel_search">
            <label for="hladaj_miestnosti" class="pristupnost">Hľadaj miestnosti</label><input id="hladaj_miestnosti" type="text" /><button type="submit">Hľadaj</button>
        </div></form>
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
