<?php if ($mode != 'kiosk'): ?>Posledná aktualizácia rozvrhovej databázy:<?php endif; ?>
<?php if ($lastUpdate !== false):
    echo '<span class="last_update">';
    echo $lastUpdate['datetime'];
    echo '</span>';
    if ($mode != 'kiosk' && !empty($lastUpdate['description'])):
        echo ' ('.$lastUpdate['description'].')';
    endif;
    if ($mode != 'kiosk') {
        echo ' Rozvrhové dáta Copyright ';
    }
    else {
        echo ' Dáta &copy; ';
    }
    echo substr($lastUpdate['datetime'],0,4).' Rozvrhári FMFI UK.';
else:
    echo 'žiadna';
    if ($mode != 'kiosk') {
        echo ' (pravdepodobne nebol vykonaný žiaden import dát)';
    }
endif;
?>