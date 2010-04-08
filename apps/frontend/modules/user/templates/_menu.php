<h2 class="pristupnost">Menu používateľa</h2>
<span class="username"><?php

if ($username) { 
    echo $username;
}
else {
    echo 'neprihlásený návštevník';
}

?></span><!--
--><ul><!--
    --><li><a href="#">Nastavenia</a></li><!--
    --><li><?php

if ($username) {
    echo link_to('Odhlásiť', '@sf_guard_signout');
}
else {
    echo link_to('Prihlásiť', '@sf_guard_signin');
}

?></li><!--
--></ul>
