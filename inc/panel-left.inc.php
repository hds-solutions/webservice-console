<div class="col-12 col-xl-6">
    <div class="card card-info">
        <div class="card-header">
            <h3 class="card-title"><?=$config->title;?> Webservice Console</h3>
        </div>
        <div class="card-body">
            <form action="<?=$config->url;?>">
                <?php
                    // endpoint url
                    require_once 'inc/endpoint-url.inc.php';
                    // endpoint params
                    require_once 'inc/endpoint-params.inc.php'
                ?>
           </form>
        </div>
        <div class="card-footer">
            <div class="row">
                <ul class="nav nav-tabs col-12" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#request-headers" data-toggle="tab">Request Headers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#response-headers" data-toggle="tab">Response Headers</a>
                    </li>
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane panel-body col-12 py-3 active" id="request-headers">
                    <div class="row my-1">
                        <div class="col-12 col-sm-4"><kbd>Content-Type</kbd></div>
                        <div class="col-12 col-sm-8"><var>form/url-encoded</var></div>
                    </div>
                </div>
                <div class="tab-pane panel-body col-12 py-3" id="response-headers">
                    <div class="row my-1">
                        <div class="col-12 col-sm-4"><kbd>Content-Type</kbd></div>
                        <div class="col-12 col-sm-8"><var>application/json</var></div>
                    </div>
                    <div class="row my-1">
                        <div class="col-12 col-sm-4"><kbd>Content-Lenght</kbd></div>
                        <div class="col-12 col-sm-8"><var>2442</var></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>