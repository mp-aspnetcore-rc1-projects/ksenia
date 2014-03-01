/*global jQuery,FormData,FileReader*/
"use strict";
/**
 * IMAGE UPLOAD SCRIPT
 * @copyrights mparaiso <mparaiso@online.fr>
 * @dependency jquery
 * @dependency jquery-observable
 * follows the MVC pattern
 * - the mediator get events from the view and execute commands
 * - the model hold datas
 * - the view represent the DOM widgets
 * - the commands regroup a set of generic actions. The client doesnt need to know
 * who will recieve the command action.
 */
jQuery(function ($) {
    var mediator, model, command, view;
    //noinspection FunctionWithInconsistentReturnsJS
    /**
     * model
     * @type {Object}
     */
    model = Observable({
        progress:0,
        dropText: "Drop Some Image Files Here",
        uploadingText:"Uploading...",
        doneText:"Done",
        formData: null,
        url: null,
        files: [],
        uploadTemplate: "\
        <div class='col-md-3 upload-item' \
        style='overflow:hidden;height:150px;padding:5px'> \
        <img style='height:100%'/><progress/></div>"
    });
    model.on('change',function(){
        mediator.trigger('change.model',arguments);
        console.log("change!",arguments);
    });

    /**
     * view
     * @type {Object}
     */
    view = {
        progress : $('<progress max="100"></progress>'),
        doneButton:$('.done'),
        form: document.querySelector('#upload-form'),
        submitButton: $('button[type=submit]').on({'click': function (event) {
            mediator.trigger('submit-button:clicked', this);
            event.stopPropagation();
            event.preventDefault();
            return false;
        }}),
        clearButton: $('button[type=reset]').on({'click': function (event) {
            mediator.trigger('clear-button:clicked', this);
            event.stopPropagation();
            event.preventDefault();
            return false;
        }}),
        dropZone: $('.drop-zone').on({
            'dragover': function (event) {
                event.stopPropagation();
                event.preventDefault();
                event.originalEvent.dataTransfer.dropEffect = "copy";
                $(this).toggleClass('dragged-over', true);
            },
            'dragleave': function () {
                $(this).toggleClass('dragged-over', false);
            },
            'drop': function (event) {
                console.log('drop');
                $(this).toggleClass('dragged-over', false);
                event.stopPropagation();
                event.preventDefault();
                var files = [].slice.call(event.originalEvent.dataTransfer.files);
                mediator.trigger('drop-zone:files-dropped', [files]);
            }}),
        imageFiles: []

    };
    /**
     * commands
     * @type {Object}
     */
    command = {
        sendFiles: {
            execute: function () {
                model.formData = new FormData(view.form);
                model.files.forEach(function (file) {
                    model.formData.append('upload[images][]', file);
                });
                var ajax = $.ajax({
                    url: model.url,
                    data: model.formData,
                    processData: false,
                    contentType: false,
                    /* @see http://stackoverflow.com/questions/166221/how-can-i-upload-files-asynchronously-with-jquery */
                    xhr: function () { //custom xhr
                        var xhr = $.ajaxSettings.xhr();
                        if (xhr.upload) { //if upload property
                            xhr.upload.addEventListener('progress', function (event) {
                                mediator.trigger('ajax:progress', [event]);
                            });
                        }
                        return xhr;
                    },
                    type: 'POST',
                    success: function (res) {
                        mediator.trigger('ajax:success', [res]);
                    },
                    error: function (err) {
                        mediator.trigger('ajax:error', [err]);
                    }
                });
                mediator.trigger('ajax:start',[ajax]);
                return ajax;
            }
        },
        log: {
            execute: function (message) {
                this.target.log.apply(this.target, [].slice.call(arguments));
            },
            target: console

        },
        addImageFile: {
            execute: function (file) {
                model.files.push(file);
                model.trigger('change');
                mediator.trigger('image:added', file);
            }
        },
        addImageFiles: {
            execute: function (files) {
                files = [].slice.call(files);
                files.forEach(function (file) {
                    command.addImageFile.execute(file);
                }, this);
            }
        },
        clearImageFiles: {
            execute: function () {
                while (model.files.length > 0) {
                    model.files.pop();
                    model.trigger('change');
                }
                command.clearImageViews.execute();
            }
        },
        clearImageViews: {
            execute: function () {
                var image;
                while (view.imageFiles.length > 0) {
                    image = view.imageFiles.pop();
                    image.remove();
                }
            }
        },
        enableSubmit: {
            execute: function () {
                view.submitButton.removeAttr('disabled');
            }
        },
        disableSubmit: {
            execute: function () {
                view.submitButton.attr('disabled', true);
            }
        },
        enableClear: {
            execute: function () {
                view.clearButton.removeAttr('disabled');
            }
        },
        disableClear: {
            execute: function () {
                view.clearButton.attr('disabled', true);
            }
        },
        enableDone: {
            execute: function () {
                view.doneButton.removeAttr('disabled');
            }
        },
        disableDone: {
            execute: function () {
                view.doneButton.attr('disabled', true);

            }
        },
        createImageFileView: {
            execute: function (file) {
                var reader = new FileReader();
                var $image = $(model.uploadTemplate);
                $image.attr('height', '100');
                reader.onload = function (event) {
                    $image.find('progress').remove();
                    $image.find('img').attr({'src': event.target.result});
                };
                reader.readAsDataURL(file);
                return $image;
            }
        },
        notifyView:{
            execute:function(model){
                if(model.uploading==true){
                    view.dropZone.find('.background-text').html(view.progress).css({'zIndex':100});
                    $(".upload-item").fadeTo(1000,0.5);
                    command.disableDone.execute();
                    command.disableClear.execute();
                    command.disableSubmit.execute();
                }else{
                    view.dropZone.find('.background-text').html(model.dropText).css({zIndex:-10});
                    $(".upload-item").css('opacity',1);
                    command.disableClear.execute();
                    command.disableSubmit.execute();
                    command.enableDone.execute();
                }
            }
        },
        updateProgress:{
            execute:function(){
                this.target.attr('value',model.progress);
            },
            target:view.progress

        }
    };
    /**
     * mediator
     */
    mediator = $({}).on({
        'change.model':function(){
            command.log.execute('model changed!');
           command.notifyView.execute(model);
        },
        'ajax:start':function(e,ajax){
            model.uploading=true;
        },
        'ajax:success': function (e, res) {
            model.uploading=false;
            command.log.execute('success', res);
            command.clearImageFiles.execute();
        },
        'ajax:error': function (e, err) {
            model.uploading=false;
            command.log.execute('error', err);
        },
        'ajax:progress': function (e, progress) {
            if (progress.lengthComputable) {
                model.progress = Math.floor(progress.loaded * 100 / progress.total);
            }
            command.updateProgress.execute();
        },
        'submit-button:clicked': function () {
            command.sendFiles.execute();
            command.disableSubmit.execute();
            command.disableClear.execute();
        },
        'clear-button:clicked': function () {
            command.clearImageFiles.execute();
            command.disableSubmit.execute();
            command.disableClear.execute();
        },
        'image:added': function (e, file) {
            var imageFile = command.createImageFileView.execute(file);
            view.imageFiles.push(imageFile);
            view.dropZone.append(imageFile);
            if (model.files.length > 0) {
                command.enableClear.execute();
                command.enableSubmit.execute();
            }
        },
        'drop-zone:files-dropped': function (e, files) {
            files = files.filter(function (file) {
                return file.type.match(/^image\/.*/i);
            });
            if (files.length > 0) {
                command.addImageFiles.execute(files);
            }
        }
    });

    (function init() {
        command.disableClear.execute();
        command.disableSubmit.execute();
        model.url = view.form.getAttribute('action');
        window.model=model;
        window.view=view;
    }());

});