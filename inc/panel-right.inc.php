<div class="col-12 col-xl-6">
    <div class="row">
        <div class="col-12">
            <div class="card pt-3 pt-xl-0">
                <div class="card-header">
                    <h3 class="card-title">Output</h3>
                </div>
                <div class="card-body">
                    <div class="row my-1">
                        <div class="col-3"><kbd>success</kbd></div>
                        <div class="col-9"><var>true | false</var></div>
                    </div>
                    <div class="row my-1">
                        <div class="col-3"><kbd>code</kbd></div>
                        <div class="col-9"><var>&lt;int&gt;</var></div>
                    </div>
                    <div class="row my-1">
                        <div class="col-3"><kbd>error</kbd></div>
                        <div class="col-9"><var>&lt;string&gt;</var></div>
                    </div>
                    <div></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">RAW Output</h3>
                </div>
                <div class="card-body">
                    <samp id="raw-output"><?php echo json_encode([ 'success' => false, 'code' => 16, 'error' => 'unknown' ]); ?></samp>
                </div>
            </div>
        </div>
    </div>
</div>