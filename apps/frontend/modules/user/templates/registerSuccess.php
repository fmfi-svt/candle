<?php

echo form_tag('@register_post');

echo $form->renderHiddenFields();

?>

<?php echo $form['username']->renderLabel('Prihlasovace meno',array('class'=>'nazov_policka')); ?>: <?php echo $form['username'];?><?php echo $form['username']->renderError(); ?><br />
<?php echo $form['password']->renderLabel('Heslo',array('class'=>'nazov_policka')); ?>: <?php echo $form['password'];?><?php echo $form['password']->renderError(); ?><br />
<?php echo $form['password_repeat']->renderLabel('Heslo (znova)',array('class'=>'nazov_policka')); ?>: <?php echo $form['password_repeat'];?><?php echo $form['password_repeat']->renderError(); ?><br />
<button type="submit">Registrovať</button>

<?php echo '</form>'; ?>