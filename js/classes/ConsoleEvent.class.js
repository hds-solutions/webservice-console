/**
 * Fire an event handler to the specified node. Event handlers can detect that the event was fired programatically
 * by testing for a 'synthetic=true' property on the event object
 * @param {HTMLNode} node The node to fire the event handler on.
 * @param {String} eventName The name of the event without the "on" (e.g., "focus")
 */
class ConsoleEvent {
    constructor(name) {
        // save event name
        this.eventName = name;
    }

    fire(element) {
        // Make sure we use the ownerDocument from the provided node to avoid cross-window problems
        let doc;
        if (element.ownerDocument)
            doc = element.ownerDocument;
        else if (element.nodeType == 9)
            // the node may be the document itself, nodeType 9 = DOCUMENT_NODE
            doc = element;
        else
            throw new Error("Invalid node passed to fireEvent: " + element.id);

         if (element.dispatchEvent) {
            // Gecko-style approach (now the standard) takes more work
            let eventClass = "";

            // Different events have different event classes.
            // If this switch statement can't map an eventName to an eventClass,
            // the event firing is going to fail.
            switch (this.eventName) {
                case "click": // Dispatching of 'click' appears to not work correctly in Safari. Use 'mousedown' or 'mouseup' instead.
                case "mousedown":
                case "mouseup":
                    eventClass = "MouseEvents";
                    break;

                case "focus":
                case "change":
                case "blur":
                case "select":
                    eventClass = "HTMLEvents";
                    break;

                default:
                    throw "fireEvent: Couldn't find an event class for event '" + this.eventName + "'.";
                    break;
            }
            // create event
            let event = doc.createEvent(eventClass);

            let bubbles = this.eventName == "change" ? false : true;
            event.initEvent(this.eventName, bubbles, true); // All events created as bubbling and cancelable.

            event.synthetic = true; // allow detection of synthetic events
            element.dispatchEvent(event, true);
        } else  if (element.fireEvent) {
            // IE-old school style
            let event = doc.createEventObject();
            event.synthetic = true; // allow detection of synthetic events
            element.fireEvent('on'+this.eventName, event);
        }
    }
}