<?php
    $panelText = get_slot('panel');
?><!DOCTYPE html>
<html class="<?php if(!$panelText) echo 'panel_hidden'?>">
<head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <title><?php if (include_slot('title')) echo ' - ' ?>Rozvrh pre FMFI UK</title>
    <link rel="shortcut icon" href="/favicon.ico" />
    <script type="text/javascript">
        var candleFrontendRelativeUrl = "<?php echo url_for('@homepage');?>";
        var candleFrontendAbsoluteUrl = "<?php echo url_for('@homepage', true);?>";
        var candleFrontendDomain = "<?php echo $sf_request->getHost();?>";
    </script>
    <?php include_stylesheets() ?>

    <!--[if lte IE 7]>
    <?php echo stylesheet_tag('main_ie7'); ?>
    <![endif]-->

    <?php include_javascripts() ?>
    <?php include_component('layout', 'analytics') ?>
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

                    <div class="footer"

                        <hr />

                        <?php include_component('layout', 'lastUpdate'); ?>
                        <br />
                        Aplikácia Candle Copyright 2010,2011 Martin Sucha. <span class="disclaimer2">Zdrojové kódy sa nachádzajú na
                        <a href="https://ne.st.dcs.fmph.uniba.sk/projects/candle">stránke projektu</a>.
                        Táto aplikácia je študentský projekt a nie je oficiálne podporovaná
                        pracovníkmi CIT, všetky prípadné otázky smerujte priamo na <a href="mailto:sucha14@st.fmph.uniba.sk">autora stránky</a>,
                        časté odpovede nájdete v sekcii <a href="https://ne.st.dcs.fmph.uniba.sk/projects/candle/wiki/FAQ">FAQ</a>.
                        Používaním služby súhlasíte s <?php echo link_to('podmienkami používania', '@terms_of_use'); ?></span>
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
        <h2 class="pristupnost">Linky</h2>
        <ul><!--
            --><li><?php echo link_to('Rozvrh', '@homepage', array('class'=>'selected')) ?></li><!--
            --><li><a href="https://ne.st.dcs.fmph.uniba.sk/projects/candle">Zoznam chýb</a></li><!--
            --><li><a href="https://ne.st.dcs.fmph.uniba.sk/projects/candle/wiki">Dokumentácia</a></li><!--
            --><li><a href="https://ne.st.dcs.fmph.uniba.sk/projects/candle/wiki/FAQ">FAQ</a></li><!--
        --></ul>
    </div>    
</div>
</body>
</html>
