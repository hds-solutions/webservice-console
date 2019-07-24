<select id="<?=$field;?>" class="form-control">
    <option></option>
    <?php foreach ($data->select->$endpoint_args->$field as $option) { ?>
    <option value="<?=$option;?>"><?=$option;?></option>
    <?php } ?>
</select>