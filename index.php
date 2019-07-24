<?php
    // console version
    define('VERSION', '1.2.14');
    // debug flags
    ini_set('display_errors', true);
    error_reporting(E_ALL);
    // load console json file
    $config =   file_exists('console.config') ?
                json_decode(file_get_contents('console.config')) :
                null;
    if ($config === null) $config = (object)[ 'title' => 'Unconfigured', 'endpoints' => [] ];
?><!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">
        <title><?=$config->title?> Webservice Console</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
        <link rel="stylesheet" href="css/vendor/jquery.jsonviewer.min.css" />
        <link rel="stylesheet" href="inc/wholeauth/src/net/hds-solutions/wholeauth/css/wholeauth-wk.min.css"></link>
        <link rel="stylesheet" href="inc/wholeauth/src/net/hds-solutions/wholeauth/css/alertify.core.min.css"></link>
        <link rel="stylesheet" href="inc/wholeauth/src/net/hds-solutions/wholeauth/css/alertify.default.min.css"></link>
        <link rel="stylesheet" href="css/style.css?v=<?=VERSION;?>" />
    </head>
    <body>
        <div class="container-fluid mt-3">
            <div class="row">
                <?php
                    // left panel
                    require_once 'inc/panel-left.inc.php';
                    // right panel
                    require_once 'inc/panel-right.inc.php';
                ?>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="js/vendor/jquery.actual.min.js"></script>
        <script src="js/vendor/jquery.jsonviewer.min.js"></script>
        <script src="js/vendor/base64.min.js"></script>
        <script src="js/vendor/cryptojs.md5.min.js"></script>
        <script src="inc/wholeauth/src/net/hds-solutions/wholeauth/js/wholeauth.min.js"></script>
        <script src="inc/wholeauth/src/net/hds-solutions/wholeauth/js/wholeauth-wk.min.js"></script>
        <script src="inc/wholeauth/src/net/hds-solutions/wholeauth/js/wholeauth-alertify.min.js"></script>
        <script src="js/classes/ConsoleEvent.class.js?v=<?=VERSION;?>"></script>
        <script src="js/classes/Console.class.js?v=<?=VERSION;?>"></script>
        <script src="js/classes/Endpoint.class.js?v=<?=VERSION;?>"></script>
        <script src="js/libs.js?v=<?=VERSION;?>"></script>
        <script src="js/main.js?v=<?=VERSION;?>"></script>
    </body>
</html>