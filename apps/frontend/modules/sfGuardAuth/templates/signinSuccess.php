<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<?php $form->renderGlobalErrors(); ?>

<form action="<?php echo url_for('@sf_guard_signin') ?>" method="post">
  <?php echo $form['_csrf_token'] ?>
    <span class="nazov_policka">Prihlasovacie meno:</span> <?php echo $form['username'] ?><?php echo $form['username']->renderError(); ?><br />
    <span class="nazov_policka">Heslo:</span> <?php echo $form['password'] ?><?php echo $form['password']->renderError(); ?><br />

  <span class="nazov_policka"></span><?php echo $form['remember'] ?> <label for="signin_remember">Zapamätať</label><br />

  <span class="nazov_policka"></span><button type="submit">Prihlásiť sa</button>
</form>
