<?php
    $panelText = get_slot('panel');
?><!DOCTYPE html>
<html class="<?php if(!$panelText) echo 'panel_hidden'?>">
<head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <title><?php if (!include_slot('title')) echo 'Rozvrh'; ?></title>
    <link rel="shortcut icon" href="/favicon.ico" />
    <script type="text/javascript">
        var candleFrontendRelativeUrl = "<?php echo url_for('@homepage');?>";
        var candleFrontendAbsoluteUrl = "<?php echo url_for('@homepage', true);?>";
        var candleFrontendDomain = "<?php echo $sf_request->getHost();?>";
    </script>
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
</head>
<body class="<?php if(!$panelText) echo 'panel_hidden'?>">
<div id="vrch">
<div id="vrch_logo"><?php echo link_to('Rozvrh', '@homepage') ?></div>
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
        <?php include_component('user','menu'); ?>
    </div>
    <div id="vrch_riadok1_vlavo">
        <h2 class="pristupnost">Užitočné stránky</h2>
        <ul><!--
            --><li><?php echo link_to('Rozvrh', '@homepage', array('class'=>'selected')) ?></li><!--
            --><li><a href="https://fajr.dcs.fmph.uniba.sk/">fajr</a></li><!--
            --><li><a href="https://ne.st.dcs.fmph.uniba.sk/">projekty</a></li><!--
        --></ul>
    </div>    
</div>
</body>
</html>
