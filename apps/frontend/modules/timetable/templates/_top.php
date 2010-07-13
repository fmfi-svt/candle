<h2 class="pristupnost">Výber aktívneho rozvrhu</h2>
<ul id="rozvrh_taby"><?php $foundSelected = false;
        foreach($all_timetables as $i => $current_timetable /* $timetable is current, don't redefine it! */):
            ?><li><a href="<?php echo url_for('@timetable_show?id='.$i); ?>" <?php
                    if (isset($timetable_id) && $i==$timetable_id) {
                        echo ' class="selected"';
                        $foundSelected = true;
                    }
                    ?>><?php
                    echo $current_timetable->getName();
                    if ($current_timetable->isModified()):
                        ?> <span class="rozvrh_stav">[upravený]</span><?php
                    endif;
            ?></a></li><?php
          endforeach;
    ?><li><a href="<?php echo url_for('@timetable_new') ?>" class="rozvrh_taby_novy<?php 
        if ($newSelected) echo ' selected' ?>">Vytvoriť nový rozvrh</a></li></ul>