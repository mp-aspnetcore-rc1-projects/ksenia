/*jslint browser:true*/
/*global jQuery,_,Statesman,Config,Backbone */
/**
 * @copyright mparaiso <mparaiso@online.fr>
 * @license   All rights reserved
 * @version 0.0.1
 * @dependencies jquery , underscore , Stateman, Backbone
 */
jQuery(function($) {
	"use strict";
	var model, command, template, Router, mediator, view, util, constant, $log;
	util = {
		/**
		 * build a jQuery object from a link
		 * @param  {Object} link
		 * @return {jQuery.Object}
		 */
		buildLink: function(link) {
			return $(template.link(link)).click(function() {
				if (link.type === "menu") {
					mediator.trigger('click.menu', [$(this).data()]);
				}
			});
		},
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
	template = {
		blockWidth: _.template("<li class='block-width'>&nbsp;</li>"),
		link: _.template('<li data-id="<%-id%>"\
		 data-item-id="<%-itemId%>" data-type="<%-type%>">\
		 <a href="#<%-type%>/<%-itemId%>"><%-title%></a></li>'),
		linkSeparator: '<li class="separator">&nbsp;</li>'
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
		projects: [],
		zoom: true
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
	/** application controller */
	command = {
		/* start router */
		startRouter: {
			execute: function() {
				Backbone.history.start({pushState:true});
			}
		},
		/* initialize main menu */
		initMenu: {
			execute: function() {
				view.$menu.append(model.get('mainMenu').links.map(function(link) {
					return util.buildLink(link);
				})).children().each(function(i) {
					$(this).after(template.linkSeparator);
				}).parent().children().last().remove();
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
					command.initGallery.execute();
					command.startRouter.execute();
					/* ajust summary size to the header size,now that the header has a menu */
					view.$summary.width(view.$header.width());
					view.$zoom.click(function() {
						mediator.trigger('click.zoom');
					});
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
				view.$img = $(img);
				command.toggleZoom.execute();
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
					view.$subNav.show(400);
					model.set('subNav.hidden', false);
				}
			}
		},
		/** hide subnav */
		hideSubNav: {
			execute: function() {
				if (!model.get('subNav.hidden')) {
					view.$subNav.hide(400);
					model.set('subNav.hidden', true);
				}
			}
		},
		/** toggle subnav */
		toggleSubNav: {
			execute: function(menu) {
				if (model.get('subNav.hidden')) {
					command.showSubNav.execute();
				} else {
					command.hideSubNav.execute();
				}
				view.$subNav.html(menu.links.map(
					function(link) {
						$log(link);
						return util.buildLink(link);
					}));
			}
		},
		toggleZoom: {
			execute: function() {
				if (model.get('zoom')) {
					command.zoom.execute();
				} else {
					command.unzoom.execute();
				}
			}
		},
		zoom: {
			execute: function() {
				view.$img.width('100%');
				view.$img.css('height', 'auto');
				view.$zoom.html('[&nharr;]');
			}
		},
		unzoom: {
			execute: function() {
				view.$img.width('auto');
				view.$img.css('height', '100%');
				view.$zoom.html('[&harr;]');
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
		$img: null,
		$next: $('.next'),
		$previous: $('.previous'),
		$summary: $('.summary'),
		$header: $('header'),
		$menu: $('#main-menu'),
		$subNav: $('.nav-sub'),
		$zoom: $('#zoom')
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
		'click.zoom': function() {
			model.set('zoom', !model.get('zoom'));
			command.toggleZoom.execute();
		},
		'click.previous': function() {
			command.showPreviousImage.execute();
		},
		'click.next': function() {
			command.showNextImage.execute();
		},
		'click.menu': function(event, link) {
			command.toggleSubNav.execute(_(model.get('menus')).find(function(menu) {
				return menu.id === link.itemId;
			}));
		}
	});
	Router = Backbone.Router.extend({
		routes: {
			"": "index",
			":type/:id": "resource",
		},
		resource: function(type, id) {
			if (type !== 'menu') {
				command.hideSubNav.execute();
			}
			switch (type) {
				case 'page':
					break;
				case 'project':
					break;
			}
		}
	});
	/** log function,can be turned off */
	$log = function() { /*@TODO*/
		console.log.apply(console, arguments);
	};
	/** start the application */
	(function init() {
		if (!constant.test) {
			if (constant.debug === true) {
				$log("version", constant.config.version);
			}
			var router = new Router();
			command.initPage.execute();
		}
	}());
});