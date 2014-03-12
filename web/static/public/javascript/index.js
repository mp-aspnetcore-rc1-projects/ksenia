/*jslint browser:true*/
/*global jQuery,_,Statesman,Config,Backbone, */
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
		zoom: false,
		galleryVisible: false,
		transition: true,
		pageVisible: false
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
				Backbone.history.start();
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
					command.showGallery.execute().done(function() {
						/** add click handlers to buttons */
						command.showImage.execute(img);
						view.$next.on('click', function() {
							mediator.trigger('click.next');
						});
						view.$previous.on('click', function() {
							mediator.trigger('click.previous');
						});
					});
				});
			}
		},
		/* init summary */
		initSummary: {
			execute: function() {
				//remove all event listeners
				view.$summary.off();
				/* ajust summary size to the header size,now that the header has a menu */
				view.$summary.width(view.$header.width());
				view.$zoom = view.$summary.find('zoom');
				view.$zoom.click(mediator.trigger.bind(mediator, 'click.zoom'));
			}
		},
		/* init model */
		initModel: {
			execute: function(images, projects, pages, menus) {
				model.set('images', images);
				model.set('pages', pages);
				model.set('projects', projects);
				model.set('mainMenu', util.findMainMenu(menus));
				model.set('menus', menus);
			}
		},
		/* hide page*/
		hidePage: {
			execute: function() {
				view.$main.addClass('hidden');
				view.$header.addClass('hidden');
				view.$gallery.addClass('hidden');
				command.hideSubNav.execute();
				view.$gallery.addClass('hidden');
			}
		},
		/* show page */
		showPage: {
			execute: function() {
				view.$main.removeClass('hidden');
				view.$header.removeClass('hidden');
			}
		},
		/* first command executed , initialize the front page */
		initPage: {
			execute: function() {
				command.hidePage.execute();
				view.$summary.css({
					left: "150%"
				});
				$.when(
					$.getJSON(constant.imageResource),
					$.getJSON(constant.pageResource),
					$.getJSON(constant.projectResource),
					$.getJSON(constant.menuResource)
				).done(function(images, pages, projects, menus) {
					command.initModel.execute(images[0], projects[0], pages[0], menus[0]);
					command.hideSpinner.execute();
					command.initMenu.execute();
					command.initSummary.execute();
					command.startRouter.execute();
					command.showPage.execute();
				});
			}
		},
		/* show gallery */
		showGallery: {
			execute: function() {
				var deferred = $.Deferred();
				if (model.get('galleryVisible') === false) {
					model.set('galleryVisible', true);
					view.$gallery.removeClass('hidden');
					/** show first image */
				}
				setTimeout(deferred.resolve.bind(deferred), 1);
				return deferred;
			}
		},
		/* hide gallery */
		hideGallery: {
			execute: function() {
				var deferred = $.Deferred();
				if (model.get('galleryVisible') === true) {
					model.set('galleryVisible', false);
					view.$gallery.addClass('hidden');
				}
				setTimeout(deferred.resolve.bind(deferred), 1);
				return deferred;
			}
		},
		/* hide image */
		hideImage: {
			execute: function() {
				var deferred = $.Deferred();
				view.$summary.animate({
					left: "-50%"
				});
				if (model.get('imageHidden') === false) {
					view.$gallery.find('figure').fadeOut(500, function() {
						model.set('transition', true);
						model.set('imageHidden', true);
						deferred.resolve();
					});
				} else {
					setTimeout(deferred.resolve.bind(deferred), 1);
				}
				return deferred.promise();
			}
		},
		/* show gallery image */
		showImage: {
			execute: function(img) {
				$log(img, img.src);
				view.$img = $(img);
				command.hideImage.execute().pipe(command.showGallery.execute()).done(function() {
					command.toggleZoom.execute();
					view.$gallery.find('figure').html(img);
					view.$gallery.find('figure').fadeIn(500, function() {
						view.$summary.html(template.summary(model.get('currentImage')));
						command.initSummary.execute();
						view.$summary.css({
							left: "150%"
						}).animate({
							left: "5%"
						});
						model.set('transition', false);
						model.set('imageHidden', false);
					});
				});
			}
		},
		/* move to next image */
		showNextImage: {
			execute: function() {
				var image;
				command.hideResource.execute();
				model.set('imageIndex', (model.get('imageIndex') + 1) % model.get('images').length);
				image = model.get('images.' + model.get('imageIndex'));
				util.loadImage(util.getImageSrc(image.id, image.extension), function(err, img) {
					command.showImage.execute(img);
				});
			}
		},
		/* move to previous image */
		showPreviousImage: {
			execute: function() {
				var image, index;
				command.hideResource.execute();
				index = model.get('imageIndex') - 1 % model.get('images').length;
				if (index < 0) {
					index = index + model.get('images').length;
				}
				model.set('imageIndex', index);
				image = model.get('images.' + model.get('imageIndex'));
				util.loadImage(util.getImageSrc(image.id, image.extension), function(err, img) {
					command.showImage.execute(img);
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
		},
		showResource: {
			execute: function(type, id) {
				var resources = model.get(type + 's');
				var resource = resources.filter(function(r) {
					return r.id === id;
				}).pop();
				if (resource) {
					command.hideResource.execute();
					view.$page.html(template[type](resource));
					view.$page.removeClass('hidden');
					model.set('pageVisible', true);
				}
			}
		},
		hideResource: {
			execute: function() {
				if (model.get('pageVisible') === true) {
					model.set('pageVisible', false);
					view.$page.addClass('hidden');
				}
			}
		},
		loadImageById: {
			execute: function(id) {
				var deferred, image, src;
				deferred = $.Deferred();
				image = model.get('images').filter(function(img) {
					return img.id === id;
				}).pop();
				if (image) {
					src = util.getImageSrc(image.id, image.extension);
					util.loadImage(src, function(err, img) {
						if (err) {
							deferred.fail(err);
						} else {
							deferred.resolve(img);
						}
					});
				} else {
					setTimeout(deferred.fail.bind(deferred), 1);
				}
				return deferred.promise();
			}
		}
	};
	/** templates and dom components */
	view = {
		$style: $('style.main'),
		$body: $('body'),
		$spinner: $('#spinner'),
		$main: $('#main'),
		$gallery: $('#gallery'),
		$img: null,
		$next: $('.next'),
		$previous: $('.previous'),
		$summary: $('.summary'),
		$header: $('header'),
		$menu: $('#main-menu'),
		$subNav: $('.nav-sub'),
		$zoom: $('#zoom'),
		$page: $('#page')
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
			if (model.get('transition') === false) {
				command.showPreviousImage.execute();
			}
		},
		'click.next': function() {
			if (model.get('transition') === false) {
				command.showNextImage.execute();
			}
		},
		'click.menu': function(event, link) {
			command.toggleSubNav.execute(_(model.get('menus')).find(function(menu) {
				return menu.id === link.itemId;
			}));
		}
	});
	Router = Backbone.Router.extend({
		routes: {
			":type/:id": "resource",
			"(/:id)": "index",
		},
		index: function() {
			command.initGallery.execute();
		},
		resource: function(type, id) {
			switch (type) {
				case 'image':
					command.loadImageById.execute(id).done(function(img) {
						console.log(img);
						command.showImage.execute(img);
					});
					break;
				case 'page':
				case 'project':
					command.hideSubNav.execute();
					command.showResource.execute(type, id);
					break;
			}
		}
	});
	template = {
		blockWidth: _.template("<li class='block-width'>&nbsp;</li>"),
		link: _.template('<li data-id="<%-id%>"\
		 data-item-id="<%-itemId%>" data-type="<%-type%>">\
		 <a href="#<%-type%>/<%-itemId%>"><%-title%></a></li>'),
		linkSeparator: '<li class="separator">&nbsp;</li>',
		page: _.template('<div class="page">\
								<h2 class="primary"><%-title%></h2>\
								<p><pre><%-content%></pre></p>\
							</div>'),
		project: _.template('<div class="project">\
								<details open>\
									<summary>\
										<h2 class="primary inline"><%-title%></h2>\
									</summary>\
									<p><pre><%-description%></pre></p>\
									<section>\
										<% _.each(images,function(image){ %>\
											<figure>\
												<a href="#image/<%-image.id%>">\
													<img src="/static/images/cache/<%-image.id%>.<%-image.extension%>"/>\
												</a>\
											</figure>\
										<% }); %>\
									</section>\
								</details>\
							</div>'),
		summary: _.template('<summary>\
               					<h3 class="inline primary"><%-title%></h3>\
               					<button id="zoom" title="zoom in,zoom out" class="zoom">[&harr;]</button>\
               				</summary>\
               				<hr>\
               				<p>\
                  				Project: <a href="#"><%-project.title%></a><br>\
                  				Client: <a href="#"><%-project.client%></a><br>\
                  				<%-description%>\
               				</p>')
	};
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