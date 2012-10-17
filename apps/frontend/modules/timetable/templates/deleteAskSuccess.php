<?php

slot('top');
include_component('timetable','top',array('timetable'=>$timetable, 'timetable_id'=>$timetable_id));
end_slot();

slot('header');
?>
<h1>Zmazať rozvrh: <?php echo $timetable->getName(); ?></h1>
<?php end_slot(); ?>
<div><strong>Zmazanie rozvrhu je nevratná operácia!</strong><br />
    Naozaj si prajete zmazať rozvrh?<br />
    Ak áno, tak do políčka nižšie napíšte text "del" (bez úvodzoviek)
    a odošlite formulár tlačítkom "Zmazať rozvrh".</div>
<?php echo form_tag(array('sf_route'=>'timetable_delete', 'id'=>$timetable_id)); ?>
<?php echo $form['_csrf_token']; ?>
<?php echo $form['confirmation']->renderError(); ?><?php echo $form['confirmation']->renderLabel(); ?>:
<?php echo $form['confirmation']; ?>
<div>
<button type="submit">Zmazať rozvrh</button>
</div>
<?php echo '</form>'; ?>
<p>
   <?php echo link_to("Nemazať rozvrh a vrátiť sa späť", array('sf_route'=>'timetable_show', 'id'=>$timetable_id)); ?>
</p>

<hr />
<div>
Náhľad rozvrhu, ktorý bude vymazaný:
<h2><?php echo $timetable->getName(); ?></h2>
<?php include_partial('timetable/table',
        array(  'timetable'=>$timetable,
                'layout'=>$layout,
                'editable'=>false
        )); ?>
</div>

