<div class="tab-pane panel-body col-12" id="<?=$method.'-'.$endpoint.'_'.substr(md5($endpoint_args), 8, 8);?>">
    <?php
        // foreach endpoint args fields
        foreach ($args as $field)
            // show field container
            include 'inc/field-container.inc.php';
    ?>
</div>