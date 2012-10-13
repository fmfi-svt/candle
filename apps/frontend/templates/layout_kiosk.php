<?php
    $panelText = get_slot('panel');
?><!DOCTYPE html>
<html class="kiosk">
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
    <?php if (!has_slot('no_analytics')): ?>
        <?php include_component('layout', 'analytics') ?>
    <?php endif; ?>
</head>
<body class="kiosk">
<div id="vrch">
<div id="vrch_logo"><?php echo link_to('Candle', '@homepage') ?></div>
<div id="kiosk_vrch">
    <?php if (has_slot('header_kiosk')):
            include_slot('header_kiosk');
          else:
            include_slot('header');
          endif;
    ?>
    <div style="clear: both"></div>
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
                        Candle &copy; 2010,2011,2012 Martin Sucha. <?php echo link_to('Podmienky používania', '@terms_of_use'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<ul id="sidebar">
    <li class="button"><?php echo link_to('Aktuálna výučba', array('sf_route' => 'lessonSearch_current')) ?></li>
    <li class="button"><?php echo link_to('Krúžky', array('sf_route' => 'studentGroup_list')) ?></li>
    <li class="button"><?php echo link_to('Miestnosti', array('sf_route' => 'room_list')) ?></li>
    <li class="button"><?php echo link_to('Učitelia', array('sf_route' => 'timetable_teacher_list')) ?></li>
</ul>
</body>
</html>
