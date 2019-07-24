class Endpoint {
    constructor(element) {
        // save element
        this.element = element;
        // method
        this.method = null;
        // endpoint
        this.endpoint = null;
        // extra data
        this.extra = false;
        // selected value
        this.value = null;
        // events
        this.events = {
            change: _ => {}
        };
    }

    init() { return new Promise((resolve, reject) => {
        // capture endpoint change
        this.element.addEventListener('change', e => {
            // get selected endpoint
            let selected = this.element.querySelector('option:checked');
            // update method, endpoint and extra flag
            this.method = selected.getAttribute('method');
            this.endpoint = selected.getAttribute('endpoint');
            this.extra = selected.getAttribute('extra') == 'true';
            // save value
            this.value = selected.value;
            // execute change event
            this.change(selected.value);
        });
        // fire change event
        if ('createEvent' in document) {
            let event = document.createEvent('HTMLEvents');
            event.initEvent('change', false, true);
            this.element.dispatchEvent(event);
        } else this.element.fireEvent('onchange');
        // resolve
        resolve();
    })}

    change(fn) {
        // save function
        if (typeof fn == 'function') this.events.change = fn;
        // execute change event
        else this.events.change(fn);
        // return object
        return this;
    }
}