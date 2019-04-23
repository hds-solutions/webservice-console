<?php
    // cargamos las configuraciones
    $config =   file_exists('console.config') ?
                json_decode(file_get_contents('console.config')) : null;
    // default config
    if ($config == null) $config = (object)[ 'title' => 'Unconfigured', 'endpoints' => [] ];
?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <title><?=$config->title?> Webservice Console</title>
        <link rel="stylesheet" href="css/vendor/bootstrap.min.css" />
        <link rel="stylesheet" href="css/vendor/jquery.jsonviewer.min.css" />
        <link rel="stylesheet" href="inc/wholeauth/src/net/hds-solutions/wholeauth/css/wholeauth-wk.min.css"></link>
        <link rel="stylesheet" href="inc/wholeauth/src/net/hds-solutions/wholeauth/css/alertify.core.min.css"></link>
        <link rel="stylesheet" href="inc/wholeauth/src/net/hds-solutions/wholeauth/css/alertify.default.min.css"></link>
        <link rel="stylesheet" href="css/style.min.css" />
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?=$config->title?> Webservice Console</h3>
                        </div>
                        <div class="panel-body">
                            <form action="<?=$config->url?>" class="form-horizontal" id="console">
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Endpoint</label>
                                        <div class="col-md-3">
                                            <select class="form-control" id="endpoint">
                                                <option value="0" method="GET" endpoint="login" extra="false" selected="selected">GET/login</option>
                                                <option value="1" method="POST" endpoint="login" extra="false">POST/login</option>
                                                <option value="2" method="DELETE" endpoint="login" extra="false">DELETE/login</option>
                                                <?php $endpointno = 3; foreach ($config->endpoints as $endpoint => $edata) { foreach ($edata as $method => $data) { if ($method == 'separator') { ?>
                                                <option disabled></option>
                                                <?php } else { ?>
                                                <option value="<?=$endpointno;?>" method="<?=$method;?>" endpoint="<?=$endpoint;?>" extra="<?=(isset($data->extra)&&$data->extra?'true':'false');?>"><?=$method.'/'.$endpoint;?></option>
                                                <?php } $endpointno++; }} ?>
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <input class="form-control" placeholder="/extra" id="extra"/>
                                        </div>
                                        <div class="col-md-2">
                                            <button class="btn btn-primary" id="send">SEND</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <label class="col-md-2 control-label">Token</label>
                                        <div class="col-md-8">
                                            <input class="form-control" placeholder="&lt;token&gt;" readonly="readonly" id="token"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="row hidden">
                                    <ul class="nav nav-tabs" role="tablist" id="endpoint-tabs">
                                        <li><a href="#get-login" data-toggle="tab">GET/login</a></li>
                                        <li><a href="#post-login" data-toggle="tab">POST/login</a></li>
                                        <li><a href="#delete-login" data-toggle="tab">DELETE/login</a></li>
                                        <?php foreach ($config->endpoints as $endpoint => $edata) foreach ($edata as $method => $data) { ?>
                                        <li><a href="#<?=strtolower($method).'-'.$endpoint;?>" data-toggle="tab"><?=$method.'/'.$endpoint;?></a></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <div class="tab-content form-horizontal" id="endpoint-params">
                                    <div class="tab-pane active" id="get-login"></div>
                                    <div class="tab-pane" id="post-login">
                                        <div class="row">
                                            <ul class="nav nav-tabs" role="tablist" id="endpoint-args">
                                                <li class="active"><a href="#POST-login" data-toggle="tab">POST/login</a></li>
                                            </ul>
                                        </div>
                                        <div class="tab-content" id="endpoint-args2">
                                            <div class="tab-pane panel-body active" id="POST-login">
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">user</label>
                                                        <div class="col-md-6">
                                                            <input id="user" type="text" class="form-control" placeholder="user"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label">pass</label>
                                                        <div class="col-md-6">
                                                            <input id="pass" type="text" class="form-control" placeholder="pass"/>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <button class="btn btn-warning btn-xs btn-hash password">HASH</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="delete-login"></div>
                                    <?php foreach ($config->endpoints as $endpoint => $edata) { foreach ($edata as $method => $data) { if ($method == 'separator') continue; ?>
                                    <div class="tab-pane" id="<?=strtolower($method).'-'.$endpoint;?>">
                                        <?php if (isset($data->json) && $data->json === true) { ?>
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="col-md-3 col-md-offset-1 control-label align-left">JSON Data</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group">
                                                <div class="col-md-9 col-md-offset-1">
                                                    <textarea id="json" class="form-control" placeholder="json"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } else { if (isset($data->args)) { $flag = false; foreach ($data->args as $name => $arg) { if (gettype($arg) == 'array') $flag = true; } if ($flag) { ?>
                                        <div class="row">
                                            <ul class="nav nav-tabs" role="tablist" id="endpoint-args">
                                                <?php $first = true; foreach ($data->args as $name => $arg) { ?>
                                                <li<?=$first?' class="active"':''?>><a href="#<?=strtolower($method).'-'.$endpoint.'_'.substr(md5($name), 8, 8)?>" data-toggle="tab"><?=$name?></a></li>
                                                <?php $first = false; } ?>
                                            </ul>
                                        </div>
                                        <div class="tab-content" id="endpoint-args2">
                                            <?php } $first = true; foreach ($data->args as $name => $arg) { if (gettype($arg) == 'array') { ?>
                                            <div class="tab-pane panel-body<?=$first?' active':'';?>" id="<?=strtolower($method).'-'.$endpoint.'_'.substr(md5($name), 8, 8);?>">
                                                <?php $first = false; foreach ($arg as $cArg) { ?>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label class="col-md-3 control-label"><?=$cArg;?></label>
                                                        <div class="col-md-6 field-wrapper">
                                                        <?php if (isset($data->select) && isset($data->select->$name) && isset($data->select->$name->$cArg)) { ?>
                                                            <select id="<?=$cArg; ?>" class="form-control">
                                                                <option></option>
                                                                <?php foreach ($data->select->$name->$cArg as $option) { ?>
                                                                <option value="<?=$option;?>"><?=$option;?></option>
                                                                <?php } ?>
                                                            </select>
                                                            <?php } else { ?>
                                                            <input id="<?=$cArg;?>" type="text" class="form-control" placeholder="<?=$cArg;?>"/>
                                                            <?php } ?>
                                                        </div>
                                                        <?php if (isset($data->encrypt) && isset($data->encrypt->$name) && isset($data->encrypt->$name->$cArg)) { ?>
                                                        <div class="col-md-3 crypt">
                                                            <button class="btn btn-warning btn-xs" crypt="<?=implode(',', $data->encrypt->$name->$cArg);?>"><?=implode(' + ', $data->encrypt->$name->$cArg);?></button>
                                                        </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                            </div>
                                        <?php } else { ?>
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="col-md-3 control-label"><?=$arg;?></label>
                                                <div class="col-md-6 field-wrapper">
                                                <?php if (isset($data->select) && isset($data->select->$arg)) { ?>
                                                    <select id="<?=$arg;?>" class="form-control">
                                                        <option></option>
                                                        <?php foreach ($data->select->$arg as $option) { ?>
                                                        <option value="<?=$option; ?>"><?=$option;?></option>
                                                        <?php } ?>
                                                    </select>
                                                    <?php } else { ?>
                                                    <input id="<?=$arg;?>" type="text" class="form-control" placeholder="<?=$arg;?>"/>
                                                    <?php } ?>
                                                </div>
                                                <?php if (isset($data->encrypt) && isset($data->encrypt->$arg)) { ?>
                                                <div class="col-md-3 crypt">
                                                    <button class="btn btn-warning btn-xs" crypt="<?=implode(',', $data->encrypt->$arg);?>"><?=implode(' + ', $data->encrypt->$arg);?></button>
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <?php }} if ($flag) { ?>
                                            </div>
                                            <?php }}} ?>
                                    </div>
                                    <?php }} ?>
                                </div>
                           </form>
                        </div>
                        <div class="panel-footer">
                            <div class="row hidden">
                                <ul class="nav nav-tabs" role="tablist" id="headers-tabs">
                                    <li><a href="#request-headers" data-toggle="tab">Request Headers</a></li>
                                    <li><a href="#response-headers" data-toggle="tab">Response Headers</a></li>
                                </ul>
                            </div>
                            <div class="tab-content form-horizontal">
                                <div class="tab-pane" id="request-headers"></div>
                                <div class="tab-pane active" id="response-headers"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-success minheight-150" id="output-panel">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Output</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-2"><kbd>success</kbd></div>
                                        <div class="col-md-10"><var id="output-success">true | false</var></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2"><kbd>code</kbd></div>
                                        <div class="col-md-10"><var id="output-code">&lt;int&gt;</var></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2"><kbd>error</kbd></div>
                                        <div class="col-md-10"><var id="output-error">&lt;string&gt;</var></div>
                                    </div>
                                    <div id="output-result"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-info minheight-150">
                                <div class="panel-heading">
                                    <h3 class="panel-title">RAW Output</h3>
                                </div>
                                <div class="panel-body">
                                    <samp id="raw-output"><?php echo json_encode([ 'success' => false, 'code' => 16, 'error' => 'unknown' ]); ?></samp>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="js/vendor/bootstrap.min.js"></script>
        <script src="js/vendor/jquery.actual.min.js"></script>
        <script src="js/vendor/jquery.jsonviewer.min.js"></script>
        <script src="js/vendor/base64.min.js"></script>
        <script src="js/vendor/cryptojs.md5.min.js"></script>
        <script src="inc/wholeauth/src/net/hds-solutions/wholeauth/js/wholeauth.min.js"></script>
        <script src="inc/wholeauth/src/net/hds-solutions/wholeauth/js/wholeauth-wk.min.js"></script>
        <script src="inc/wholeauth/src/net/hds-solutions/wholeauth/js/wholeauth-alertify.min.js"></script>
        <script src="js/main.min.js"></script>
    </body>
</html>