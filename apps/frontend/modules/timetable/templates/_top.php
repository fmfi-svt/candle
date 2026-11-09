<nav class="tabs">
<h2 class="pristupnost">Výber aktívneho rozvrhu</h2>
<ul id="rozvrh_taby" class="tabs__list"><?php
        foreach($all_timetables as $i => $current_timetable /* $timetable is current, don't redefine it! */):
            ?><li class="tabs__item"><a rel="nofollow" href="<?php echo url_for('@timetable_show?id='.$i); ?>" class="tabs__link<?php
                    if (isset($timetable_id) && $i==$timetable_id) {
                        echo ' selected';
                    }
                    ?>"><?php
                    echo $current_timetable->getName();
                    if ($current_timetable->isModified()):
                        ?> <span class="rozvrh_stav">[upravený]</span><?php
                    endif;
            ?></a></li><?php
          endforeach;
    ?><li class="tabs__item"><a rel="nofollow" href="<?php echo url_for('@timetable_new') ?>" class="tabs__link tabs__link--new<?php
        if ($newSelected) echo ' selected' ?>"><i class="fa fa-plus" aria-hidden="true"></i>Vytvoriť nový rozvrh</a></li></ul>
</nav>
