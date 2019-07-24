<div class="tab-pane col-12" id="<?=$method.'-'.$endpoint;?>">
    <?php
        // check for arguments
        if (isset($data->args))
            // load arguments container
            include 'inc/endpoint-args-container.inc.php';
        // // validate json data endpoint
        // if (isset($data->json) && $data->json === true)
        //     // load json input container
        //     include 'inc/endpoint-params-json.inc.php';
        // else {
        // }
    ?>
</div>