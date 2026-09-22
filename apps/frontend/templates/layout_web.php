<?php
use_helper('Candle');

// Panel s vyhľadávaním: stránka si ho môže nastaviť sama (napr. editor rozvrhu
// pridáva zaškrtávacie políčka), inak sa použije bežný panel s vyhľadávaním.
$panelText = get_slot('panel');
if (!$panelText) {
    $panelText = get_component('panel', 'panel');
}
// ak sa vyhľadávalo formulárom bez javascriptu, panel sa zobrazí hneď vysunutý
$panelOpen = candle_panel_search_requested($sf_request);
?><!DOCTYPE html>
<html lang="sk">
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
<body>
<div class="main_wraper">
    <div class="overlay<?php if ($panelOpen) echo ' overlay--visible' ?>" id="overlay"></div>

    <div class="sidebar<?php if ($panelOpen) echo ' sidebar--expanded' ?>" id="sidebar">
        <div class="sidebar__visible">
            <a class="sidebar__logo_link" href="<?php echo url_for('@homepage') ?>" title="Rozvrh - úvodná stránka"><img class="sidebar__logo" src="<?php echo image_path('logo.svg') ?>" alt="Candle" /></a>
            <ul class="sidebar__elements">
                <li class="sidebar__element">
                    <button type="button" class="sidebar__toggle" id="sidebar__toggle"
                            title="Vyhľadávanie a nástroje"
                            aria-controls="sidebar__container" aria-expanded="<?php echo $panelOpen ? 'true' : 'false' ?>">
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>
                        <i class="fa fa-arrow-left" aria-hidden="true"></i>
                        <i class="fa fa-bars" aria-hidden="true"></i>
                        <span class="pristupnost">Zobraziť/schovať panel s vyhľadávaním</span>
                    </button>
                </li>
            </ul>
        </div>
        <div class="sidebar__container<?php if ($panelOpen) echo ' sidebar__container--expanded' ?>" id="sidebar__container">
            <button type="button" class="sidebar__close" id="sidebar__close" title="Schovať panel"><i class="fa fa-times" aria-hidden="true"></i><span class="pristupnost">Schovať panel</span></button>
            <?php echo $panelText ?>
            <section class="sidebar__block sidebar__block--links">
                <h2 class="pristupnost">Linky</h2>
                <ul>
                    <li><a href="https://github.com/fmfi-svt/candle">Zoznam chýb</a></li>
                    <li><a href="https://github.com/fmfi-svt/candle/wiki">Dokumentácia</a></li>
                    <li><a href="https://github.com/fmfi-svt/candle/wiki/FAQ">FAQ</a></li>
                    <li><a href="http://groups.google.com/group/candle-users">Mailing list</a></li>
                    <li><?php echo link_to('Podmienky používania', '@terms_of_use'); ?></li>
                </ul>
            </section>
        </div>
    </div>

    <div class="content">
        <div class="content__header">
            <div class="content__header_name">
                <?php include_slot('header') ?>
            </div>
            <div class="content__header_controls">
                <div class="content__header_user_row">
                    <?php include_component('user', 'menu'); ?>
                </div>
                <?php if (has_slot('actions')): ?>
                <nav class="content__header_controls_row">
                    <?php include_slot('actions') ?>
                </nav>
                <?php endif; ?>
            </div>
        </div>
        <?php include_slot('top') ?>
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
                <?php include_component('layout', 'lastUpdate', array('mode' => 'normal')); ?>
                <span class="disclaimer2">
                Aplikácia Candle Copyright 2010,2011,2012 Martin Sucha. Zdrojové kódy sa nachádzajú na
                <a href="https://github.com/fmfi-svt/candle">stránke projektu</a>.
                Táto aplikácia je študentský projekt a nie je oficiálne podporovaná
                pracovníkmi CIT, všetky prípadné otázky smerujte na diskusnú skupinu
                <a href="http://groups.google.com/group/candle-users">candle-users</a>,
                časté odpovede nájdete v sekcii <a href="https://github.com/fmfi-svt/candle/wiki/FAQ">FAQ</a>.
                Používaním služby súhlasíte s <?php echo link_to('podmienkami používania', '@terms_of_use'); ?></span>
            </div>
        </div>
    </div>
</div>
</body>
</html>
