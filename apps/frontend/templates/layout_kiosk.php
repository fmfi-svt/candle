<?php
    $panelText = get_slot('panel');
?><!DOCTYPE html>
<html class="<?php if(!$panelText) echo 'panel_hidden'?>">
<head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <title><?php if (include_slot('title')) echo ' - ' ?>Rozvrh pre FMFI UK</title>
    <link rel="shortcut icon" href="<?php echo image_path('../favicon.ico') ?>" type="image/x-icon" />
    <script type="text/javascript">
        var candleFrontendRelativeUrl = "<?php echo url_for('@homepage');?>";
        var candleFrontendAbsoluteUrl = "<?php echo url_for('@homepage', true);?>";
        var candleFrontendDomain = "<?php echo $sf_request->getHost();?>";
        <?php include_slot('additionalRawJavascript') ?>
    </script>
    <?php include_stylesheets() ?>

    <!--[if lte IE 7]>
    <?php echo stylesheet_tag('main_ie7'); ?>
    <![endif]-->
    
    <?php echo stylesheet_tag('kiosk'); ?>

    <?php include_javascripts() ?>
    <?php include_component('layout', 'analytics') ?>
</head>
<body class="<?php if(!$panelText) echo 'panel_hidden'?>">
<div id="vrch">
<div id="vrch_logo"><?php echo link_to('Candle', '@homepage') ?></div>
<div id="kiosk_vrch">
    <?php include_slot('kiosk_vrch') ?>
</div>
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

                    <div class="footer">

                        <hr />

                        <?php include_component('layout', 'lastUpdate', array('mode' => 'kiosk')); ?>
                        Candle &copy; 2010,2011 Martin Sucha. <?php echo link_to('Podmienky používania', '@terms_of_use'); ?>
                    </div>
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
</body>
</html>
