/*jslint browser:true,nomen:true,unparam:true,white:true*/
/*global $*/
(function (exports) {
    "use strict";
    var Observable = function (data) {
        var $notifier, $data, projectectProperties;
        if (!(this instanceof Observable)) {
            return new Observable(data);
        }
        $notifier = $({});
        $data = {};
        $.extend(this, $notifier);
        projectectProperties = Object.keys(this);
        this.model = function (key, value) {
            var old, _new;
            if (value !== undefined) {
                old = $data[key];
                _new = $data[key] = value;
                return this.trigger("change", [old, _new, key]);
            }
            return $data[key];
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
        projectectProperties.forEach(function (p) {
            Object.defineProperty(this, p, {value: this[p], writable: false});
        }, this);
        Object.keys(data).forEach(function (key) {
            if (data.hasOwnProperty(key)) {
                this.register(key, data[key]);
            }
        }, this);
    };
    exports.Observable = Observable;
}(window || this));
