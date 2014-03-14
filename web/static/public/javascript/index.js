/*jslint es5:true,browser:true*/
/*global jQuery,_,Statesman,Config,Backbone */
/**
 * @copyright mparaiso <mparaiso@online.fr>
 * @license   All rights reserved
 * @version 0.0.1
 * @dependencies jquery , underscore , Stateman, Backbone
 */
jQuery(function($) {
	"use strict";
	var model, router, command, template, Router, Model, mediator, view, util, constant, $log;
	util = { /** utility functions **/
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
	/** immutable values */
	constant = {
		debug: true,
		config: Config,
		imageResource: '/api/image',
		projectResource: '/api/project',
		pageResource: '/api/page',
		menuResource: '/api/menu',
		imagePath: '/static/images/cache/:id.:extension',
	};
	/** manage application state */
	Model = Backbone.Model.extend({
		initialize: function(options) {
			this.on('change:menus', function(model) {
				var menus = this.get('menus');
				var menu = menus.filter(function(m) {
					return m.isMain;
				}).pop();
				if (!menu) {
					menu = menus[0];
				}
				this.set('mainMenu', menu);
			});
			this.on('change:currentImage', function(model, currentImage) {
				var index = this.getImageIndexInPlaylist(currentImage);
				if (index < 0) {
					index = 0;
				}
				this.set('playlistIndex', index);
			});
			this.on('change:currentProject', function(model, currentProject) {
				this.set('playlist', this.getImagesByProject(currentProject));
				this.set('playlistIndex', 0);
			});
			this.on('change:transition', function(model, transition) {
				mediator.trigger('load.image', [transition]);
			});
		},
		getCurrentImage: function() { /* get the current image displayed in playlist */
			return this.getImageInPlaylistAt(this.get('playlistIndex'));
		},
		getImageIndexInPlaylist: function(image) { /* get the index of an image in the playlist */
			return this.get('playlist').indexOf(this.getImageById(image.id));
		},
		getImageById: function(id) { /* get image by image id */
			return this.get('images').filter(function(img) {
				return id === img.id;
			}).pop();
		},
		getFirstImageInPlaylist: function() {
			return this.getImageInPlaylistAt(0);
		},
		getImageInPlaylistAt: function(index) {
			return model.get('playlist')[index];
		},
		getPreviousImage: function() { /* get previous image in current playlist */
			var index;
			index = (this.get('playlistIndex') - 1) % this.get('playlist').length;
			if (index < 0) {
				index = index + this.get('playlist').length;
			}
			this.set('playlistIndex', index);
			return this.getCurrentImage();
		},
		getNextImage: function() { /* get next image in current playlist */
			this.set('playlistIndex', (this.get('playlistIndex') + 1) % this.get('playlist').length);
			return this.getCurrentImage();
		},
		getImagesByProject: function(project) { /* find all images by project id */
			return this.get('images').filter(function(img) {
				return img.project.id.toString() === project.id.toString();
			});
		},
		getProjectById: function(id) {
			return this.get('projects').filter(function(project) {
				return project.id === id;
			}).pop();
		},
		defaults: {
			playlistIndex: 0,
			images: [], // all images
			playlist: [], // gallery imageList
			menus: [], // all menus
			pages: [], // all pages
			projects: [], // all projects
			galleryVisible: true, //is gallery showing
			transition: false, //is image transition happening
			pageVisible: true //is page visible
		}
	});

	/** application controller */
	command = {
		startRouter: { /* start router */
			execute: function() {
				Backbone.history.start();
			}
		},
		initMenu: { /* initialize main menu */
			execute: function() {
				view.$menu.append(model.get('mainMenu').links.map(function(link) {
					return util.buildLink(link);
				})).children().each(function(i) {
					$(this).after(template.linkSeparator);
				}).parent().children().last().remove();
			}
		},
		initGallery: { /*initialize gallery */
			execute: function() {
				model.set('playlist', model.get('images'));
				model.set('playlistIndex', 0);
				model.set('currentImage', model.get('images')[0]);
				command.showCurrentImage.execute();
				command.hideResource.execute().done(function() {
					view.$page.html(template.thumbnails({
						images: model.get('playlist')
					}));
					view.$page.slideDown(700);
					model.set('pageVisible', true);
				});
			}
		},
		initSummary: { /* init summary */
			execute: function() {
				view.$summary.off(); /* remove all event listeners */
				view.$zoom = view.$summary.find('zoom');
				view.$zoom.click(mediator.trigger.bind(mediator, 'click.zoom'));
			}
		},
		showSummary: { /* show summary */
			execute: function() {
				var deferred = $.Deferred();
				view.$summary.show(700, function() {
					deferred.resolve();
				});
				return deferred.promise();
			}
		},
		hideSummary: {
			execute: function() {
				var deferred = $.Deferred();
				view.$summary.slideUp(500, function() {
					deferred.resolve();
				});
				return deferred.promise();
			}
		},
		initModel: { /* init model */
			execute: function(images, projects, pages, menus) {
				model.set('images', images);
				model.set('pages', pages);
				model.set('projects', projects);
				model.set('menus', menus);
				model.set('playlist', images);
			}
		},
		hidePage: { /* hide page*/
			execute: function() {
				view.$main.hide();
				view.$header.hide();
				view.$gallery.hide();
				view.$footer.hide();
				command.hideSubNav.execute();
			}
		},
		/* show page */
		showPage: {
			execute: function() {
				view.$main.show();
				view.$header.show();
				view.$footer.show();
			}
		},
		/* first command executed , initialize the front page */
		initPage: {
			execute: function() {
				command.hidePage.execute();
				command.hideGallery.execute();
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
					view.$gallery.slideDown(700, deferred.resolve.bind(deferred));
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
					view.$gallery.slideUp(500, deferred.resolve.bind(deferred));
				}
				setTimeout(deferred.resolve.bind(deferred), 1);
				return deferred;
			}
		},
		/* hide image */
		hideImage: {
			execute: function() {
				var deferred = $.Deferred();
				command.hideSummary.execute().done(function() {
					view.$gallery.find('figure').fadeOut(500, function() {
						deferred.resolve();
					});
				});
				return deferred.promise();
			}
		},
		/* show gallery image */
		showImage: {
			execute: function(img) {
				view.$img = $(img);
				model.set('transition', true);
				return command.hideImage.execute().pipe(command.showGallery.execute()).done(function() {
					view.$gallery.find('figure').html(img);
					view.$gallery.find('figure').fadeIn(700, function() {
						console.log('currentImage', model.getCurrentImage());
						view.$summary.html(template.summary(model.getCurrentImage()));
						command.initSummary.execute();
						view.$summary.slideDown(500);
						model.set('transition', false);
					});
				});
			}
		},
		showCurrentImage: { /** show current image */
			execute: function() {
				var image = model.get('currentImage') || model.getFirstImageInPlaylist();
				return command.loadImageById.execute(image.id).done(function(img) {
					command.showImage.execute(img);
				});
			}
		},
		/* move to next image */
		showNextImage: {
			canExecute: function() {
				return model.get('transition') === false;
			},
			execute: function() {
				var image;
				if (!this.canExecute()) {
					return;
				}
				image = model.getNextImage();
				util.loadImage(util.getImageSrc(image.id, image.extension), function(err, img) {
					command.showImage.execute(img);
				});
			}
		},
		/* move to previous image */
		showPreviousImage: {
			canExecute: function() {
				return model.get('transition') === false;
			},
			execute: function() {
				var image = model.getPreviousImage();
				if (!this.canExecute()) {
					return;
				}
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
					view.$subNav.slideDown();
					model.set('subNav.hidden', false);
				}
			}
		},
		/** hide subnav */
		hideSubNav: {
			execute: function() {
				if (!model.get('subNav.hidden')) {
					view.$subNav.slideUp();
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
						return util.buildLink(link);
					}));
			}
		},
		/* show a resource */
		showResource: {
			execute: function(type, id) {
				model.get(type + 's').filter(function(r) {
					return r.id === id;
				}).forEach(function(resource) {
					command.hideResource.execute().done(function() {
						view.$page.html(template[type](resource));
						view.$page.slideDown(700);
						model.set('pageVisible', true);
					});
				});
			}
		},
		hideResource: {
			execute: function() {
				var deferred = $.Deferred();
				if (model.get('pageVisible') === true) {
					model.set('pageVisible', false);
					view.$page.slideUp(500, deferred.resolve.bind(deferred));
				} else {
					setTimeout(deferred.resolve.bind(deferred), 1);
				}
				return deferred.promise();
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
		$page: $('#page'),
		$footer: $('footer')
	};
	/** dispatch event between layers of application */
	mediator = $({}).on({
		'load.image': function(e, transition) {
			if (transition) {
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
		'click.menu': function(event, link) {
			command.toggleSubNav.execute(_(model.get('menus')).find(function(menu) {
				return menu.id === link.itemId;
			}));
		}
	});
	/** route links in the page */
	Router = Backbone.Router.extend({
		routes: {
			"project/:id": "project",
			"project/:projectId/image/:imageId": "projectImage",
			"page/:id": "page",
			"image/:id": "image",
			"": "index",
		},
		index: function() {
			command.initGallery.execute();
		},
		project: function(projectId) {
			var project;
			project = model.getProjectById(projectId);
			if (project) {
				command.hideSubNav.execute();
				command.showResource.execute("project", projectId);
				model.set('currentProject', project);
				model.set('currentImage', model.getFirstImageInPlaylist());
				command.showCurrentImage.execute();
			}
		},
		projectImage: function(projectId, imageId) {
			var project;
			project = model.getProjectById(projectId);
			if (project) {
				command.hideSubNav.execute();
				if (!model.get('currentProject') || (model.get('currentProject').id !== projectId)) {
					model.set('currentProject', project);
					command.showResource.execute("project", projectId);
				}
				model.set('currentImage', model.getImageById(imageId) || model.getFirstImageInPlaylist());
				command.showCurrentImage.execute();
			}
		},
		page: function(id) {
			command.hideGallery.execute();
			command.hideSubNav.execute();
			command.showResource.execute('page', id);
		}
	});
	/** html templates */
	template = {
		thumbnails: _.template('	<% _.each(images,function(image){ %>\
									<figure class="thumbnail">\
										<a href="#/image/<%-image.id%>">\
											<img src="/static/images/cache/<%-image.id%>.<%-image.extension%>"/>\
										</a>\
									</figure>\
								<% }); %>'),
		link: _.template('	<li data-id="<%-id%>"\
		 					data-item-id="<%-itemId%>" data-type="<%-type%>">\
		 						<a <% if(type!="menu"){%> href="#<%-type%>/<%-itemId%>" <% }else{ %> href="javascript:void 0;" <% } %> >\
		 							<%-title%>\
		 						</a>\
		 					</li>'),

		linkSeparator: '<li class="separator">&nbsp;</li>',

		page: _.template('<div class="page">\
								<h2 class="primary"><%-title%></h2>\
								<div><pre><%-content%></pre></div>\
								<div class="space">&nbsp;</div>\
							</div>'),

		project: _.template('<div class="project">\
									<h2 class="primary inline"><%-title%></h2>\
									<section>\
										<% _.each(images,function(image){ %>\
											<figure class="thumbnail">\
												<a href="#/project/<%-id%>/image/<%-image.id%>">\
													<img src="/static/images/cache/<%-image.id%>.<%-image.extension%>"/>\
												</a>\
											</figure>\
										<% }); %>\
									</section>\
									<p><pre><%-description%></pre></p>\
									<div class="space">&nbsp;</div>\
							</div>'),

		summary: _.template('<summary>\
               					<h4 class="inline primary"><%-title%></h4>\
               				</summary>\
               				<hr>\
               				<p>\
                  				Project: <a href="#project/<%-project.id%>"><%-project.title%></a><br>\
                  				Client: <%-project.client%><br>\
               				</p>')
	};
	/** log function,can be turned off */
	$log = function() { /*@TODO*/
		if (constant.debug === true) {
			console.log.apply(console, arguments);
		}
	};
	/** start the application */
	(function init() {
		if (!constant.test) {
			if (constant.debug === true) {
				$log("version", constant.config.version);
			}
			model = new Model();
			router = new Router();
			view.$next.on('click', function() { /** add click handlers to buttons */
				console.log('click.next', model.toJSON());
				mediator.trigger('click.next');
			});
			view.$previous.on('click', function() { /** add click handlers to buttons */
				console.log('click.previous', model.toJSON());
				mediator.trigger('click.previous');
			});
			command.initPage.execute();
		}
	}());
});