<div class="tab-pane panel-body col-12" id="<?=$method.'-'.$endpoint.'_'.substr(md5($endpoint_args), 8, 8);?>">
    <div class="form-group row pt-3">
        <label class="col-3 col-form-label">JSON Data</label>
        <div class="col-9">
            <textarea name="json" class="form-control" placeholder="JSON Payload"></textarea>
        </div>
    </div>
</div>