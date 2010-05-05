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
    echo link_to('Profil', '@profile');
    ?></li><li><?php
    echo link_to('Odhlásiť', '@sf_guard_signout');
}
else {
    echo link_to('Prihlásiť', '@sf_guard_signin');
    ?></li><li><?php
    echo link_to('Registrovať', '@register');
    ?></li><li><?php
    echo link_to('Zrušiť session', '@sf_guard_signout');
}

?></li></ul>
