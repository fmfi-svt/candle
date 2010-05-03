<?php

echo form_tag('@change_password');

echo $form->renderHiddenFields();

?>

<?php echo $form['old_password']->renderLabel('Staré heslo',array('class'=>'nazov_policka')); ?>: <?php echo $form['old_password'];?><?php echo $form['old_password']->renderError(); ?><br />
<?php echo $form['password']->renderLabel('Heslo',array('class'=>'nazov_policka')); ?>: <?php echo $form['password'];?><?php echo $form['password']->renderError(); ?><br />
<?php echo $form['password_repeat']->renderLabel('Heslo (znova)',array('class'=>'nazov_policka')); ?>: <?php echo $form['password_repeat'];?><?php echo $form['password_repeat']->renderError(); ?><br />
<button type="submit">Zmeniť heslo</button>

<?php echo '</form>'; ?>