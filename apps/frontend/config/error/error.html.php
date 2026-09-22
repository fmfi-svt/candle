<!DOCTYPE html>
<html lang="sk">
<head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <title>Chyba - Rozvrh pre FMFI UK</title>
    <?php echo stylesheet_tag('font-awesome.min.css') ?>
    <?php echo stylesheet_tag('style.css') ?>
</head>
<body>
<div class="main_wraper">
    <div class="sidebar">
        <div class="sidebar__visible">
            <a class="sidebar__logo_link" href="<?php echo url_for('@homepage') ?>" title="Rozvrh - úvodná stránka"><img class="sidebar__logo" src="<?php echo image_path('logo.svg') ?>" alt="Candle" /></a>
        </div>
    </div>
    <div class="content">
        <div class="content__header">
            <div class="content__header_name">
                <h1>Chyba</h1>
            </div>
        </div>
        <div class="content__body">
            <p>Pri spracovaní požiadavky nastala chyba.</p>
            <p><?php echo link_to('Prejsť na hlavnú stránku', '@homepage'); ?></p>
        </div>
    </div>
</div>
</body>
</html>
