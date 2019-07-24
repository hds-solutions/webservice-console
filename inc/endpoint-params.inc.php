<div class="row d-none">
    <ul class="nav nav-tabs" role="tablist" id="endpoint-tabs">
        <?php
            // foreach endpoints
            foreach ($config->endpoints as $endpoint => $methods)
                // foreach endpoint.methods
                foreach ($methods as $method => $data)
                    // ignore separators
                    if ($method !== 'separator')
                        // show method/endpoint selector
                        include 'inc/endpoint-tab.inc.php';
        ?>
    </ul>
</div>
<div class="row tab-content">
    <?php
        // foreach endpoints
        foreach ($config->endpoints as $endpoint => $methods)
            // foreach methods
            foreach ($methods as $method => $data)
                // ignore separators
                if ($method !== 'separator')
                    // load endpoint params panel
                    include 'inc/endpoint-params-panel.inc.php';
    ?>
</div>