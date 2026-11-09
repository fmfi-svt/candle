<h2 class="pristupnost">Menu používateľa</h2>
<?php if ($username): ?>
<span class="username"><i class="fa fa-user-circle-o" aria-hidden="true"></i><?php echo $username ?></span><?php
    echo link_to('Odhlásiť', '@signout', array('rel' => 'nofollow'));
?>
<?php else: ?>
<?php echo link_to('Prihlásiť<i class="fa fa-user-circle-o" aria-hidden="true"></i>', '@signin', array('rel' => 'nofollow', 'title' => 'Prihlásiť sa')); ?>
<?php endif; ?>
