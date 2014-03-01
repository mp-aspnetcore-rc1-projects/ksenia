/*global jQuery*/
/**
 * IMAGE MANAGEMENT SCRIPT FOR project-read route
 * @2013 mparaiso
 * follows the mvc pattern
 * with the controller distributed between the mediator and command
 */
"use strict";
jQuery(function ($) {
    var model, view, command, mediator;
    mediator = $({}).on({
        'click.image.remove': function (e, image) {
            command.deleteImage.execute(image);
        },
        'click.image.publish': function (e, image) {
            command.publishImage.execute(image);
            //command.swapPublishState.execute(image);
        },
        'success.image.remove': function (e, data) {
            console.log('success', arguments);
        },
        'success.image.publish': function (e, data,image) {
            console.log("success publish");
            command.renderImageView.execute(data.image);
        }
    });
    command = {
        renderImageView: {
            execute: function (imageData) {
                console.log(arguments);
                var $view,$button;
                if(imageData.id){
                    $view = $("[data-role='image']").filter("[data-id="+imageData.id+"]");
                    $button= $view.find("button[data-role='image-publish']");
                    if($button){
                        $button.text(imageData.isPublished?model.text.unpublish:model.text.publish);
                    }
                }
            }
        },
        publishImage: {
            execute: function (image) {
                $.ajax({
                    method: 'POST',
                    url: image.data('publishUrl'),
                    success: function (d) {
                        mediator.trigger('success.image.publish', [d, image]);
                    }
                });
            }
        },
        deleteImage: {
            execute: function (image) {
                $.ajax({url: image.data('url'), method: "DELETE", success: function (data) {
                    mediator.trigger('success.image.remove', [data]);
                }});
                image.fadeOut('slow', function () {
                    image.remove();
                });
            }
        }
    };
    view = {
        images: $("[data-role='image']").each(function () {
            var $this = $(this);
            $this.data('url', $this.find('form[data-role="image-delete"]').attr('action'));
            $this.data('publishUrl', $this.find('form[data-role="image-publish"]').attr('action'));
            $this.on('click', 'button[data-role="image-publish"]', function (e) {
                mediator.trigger('click.image.publish', [$this]);
                e.stopPropagation();
                e.preventDefault();
            });
            $this.on('click', 'button[data-role="image-delete"]', function (e) {
                var url = $(this).parent('form').attr('action');
                mediator.trigger('click.image.remove', [$this]);
                e.stopPropagation();
                e.preventDefault();
                return false;
            });
            return $this;
        })
    };
    model = new Observable({
        images: [],
        text: {
            publish: "Publish",
            unpublish: "UnPublish"
        }
    });

    (function init() {
        view.images.each(function () {
            var $this = $(this);
        });
    }());
})
;