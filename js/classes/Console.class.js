class Console {
    constructor() {
        // form
        this.form = document.querySelector('form');
        // endpoint
        this.endpoint = new Endpoint(this.form.querySelector('select[name="endpoint"]'));
        // extra arguments
        this.extra = this.form.querySelector('input[name="extra"]');
        // token
        this.token = this.form.querySelector('input[name="token"]');
        // endpoint tabs
        this.endpointTabs = document.querySelector('#endpoint-tabs');
        // capture hash buttons
        this.hashes = document.querySelectorAll('button[crypt]');
    }

    init() { return Promise.all([
        // execute local init
        this._init(),
        // init endpoint selector
        this.endpoint.init()
    ])}

    _init() { return new Promise((resolve, reject) => {
        // capture form submit
        this.form.addEventListener('submit', e => {
            // prevent default action
            e.preventDefault();
            // execute request
            this.request();
        });
        // set hash buttons events
        this.hashes.forEach(button => this._hashButton(button));
        // capture extra input change
        this.extra.addEventListener('keyup', e => {
            // check for slash at start
            if (e.keyCode !== 8 && e.keyCode !== 9 && e.keyCode !== 46 && this.extra.value.substr(0, 1) !== '/')
                // replace value, adding a slash
                this.extra.value = '/' + this.extra.value;
            // remove if only is a slash
            if (this.extra.value == '/') this.extra.value = '';
        });
        // capture endpointTabs change
        this.endpointTabs.change = tab => {
            // activate first args tab
            let argsTab = document.querySelector(tab.getAttribute('href')+' .nav-tabs .nav-item:first-child a.nav-link');
            // ignore if endpoint dont have args tabs
            if (argsTab === null) return;
            // activate args tab
            let ev = new ConsoleEvent('click');
            ev.fire(argsTab);
        };
        // capture endpoint change
        this.endpoint.change(value => {
            // de/activate extra field
            this.extra.classList.add('d-none');
            if (this.endpoint.extra) this.extra.classList.remove('d-none');
            // activate endpoint tab
            let tab = this.endpointTabs.querySelector('a[href="#'+value+'"]');
            let ev = new ConsoleEvent('click');
            ev.fire(tab);
            // redirect change
            this.endpointTabs.change(tab);
        });
    })}

    _hashButton(button) {
        // capture button click
        button.addEventListener('click', e => {
            // get parent input
            let input = button;
            do { input = input.parentNode;
            } while (input == null || !input.classList.contains('input-group'))
            input = input.querySelector('input');
            // check if input is already crypted
            if (input.readOnly) {
                // empty field value
                input.value = '';
                // enable input
                input.readOnly = false;
                // change button class
                button.classList.remove('btn-warning');
                button.classList.add('btn-outline-info');
                // stop cryption
                return;
            }
            // save current input value
            let crypted = input.value;
            // get encryption types
            let types = button.getAttribute('crypt').split(',');
            // validate types
            for (let type in types) {
                switch (types[type]) {
                    case 'md5': crypted = CryptoJS.MD5(crypted).toString();
                        break;
                    case 'base64': crypted = Base64.encode(crypted);
                        break;
                    default: throw new Error('Unknown crypt option ' + types[type]);
                }
            }
            // save encrypted value into output
            input.value = crypted;
            // disable input
            input.readOnly = true;
            // change button class
            button.classList.remove('btn-outline-info');
            button.classList.add('btn-warning');
        });
    }

    request() {
        // get current time
        let time = new Date();
        // build request params
        let request = {};
        // set method
        request.method = this.endpoint.method;
        // set base WS url
        request.url = this.form.getAttribute('action') + '/';
        // apend endpoint name
        request.url += this.endpoint.endpoint;
        // append extra
        request.url += !this.extra.classList.contains('d-none') ? this.extra.value : '';
        // append token
        if (this.token.value.length > 0) request.headers = { 'Authorization-Token': this.token.value };
        // build data
        request.data = {};
        // get active data form
        let endpointArgs = this.form.querySelector('#'+this.endpoint.value+' .tab-content .tab-pane.active');
        // foreach fields
        if (endpointArgs !== null) {
            // get args fields
            let fields = endpointArgs.querySelectorAll('input,select,textarea');
            // check for JSON payload
            if (fields.length == 1 && fields[0].name == 'json') {
                // set contenttype header
                request.contentType = 'application/json';
                // remplace data with JSON payload
                request.data = fields[0].value;
            } else {
                // foreach fields
                fields.forEach(field => {
                    // check if fields has null value
                    if (field.value === 'null')
                        // append field as null
                        request.data[field.name] = null;
                    // add only if field has value
                    else if (field.value.length > 0)
                        // append field value
                        request.data[field.name] = field.value;
                });
            }
            // check DELETE request with data
            if (this.endpoint.method == 'DELETE' && Object.keys(request.data).length > 0) {
                // append data to request URI
                let url = request.url.split('?'),
                    args = [],
                    args_raw = url[1] === undefined ? [] : url[1].split('&');
                url = url[0];
                // parse args
                for (let i in args_raw) args[args_raw[i].split('=')[0]] = args_raw[i].split('=')[1];
                // append data
                for (let i in request.data) args[i] = request.data[i];
                // add args to url
                url += '?'; for (let i in args) url += (i>0?'&':'')+i+'='+args[i];
                // replace request url with and params
                request.url = url;
            }
        }
        // capture request completition
        request.complete = xhr => {
            // empty again
            $('#request-output,#response-headers,#raw-output').empty();
            // parse response
            let response = xhr.responseJSON !== undefined ? xhr.responseJSON : JSON.parse(xhr.responseText);
            // foreach response data
            for (let i in response) {
                // ignore common elements
                // if (['success', 'code', 'error'].indexOf(i) !== -1) continue;
                // create response element
                let item = $(
                    '<div class="row my-1">'+
                        '<div class="col-3"><kbd>'+i+'</kbd></div>'+
                        '<div class="col-9 d-flex align-items-center"><var class="flex-fill"></var></div>'+
                    '</div>');
                // show element value with JsonViewer
                if (response[i] !== null && typeof response[i] === 'object') item.find('var').jJsonViewer(response[i]);
                // show element boolean value
                else item.find('var').html('<ul class="jjson-container"><li><span class="boolean">'+response[i]+'</span></li></ul>');
                // append item to output
                $('#request-output').append(item);
            }
            // show raw output
            $('#raw-output').text(xhr.responseText);
            // show response headers
            let headers = xhr.getAllResponseHeaders().split("\n").sort();
            for (let i in headers) {
                // ignore empty headers
                if (headers[i] == '') continue;
                // split name and value
                let header = headers[i].split(':');
                // ignore not known headers
                if (['Date', 'Content-Length', 'Authorization-Token', 'Content-Type'].indexOf(header[0]) < 0) continue;
                // add header
                $('#response-headers').append(
                    '<div class="row my-1">'+
                        '<div class="col-12 col-sm-4"><kbd>'+header[0]+'</kbd></div>'+
                        '<div class="col-12 col-sm-8"><var>'+header[1]+'</var></div>'+
                    '</div>');
            }
            $('.nav-link[href="#response-headers"]').click();
            // get new token
            let token = xhr.getResponseHeader('Authorization-Token');
            if (token !== null) {
                token = token.split(';');
                token = token[1] !== undefined ? token[1] : token[0];
            }
            // set token into form
            this.token.value = token;
            // calculate elapsed time
            time = new Date() - time;
            var seconds = Math.floor(time / 1000);
            time -= seconds * 1000;
            // show time in headers
            $('#response-headers').append(
                '<div class="row my-1">'+
                    '<div class="col-12 col-sm-4"><kbd>Time</kbd></div>'+
                    '<div class="col-12 col-sm-8"><var>'+seconds+'s '+time+'ms</var></div>'+
                '</div>');
            // update output panel
            document.querySelector('.card#output').classList.remove('card-success');
            document.querySelector('.card#output').classList.remove('card-danger');
            document.querySelector('.card#output').classList.add('card-'+(response.success?'success':'danger'));
        };
        // empty response outputs
        $('#request-output,#response-headers,#raw-output').empty();
        // execute request
        $.ajax(request);
    }
}