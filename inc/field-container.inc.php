<div class="form-group row">
    <label class="col-12 col-md-3 col-form-label"><?=$field;?></label>
    <div class="input-group col-12 col-md-9">
        <?php
            // check if field has fixed options
            if (isset($data->select) && isset($data->select->$endpoint_args) && isset($data->select->$endpoint_args->$field))
                // show field as <select>
                include 'inc/field-select.inc.php';
            else
                // show field as <input>
                include 'inc/field-input.inc.php';
            // check if field has encryption
            if (isset($data->encrypt) && isset($data->encrypt->$endpoint_args) && isset($data->encrypt->$endpoint_args->$field))
                // load field encryption button
                include 'inc/encryption-button.inc.php';
        ?>
    </div>
</div>