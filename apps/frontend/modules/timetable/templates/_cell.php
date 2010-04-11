<div class="wrap"><!--
    --><div class="predmet_wrap"><div class="predmet"><?php echo $lesson->getSubject(); ?></div></div><!--
    --><div class="miestnost"><?php echo $lesson->getRoom(); ?></div><!--
    --><div class="typ"><abbr title="<?php echo $lesson->getLessonType(); ?>"><?php echo $lesson->getLessonType()->getCode(); ?></abbr></div><!--
    --><!-- TODO <label class="pristupnost" for="zobrazitHodinu1">Zobraziť Predmet štvrtok 12:20</label><input type="checkbox" class="vybrany" id="zobrazitHodinu1" />--></div>
