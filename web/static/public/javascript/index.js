/*jslint browser:true*/
/*global jQuery,_,Statesman,Config */
/**
 * @copyright mparaiso <mparaiso@online.fr>
 * @dependencies jquery , underscore , jquery.observable
 */
"use strict";
jQuery(function($) {
	var model, command, mediator, view, util, constant, template, $log;
	util = {
		getImageSrc: function(id, extension) {
			return constant.imagePath.replace(/(\:\w+)/g, function(match) {
				switch (match) {
					case ':id':
						return id;
					case ':extension':
						return extension;
				}
			});
		},
		loadImage: function(src, callback) {
			var img = new Image();
			img.onload = function(e) {
				return callback(null, img);
			};
			img.onerror = function(e) {
				return callback(e);
			};
			img.src = src;
		}
	};
	template = {

	};
	constant = {
		debug: true,
		config: Config,
		imageResource: '/api/image',
		projectResource: '/api/project',
		pageResource: '/api/page',
		imagePath: '/static/images/cache/:id.:extension'
	};
	/** manage application state */
	model = new Statesman({});
	model.observe('imageIndex',function(){
		console.log(arguments);
	})
	/** controller */
	command = {
		setInitialState: {
			execute: function() {
				view.$container.addClass('hidden');
			}
		},
		loadAssets: {
			execute: function() {
				return;
			}
		},
		initGallery: {
			execute: function() {
				var src, img;
				img = model.get('images[0]');
				src = util.getImageSrc(img.id, img.extension);
				util.loadImage(src, function(err,img) {
					model.set('imageIndex',0);
					view.$galleryContainer
						.find('figure').html(img);
					view.$galleryContainer
						.fadeIn(1000);
					view.$next.on('click',function(){
						mediator.trigger('click.next');
					});
					view.$previous.on('click',function(){
						mediator.trigger('click.previous');
					});
				});
			}
		},
		startLoading: {
			execute: function() {
				view.$galleryContainer.hide();
				view.$spinner.addClass('loading');
				$.when(
					$.getJSON(constant.imageResource),
					$.getJSON(constant.projectResource)
				).done(function(images, projects) {
					model.set('images', images[0]);
					model.set('projects', projects[0]);
					mediator.trigger('load.assets');
				});
			}
		},
		showImage:{
			execute:function(img){
				$log(img.src);
				view.$galleryContainer.find('figure').fadeOut(500,function(){
						view.$galleryContainer.find('figure').html(img);
						view.$galleryContainer.find('figure').fadeIn(500);
					});
			}
		},
		showNextImage:{
			execute:function(){
				var image;
				model.set('imageIndex',(model.get('imageIndex')+1) %  model.get('images').length);
				image = model.get('images.'+model.get('imageIndex'));
				util.loadImage(util.getImageSrc(image.id,image.extension),function(err,img){
					command.showImage.execute(img);
				});
			}
		},
		showPreviousImage:{
			execute:function(){
				var image,index;
				index = model.get('imageIndex') - 1  %  model.get('images').length;
				if(index<0){
					index = index+model.get('images').length;
				}
				model.set('imageIndex',index);
				image = model.get('images.'+model.get('imageIndex'));
				util.loadImage(util.getImageSrc(image.id,image.extension),function(err,img){
					command.showImage.execute(img);
				});
			}
		},
		initPage: {
			execute: function() {
				view.$container.removeClass('hidden');
				view.$spinner.addClass('hidden');
				command.initGallery.execute();
			}
		}
	};
	/** templates and dom components */
	view = {
		$style: $('style.main'),
		$body: $('body'),
		$spinner: $('#spinner'),
		$container: $('#container'),
		$galleryContainer:$('#gallery-container'),
		$next:$('.next'),
		$previous:$('.previous')
	};
	/** dispatch event between layers of application */
	mediator = $({}).on({
		'load.assets': function() {
			command.initPage.execute();
		},
		'click.previous':function(){
			command.showPreviousImage.execute();
		},
		'click.next':function(){
			command.showNextImage.execute();
		}
	});
	/** log function,can be turned off */
	$log = function() {
		console.log.apply(console, [].slice.call(arguments));
	};
	/** start the application */
	(function init() {
		if (constant.debug === true) {
			$log("version", constant.config.version);
		}
		command.setInitialState.execute();
		command.startLoading.execute();
	}());

});