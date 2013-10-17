<h2 class="pristupnost">Menu používateľa</h2>
<span class="username"><?php

if ($username) {
    echo $username;
}
else {
    echo 'anonymný návštevník';
}

?></span><!--
--><ul>
     <li>
<?php
if (!$username) {
    echo link_to('Prihlásiť', '@signin', array('rel' => 'nofollow'));
} else {
    echo link_to('Odhlásiť', '@signout', array('rel' => 'nofollow'));
}
?>
     </li>
   </ul>
