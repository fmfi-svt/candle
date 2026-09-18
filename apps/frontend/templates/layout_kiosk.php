<!DOCTYPE html>
<html lang="sk" class="kiosk">
<head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php if (include_slot('title')) echo ' - ' ?>Rozvrh pre FMFI UK</title>
    <link rel="shortcut icon" href="<?php echo image_path('../favicon.ico') ?>" type="image/x-icon" />
    <script type="text/javascript">
        var candleFrontendRelativeUrl = "<?php echo url_for('@homepage');?>";
        var candleFrontendAbsoluteUrl = "<?php echo url_for('@homepage', true);?>";
        var candleFrontendDomain = "<?php echo $sf_request->getHost();?>";
        <?php include_slot('additionalRawJavascript') ?>
    </script>
    <?php include_stylesheets() ?>

    <?php include_slot('additionalHeadTags') ?>

    <?php include_javascripts() ?>
    <?php if (!has_slot('no_analytics')): ?>
        <?php include_component('layout', 'analytics') ?>
    <?php endif; ?>
</head>
<body class="kiosk">
<div class="main_wraper">
    <div class="sidebar" id="sidebar">
        <div class="sidebar__visible">
            <a class="sidebar__logo_link" href="<?php echo url_for('@homepage') ?>" title="Candle"><img class="sidebar__logo" src="<?php echo image_path('logo.svg') ?>" alt="Candle" /></a>
            <ul class="sidebar__elements" id="kiosk_menu">
                <li class="sidebar__element"><?php echo link_to('<i class="fa fa-clock-o" aria-hidden="true"></i><p>Aktuálna výučba</p>', array('sf_route' => 'lessonSearch_current')) ?></li>
                <li class="sidebar__element"><?php echo link_to('<i class="fa fa-key" aria-hidden="true"></i><p>Voľné miestnosti</p>', array('sf_route' => 'freeRoom_current')) ?></li>
                <li class="sidebar__element"><?php echo link_to('<i class="fa fa-users" aria-hidden="true"></i><p>Krúžky</p>', array('sf_route' => 'studentGroup_list')) ?></li>
                <li class="sidebar__element"><?php echo link_to('<i class="fa fa-map-marker" aria-hidden="true"></i><p>Miestnosti</p>', array('sf_route' => 'room_list')) ?></li>
                <li class="sidebar__element"><?php echo link_to('<i class="fa fa-graduation-cap" aria-hidden="true"></i><p>Učitelia</p>', array('sf_route' => 'timetable_teacher_list')) ?></li>
            </ul>
        </div>
    </div>

    <div class="content">
        <div class="content__header">
            <div class="content__header_name">
                <?php if (has_slot('header_kiosk')):
                        include_slot('header_kiosk');
                      else:
                        include_slot('header');
                      endif;
                ?>
            </div>
        </div>
        <div class="content__body<?php if (has_slot('timetable_page')) echo ' content__body--timetable' ?>">
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
                <?php include_component('layout', 'lastUpdate', array('mode' => 'kiosk')); ?>
                Candle &copy; 2010,2011,2012 Martin Sucha. <?php echo link_to('Podmienky používania', '@terms_of_use'); ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>
