<html class="panel_hidden">
<head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <title>Chyba</title>
    <?php include_stylesheets() ?>

    <!--[if lte IE 7]>
    <?php echo stylesheet_tag('main_ie7'); ?>
    <![endif]-->
</head>
<body class="panel_hidden">
    <div id="hlavny">
    <div id="obsah_wrap">
        <div id="obsah_vrch">
            <div id="obsah_vrch_lavy">
                <div id="obsah_in">
                    <h1>Chyba</h1>
                    Pri spracovaní požiadavky nastala chyba.
                    <?php echo link_to('Prejsť na hlavnú stránku', '@homepage'); ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>