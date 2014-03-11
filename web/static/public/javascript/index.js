/*jslint browser:true*/
/*global jQuery,_,Statesman,Config,Backbone */
/**
 * @copyright mparaiso <mparaiso@online.fr>
 * @license   All rights reserved
 * @version 0.0.1
 * @dependencies jquery , underscore , Stateman, Backbone
 */
"use strict";
jQuery(function($) {
	var model, command, mediator, view, util, constant, $log;
	util = {
		findMainMenu: function(menus) {
			/* extract main menu*/
			var menu = menus.filter(function(m) {
				return m.isMain;
			}).pop();
			if (!menu) {
				menu = menus[0];
			}
			return menu;
		},
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
		menuResource: '/api/menu',
		imagePath: '/static/images/cache/:id.:extension'
	};
	/** manage application state */
	model = new Statesman({
		imageIndex: 0,
		images: [],
		menus: [],
		pages: [],
		projects: []
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
		/* initialize main menu */
		initMenu: {
			execute: function() {
				model.get('mainMenu').links.forEach(function(link, i, a) {
					/* create link */
					var $link = $('<li>', {
						'data-id': link.id,
						'data-item-id': link.itemId,
						'data-type': link.type
					}).html(
						$('<a href="#/link/' + link.type + '/' + link.itemId + '">' + link.title + '</a>')
					).click(function() {
						mediator.trigger('click.link', [$link.data]);
					});
					/* append link to menu */
					view.$menu.append($link);
					/*append separator between each link unless last link*/
					if (i < a.length - 1) {
						view.$menu.append('<li class="separator">&nbsp;</li>');
					}
				});
			}
		},
		/*initialize gallery */
		initGallery: {
			/** init image gallery */
			execute: function() {
				var src, img;
				img = model.get('images[0]');
				src = util.getImageSrc(img.id, img.extension);
				/* load first image */
				util.loadImage(src, function(err, img) {
					model.set('imageIndex', 0);
					view.$galleryContainer.fadeIn(100, function(argument) {
						/** show first image */
						command.showImage.execute(img);
					});
					view.$container.fadeIn(1000);
					/** add click handlers to buttons */
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
		/* first command executed , initialize the front page */
		initPage: {
			execute: function() {
				view.$summary.css({
					left: "150%"
				});
				view.$container.addClass('hidden');
				view.$header.addClass('hidden');
				command.hideSubNav.execute();
				view.$galleryContainer.hide();
				$.when(
					$.getJSON(constant.imageResource),
					$.getJSON(constant.projectResource),
					$.getJSON(constant.menuResource)
				).done(function(images, projects, menus) {
					model.set('images', images[0]);
					model.set('projects', projects[0]);
					model.set('mainMenu', util.findMainMenu(menus[0]));
					model.set('menus', menus[0]);
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
		/* hide gallery image */
		hideImage: {
			execute: function() {
				var deferred = $.Deferred();
				view.$summary.animate({
					left: "-50%"
				});
				view.$galleryContainer.find('figure').fadeOut(500, function() {
					model.set('transition', true);
					deferred.resolve();
				});
				return deferred.promise();
			}
		},
		/* show gallery image */
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
						left: "150%"
					}).animate({
						left: "5%"
					});
				});
			}
		},
		/* move to next image */
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
		/* move to previous image */
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
		/* show loading spinner */
		showSpinner: {
			execute: function() {
				view.$spinner.removeClass('hidden');
			}
		},
		/* hide loading spinner */
		hideSpinner: {
			execute: function() {
				view.$spinner.addClass('hidden');
			}
		},
		/* show subnav */
		showSubNav: {
			execute: function() {
				if (model.get('subNav.hidden')) {
					view.$subNav.show();
					model.set('subNav.hidden', false);
				}
			}
		},
		hideSubNav: {
			execute: function() {
				if (!model.get('subNav.hidden')) {
					view.$subNav.hide();
					model.set('subNav.hidden', true);
				}
			}
		},
		buildSubNav: {
			execute: function(link) {
				console.log('building subnav', link);
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
		$menu: $('#main-menu'),
		$subNav: $('.nav-sub')

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
		},
		'click.link': function(event, linkData) {
			var link = model.get('menus').filter(function(link) {
				return link.id === linkData.id;
			}).pop();
			if (link) {
				switch (link.type) {
					case 'menu':
						command.showSubNav.execute();
						command.buildSubNav.execute(link);
						break;

				}
			}
		}
	});
	/** log function,can be turned off */
	$log = function() { /*@TODO*/
		return;
	};
	/** start the application */
	(function init() {
		if (constant.debug === true) {
			$log("version", constant.config.version);
		}
		command.initPage.execute();
	}());

});