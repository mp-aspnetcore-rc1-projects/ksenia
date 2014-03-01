var Observable = function (data) {
    if (!(this instanceof Observable)) {
        return new Observable(data);
    }
    var $notifier = $({});
    var $data = {};
    this.on = $notifier.on.bind($notifier);
    this.off = $notifier.off.bind($notifier);
    this.trigger = $notifier.trigger.bind($notifier);
    this.model = function (key, value) {
        if (value !== undefined) {
            var old = $data[key];
            var _new = $data[key] = value;
            return this.trigger("change", [old, _new, key]);
        } else {
            return $data[key];
        }
    };
    this.register = function (key, value) {
        Object.defineProperty(this, key, {
            get: function () {
                return this.model(key);
            },
            set: function (value) {
                return this.model(key, value);
            }
        });
        if (value) {
            this[key] = value;
        }
    };
    Object.keys(data).forEach(function (key) {
        if (data.hasOwnProperty(key)) {
            this.register(key, data[key]);
        }
    }, this);
};