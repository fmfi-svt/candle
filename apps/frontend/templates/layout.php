<?php
    $panelText = get_slot('panel');
?><!DOCTYPE html>
<html class="<?php if(!$panelText) echo 'panel_hidden'?>">
<head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <title><?php if (!include_slot('title')) echo 'Rozvrh'; ?></title>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
</head>
</head>
<body class="<?php if(!$panelText) echo 'panel_hidden'?>">
<div id="vrch">
<h1 id="vrch_logo"><?php echo link_to('Rozvrh', '@homepage') ?></h1>
</div>
<div id="hlavny">
    <div id="obsah_wrap">
        <div id="obsah_vrch">
            <div id="obsah_vrch_lavy">
                <div id="obsah_in">
                    <?php if ($sf_user->hasFlash('notice')): ?>
                      <div class="flash_notice">
                        <?php echo $sf_user->getFlash('notice') ?>
                      </div>
                    <?php endif; ?>
             
                    <?php if ($sf_user->hasFlash('error')): ?>
                      <div class="flash_error">
                        <?php echo $sf_user->getFlash('error') ?>
                      </div>
                    <?php endif; ?>

                    <?php echo $sf_content ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($panelText): ?>
    <div id="panel"><div id="panel_in">
        <?php echo $panelText ?>
    </div></div>
    
<div id="panel_schovat" class="hidden"><a href="#" id="panel_toggle"><span class="pristupnost">Schovať/Zobraziť panel</span></a>
</div>
<?php endif; ?>
<div id="vrch2">
<div id="vrch_riadok2">
    <?php include_slot('top') ?>
</div>
</div>
<div id="vrch_riadok1">
    <div id="vrch_riadok1_vpravo">
        <h2 class="pristupnost">Menu používateľa</h2>
        <span class="username">používateľ</span><!--
        --><ul><!--
            --><li><a href="#">Nastavenia</a></li><!--
            --><li><a href="#">Administrácia</a></li><!--
            --><li><a href="#">Odhlásiť</a></li><!--
        --></ul>
    </div>
    <div id="vrch_riadok1_vlavo">
        <h2 class="pristupnost">Užitočné stránky</h2>
        <ul><!--
            --><li><span class="selected">Rozvrh</span></li><!--
            --><li><a href="http://www.st.fmph.uniba.sk/~kralik3/">fajr</a></li><!--
            --><li><a href="https://ne.st.dcs.fmph.uniba.sk">projekty</a></li><!--
        --></ul>
    </div>    
</div>
</body>
</html>
