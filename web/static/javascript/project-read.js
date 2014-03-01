/*global jQuery*/
"use strict";
jQuery(function ($) {

    function deletify($jQObject) {
        $jQObject.each(function (el) {
            var $this = $(this),
                $form;
            $this.data('title', $('[data-title]', $this).first().text());
            $form = $this.find('form');
            $this.data('url', $form.attr('action'));
            //on submit confirm deletion
            $form.on('click', 'button[type=submit]', function (event) {
                $this.fadeOut(function () {
                    $this.remove();
                    $.ajax({
                        method: "DELETE",
                        url: $this.data('url'),
                        success: function () {
                            console.log([$this.data('title'), "removed."].join(' '));
                        }
                    });
                });
                event.preventDefault();
                event.stopPropagation();
                return false;
            });
        });
        return $jQObject;
    }

    (function init() {
        var $images;
        //get all images
        $images = $('[data-role=image]');
        deletify($images);
    }());
});