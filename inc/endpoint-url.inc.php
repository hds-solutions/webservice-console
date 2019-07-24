<!-- Endpoint URL -->
<div class="form-row">
    <div class="form-group col-9 col-md-4 col-xl-3">
        <select class="form-control" id="endpoint">
            <?php
                // foreach endpoints
                foreach ($config->endpoints as $endpoint => $edata)
                    // foreach endpoint.methods
                    foreach ($edata as $method => $data) {
                        // add separators
                        if ($method == 'separator') include 'inc/endpoint-separator.inc.php';
                        // add endpoint <option>
                        else include 'inc/endpoint-option.inc.php';
                    }
            ?>
        </select>
    </div>
    <div class="form-group col-3 d-flex d-md-none justify-content-end">
        <button class="btn btn-primary" id="send">SEND</button>
    </div>
    <div class="form-group col-12 col-md-6 col-xl-7">
        <input class="form-control" placeholder="/extra" id="extra"/>
    </div>
    <div class="form-group col-2 d-none d-md-flex justify-content-end">
        <button class="btn btn-primary" id="send">SEND</button>
    </div>
</div>
<!-- Request Token -->
<div class="form-row">
    <div class="form-group col-12">
        <input class="form-control" placeholder="&lt;token&gt;" readonly id="token"/>
    </div>
</div>