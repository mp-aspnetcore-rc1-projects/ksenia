/*jslint browser:true*/
/*global jQuery,_,Statesman,Config */
/**
 * @copyright mparaiso <mparaiso@online.fr>
 * @license   All rights reserved
 * @version 0.0.1
 * @dependencies jquery , underscore , jquery.Stateman
 */
"use strict";
jQuery(function($) {
	var model, command, mediator, view, util, constant, $log;
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
	constant = {
		debug: true,
		config: Config,
		imageResource: '/api/image',
		projectResource: '/api/project',
		pageResource: '/api/page',
		menuResource:'/api/menu',
		imagePath: '/static/images/cache/:id.:extension'
	};
	/** manage application state */
	model = new Statesman({
		imageIndex: 0,
		images: []
	});
	model.compute('currentImage', {
		triggers: ['imageIndex', 'images'],
		get: function() {
			var i = this.get('imageIndex');
			return this.get('images')[i];
		}
	});
	model.observe('transition', function(isTransition) {
		mediator.trigger('load.image', [isTransition]);
	});
	/** controller */
	command = {
		initMenu: {
			execute: function() {
				model.get('mainMenu').links.forEach(function(link, i, a) {
					console.log(link);
					view.$menu.append($('<li>').html($('<a>',{href:'#/link/'+link.type+'/'+link.itemId}).html(link.title)));
					if (i < a.length-1) {
						view.$menu.append($('<li>', {
							class: "separator"
						}).html("&nbsp;"));
					}
				});
			}
		},
		initGallery: {
			execute: function() {
				var src, img;
				img = model.get('images[0]');
				src = util.getImageSrc(img.id, img.extension);
				util.loadImage(src, function(err, img) {
					model.set('imageIndex', 0);
					view.$galleryContainer.fadeIn(100, function(argument) {
						command.showImage.execute(img);
					});
					view.$container
						.fadeIn(1000);
					view.$next.on('click', function() {
						if (model.get('transition') === false) {
							mediator.trigger('click.next');
						}
					});
					view.$previous.on('click', function() {
						if (model.get('transition') === false) {
							mediator.trigger('click.previous');
						}
					});
				});
			}
		},
		initPage: {
			execute: function() {
				view.$summary.css({
					left: "-30%"
				});
				view.$container.addClass('hidden');
				view.$header.addClass('hidden');
				view.$galleryContainer.hide();
				$.when(
					$.getJSON(constant.imageResource),
					$.getJSON(constant.projectResource),
					$.getJSON(constant.menuResource)
				).done(function(images, projects, menu) {
					model.set('images', images[0]);
					model.set('projects', projects[0]);
					model.set('mainMenu', menu[0]);
					view.$container.removeClass('hidden');
					view.$header.removeClass('hidden');
					command.hideSpinner.execute();
					command.initMenu.execute();
					/* ajust summary size to the header size,now that the header has a menu */
					view.$summary.width(view.$header.width());
					command.initGallery.execute();
				});
			}
		},
		hideImage: {
			execute: function() {
				var deferred = $.Deferred();
				view.$summary.animate({
					left: "120%"
				});
				view.$galleryContainer.find('figure').fadeOut(500, function() {
					model.set('transition', true);
					deferred.resolve();
				});
				return deferred.promise();
			}
		},
		showImage: {
			execute: function(img) {
				$log(img.src);
				model.set('transition', false);
				view.$galleryContainer.find('figure').html(img);
				view.$galleryContainer.find('figure').fadeIn(500, function() {
					view.$summary.find('[role=title]').text(model.get('currentImage').title);
					view.$summary.find('[role=description]').text(model.get('currentImage').description);
					view.$summary.find('[role=project]').text(model.get('currentImage').project.title);
					view.$summary.find('[role=client]').text(model.get('currentImage').project.client);
					view.$summary.css({
						left: "-30%"
					}).animate({
						left: "5%"
					});
				});
			}
		},
		showNextImage: {
			execute: function() {
				var image;
				command.hideImage.execute().done(function() {
					model.set('imageIndex', (model.get('imageIndex') + 1) % model.get('images').length);
					image = model.get('images.' + model.get('imageIndex'));
					util.loadImage(util.getImageSrc(image.id, image.extension), function(err, img) {
						command.showImage.execute(img);
					});
				});

			}
		},
		showPreviousImage: {
			execute: function() {
				var image, index;
				command.hideImage.execute().done(function() {
					index = model.get('imageIndex') - 1 % model.get('images').length;
					if (index < 0) {
						index = index + model.get('images').length;
					}
					model.set('imageIndex', index);
					image = model.get('images.' + model.get('imageIndex'));
					util.loadImage(util.getImageSrc(image.id, image.extension), function(err, img) {
						command.showImage.execute(img);
					});
				});
			}
		},
		showSpinner: {
			execute: function() {
				view.$spinner.removeClass('hidden');
			}
		},
		hideSpinner: {
			execute: function() {
				view.$spinner.addClass('hidden');
			}
		}
	};
	/** templates and dom components */
	view = {
		$style: $('style.main'),
		$body: $('body'),
		$spinner: $('#spinner'),
		$container: $('#container'),
		$galleryContainer: $('#gallery-container'),
		$next: $('.next'),
		$previous: $('.previous'),
		$summary: $('.summary'),
		$header: $('header'),
		$menu: $('#main-menu')
	};
	/** dispatch event between layers of application */
	mediator = $({}).on({
		'load.image': function(e, isTransition) {
			if (isTransition) {
				command.showSpinner.execute();
			} else {
				command.hideSpinner.execute();
			}
		},
		'click.previous': function() {
			command.showPreviousImage.execute();
		},
		'click.next': function() {
			command.showNextImage.execute();
		}
	});
	/** log function,can be turned off */
	$log = function() {
		if (typeof(console) !== undefined) {
			//console.log.apply(console, [].slice.call(arguments));
		}
	};
	/** start the application */
	(function init() {
		if (constant.debug === true) {
			$log("version", constant.config.version);
		}
		command.initPage.execute();
	}());

});