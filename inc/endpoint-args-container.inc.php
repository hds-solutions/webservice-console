<!-- Endpoint Arguments nav container -->
<div class="row">
    <ul class="nav nav-tabs col-12" role="tablist">
        <?php
            // foreach args endpoints
            foreach ($data->args as $endpoint_args => $args)
                // show endpoint.args nav item
                include 'inc/endpoint-args-nav.inc.php';
        ?>
    </ul>
</div>
<!-- Endpoint Arguments container -->
<div class="row tab-content pt-3">
    <?php
        // foreach args endpoints
        foreach ($data->args as $endpoint_args => $args)
            // show endpoint.args container
            include 'inc/endpoint-args-tab-content.inc.php';
    ?>
</div>