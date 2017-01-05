// declaramos la instancia
this.AgenciaModerna = this.AgenciaModerna || {};
// metodos genericos
(function($this, $, _) {
    $this.token = null;
    $this.url = $('#console').attr('action');
    $this.method = null;
    $this.endpoint = null;
    $this.extra = $('#extra');
    $this.output = $('#raw-output');
    $this.oSuccess = $('#output-success');
    $this.oCode = $('#output-code');
    $this.oError = $('#output-error');
    $this.oResult = $('#output-result');
    // init de la app
    $this.init = function() {
        $('#console').submit(function() {
            // cancel default submit
            return false;
        });
        // link tabs with select
        $('#endpoint').change(function() {
            // save values
            $this.method = $(this).find('>option').eq($(this).val()).attr('method');
            $this.endpoint = $(this).find('>option').eq($(this).val()).attr('endpoint');
            // check if extra
            $this.extra.removeAttr('class').addClass('form-control');
            if ($(this).find('>option').eq($(this).val()).attr('extra') !== 'true') $this.extra.addClass('hidden').val('');
            // fire show event
            $('#endpoint-tabs>li>a').eq($(this).val()).tab('show');
        }).val(0).change();
        //
        $('#extra').keyup(function(e) {
            // check for slash at start
            if (e.keyCode !== 8 && e.keyCode !== 9 && e.keyCode !== 46 && $(this).val().substr(0, 1) !== '/') $(this).val('/' + $(this).val());
        });
        //
        $('#pass').keydown(function(e) {
            if ((e.keyCode == 8 || e.keyCode == 46) && $(this).attr('type') == 'password') {
                $(this).val('').attr('type', 'text');
                $('.btn-hash.password').fadeIn();
            }
        });
        $('.btn-hash').each(function() {
            var $self = $(this);
            $self.click(function() {
                $('#pass').val(CryptoJS.MD5(Base64.encode($('#pass').val())).toString()).attr('type', 'password');
                $self.fadeOut();
            });
        });
        $('.field-wrapper+.crypt').each(function() {
            var $self = $(this),
                $field = $self.prev().find('>input'),
                $btn = $self.find('>button');
            $field.keydown(function(e) {
                if ((e.keyCode == 8 || e.keyCode == 46) && $field.prop('crypted') === true) {
                    $field.val('');
                    $btn.fadeIn();
                    $field.prop('crypted', false);
                }
            });
            $btn.click(function() {
                //
                var $crypted = $field.val(),
                    $types = $btn.attr('crypt').split(',');
                //
                for (var type in $types) {
                    switch ($types[type]) {
                        case 'md5':
                            $crypted = CryptoJS.MD5($crypted).toString();
                            break;
                        case 'base64':
                            $crypted = Base64.encode($crypted);
                            break;
                        default:
                            throw new Error('Unknown crypt option ' + $types[type]);
                    }
                }
                //
                $field.val($crypted).prop('crypted', true);
                //
                $btn.fadeOut();
            });
        });
        // send button
        $('#send').click(function() {
            _.lock();
            //
            $this.oSuccess.html('&nbsp;');
            $this.oCode.html('&nbsp;');
            $this.oError.html('&nbsp;');
            $this.oResult.html('&nbsp;');
            $this.output.html('&nbsp;');
            var $time = new Date();
            // build params
            var $request = {
                method: $this.method,
                url: $this.url + '/' + $this.endpoint + ($this.extra.val() != '' ? $this.extra.val() : ''),
                headers: {
                    'Authorization': $this.token
                },
                complete: function(xhr) {
                    // parse response
                    var $response = xhr.responseJSON !== undefined ? xhr.responseJSON : JSON.parse(xhr.responseText);
                    // set response data
                    $this.oSuccess.text($response.success ? 'true' : 'false');
                    $this.oCode.text($response.code);
                    $this.oError.text($response.error);
                    for (var i in $response) {
                        // ignore common elements
                        if (['success', 'code', 'error'].indexOf(i) !== -1) continue;
                        // add element to result
                        $this.oResult.append('<div class="row"><div class="col-md-2"><kbd>'+i+'</kbd></div><div class="col-md-10"><var id="output-result-'+i+'"></var></div></div>');
                        // show element value with JsonViewer
                        if ($response[i] !== null && typeof $response[i] === 'object') $('#output-result-'+i).jJsonViewer($response[i]);
                        // show element boolean value
                        else $('#output-result-'+i).html('<ul class="jjson-container"><li><span class="boolean">' + $response[i] + '</span></li></ul>');
                    }
                    // show raw response
                    $this.output.text(xhr.responseText);
                    // change panel color
                    $('#output-panel').attr('class', 'panel minheight-150 ' + ($response.success ? 'panel-success' : 'panel-danger'));
                    // empty headers
                    $('#response-headers').empty();
                    var $headers = xhr.getAllResponseHeaders().split("\n").sort();
                    for (var i in $headers) {
                        if ($headers[i] == '') continue;
                        var $header = $headers[i].split(':');
                        if (['Date', 'Content-Length', 'Authorization', 'Content-Type'].indexOf($header[0]) < 0) continue;
                        $('#response-headers').append('<div class="row"><div class="col-md-4"><kbd alt="'+$header[0]+'">'+$header[0]+'</kbd></div><div class="col-md-8"><var>'+$header[1]+'</var></div></div>');
                    }
                    // save new token
                    $this.token = xhr.getResponseHeader('Authorization');
                    if ($this.token !== null) {
                        $this.token = $this.token.split(';');
                        $this.token = $this.token[1] !== undefined ? $this.token[1] : $this.token[0];
                    }
                    // show current token
                    $('#token').val($this.token);
                    $time = new Date() - $time;
                    var $seconds = Math.floor($time / 1000);
                    $time -= $seconds * 1000;
                    // show request time
                    $('#response-headers').append('<div class="row"><div class="col-md-4"><kbd alt="Time">Time</kbd></div><div class="col-md-8"><var>'+$seconds+'s '+$time+'ms</var></div></div>');
                    // unlock UI
                    _.unlock();
                }
            };
            // remove authorization if we haven't a token
            if ($this.token === null) delete $request.headers;
            // foreach form fields to send
            $request.data = {};
            // ignore JSON data on GET requests
            if ($this.method !== 'GET')
                $('#endpoint-params .tab-pane.active textarea').each(function() {
                    // add content type to request
                    $request.contentType = 'application/json';
                    // add field
                    $request.data = JSON.stringify($(this).val());
                });
            // check for JSON post
            if ($request.contentType === undefined)
                // add default fields
                $('#endpoint-params .tab-pane.active input,#endpoint-params .tab-pane.active select').each(function() {
                    // check if contains data
                    if ($(this).val().toString().length > 0)
                        // add field value
                        $request.data[$(this).attr('id')] = $(this).attr('json') == 'true' ? JSON.parse($(this).val()) : $(this).val();
                });
            // check for DELETE request with data
            if ($this.method === 'DELETE' && Object.keys($request.data).length > 0) {
                // append data to request URI
                var $url = $request.url.split('?'),
                    $args = [],
                    $args_raw = $url[1] === undefined ? [] : $url[1].split('&');
                $url = $url[0];
                // parse args
                for (var i in $args_raw)
                    $args[$args_raw[i].split('=')[0]] = $args_raw[i].split('=')[1];
                // append data
                for (var i in $request.data)
                    $args[i] = $request.data[i];
                // add args to url
                $url += '?';
                for (var i in $args)
                    $url += i+'='+$args[i]+'&';
                // replace url un request
                $request.url = $url;
            }
            // send request
            $.ajax($request);
        });
        // eliminamos los metodos
        delete $this.init;
    };
}(AgenciaModerna, jQuery, WholeAuth));
// ejecutamos el proceso de la pagina
jQuery(AgenciaModerna.init);