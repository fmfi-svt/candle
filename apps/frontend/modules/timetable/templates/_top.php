<h2 class="pristupnost">Výber aktívneho rozvrhu</h2>
<ul id="rozvrh_taby">
    <?php foreach($all_timetables as $i => $current_timetable /* $timetable is current, don't redefine it! */):
            ?><li><a href="<?php echo url_for('@timetable_show?id='.$i); ?>" class="selected"><?php
                    echo $current_timetable->getName();
                    if ($current_timetable->isModified()):
                        ?> <span class="rozvrh_stav">[upravený]</span><?php
                    endif;
            ?></a></li><?php
          endforeach;
    ?><li><a href="#" class="rozvrh_taby_novy">Nový</a></li>
</ul>
<h2 class="pristupnost">Akcie aktívneho rozvrhu</h2>
<ul id="rozvrh_akcie">
    <li><a href="#">Uložiť</a></li><!--
    --><li><a href="#">Duplikovať</a></li><!--
    --><li><a href="#">Importovať</a></li><!--
    --><li><a href="#">Exportovať</a></li><!--
    --><li><a href="#">Publikovať</a></li><!--
    --><li><a href="#">Zmazať</a></li>
</ul>
