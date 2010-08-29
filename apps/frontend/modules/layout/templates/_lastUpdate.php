Posledná aktualizácia rozvrhovej databázy:
<?php if ($lastUpdate !== false):
    echo $lastUpdate['datetime'];
    if (!empty($lastUpdate['description'])):
        echo ' ('.$lastUpdate['description'].')';
    endif;
else:
    echo 'žiadna (pravdepodobne nebol vykonaný žiaden import dát)';
endif;
?>