<h2 class="pristupnost">Menu používateľa</h2>
<span class="username"><?php

if ($username) { 
    echo $username;
}
else {
    echo 'neprihlásený návštevník';
}

?></span><!--
--><ul><li><?php

if ($username) {
    echo link_to('Odhlásiť', '@signout', array('rel' => 'nofollow'));
}
else {
    echo link_to('Prihlásiť', '@signin', array('rel' => 'nofollow'));
    ?></li><li><?php
    echo link_to('Zrušiť session', '@signout', array('rel' => 'nofollow'));
}

?></li></ul>
