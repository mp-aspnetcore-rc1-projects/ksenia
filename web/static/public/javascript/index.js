/*jslint multistr:true,browser:true,white:true,devel:true,nomen:true*/
/*global jQuery,Config,Backbone,_,Model,Showdown */
/**
 * @copyright mparaiso <mparaiso@online.fr>
 * @license   All rights reserved
 * @version 0.0.1
 * @dependencies jquery , underscore , Backbone,Model
 */
jQuery(function ($) {
    "use strict";
    var model, markdown, router, Router, View, command, template, mediator, view, util, constant, $log, slug;
    slug = function (string) {
        return string.replace(/\s/g, "_");
    };
    Backbone.Mediator = function (events) {
        events = events || {};
        return _.extend({}, Backbone.Events).on(events);
    };
    util = { /** utility functions **/
    getImageSrc: function (id, extension) {
        return constant.imagePath.replace(/(:\w+)/g, function (match) {
            switch (match) {
                case ':id':
                    return id;
                case ':extension':
                    return extension;
            }
        });
    },
        loadImage: function (src, callback) {
            var img = new Image();
            img.onload = function () {
                return callback(null, img);
            };
            img.onerror = function (e) {
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
        projectResource: Config.projectResource,
        pageResource: Config.pageResource,
        menuResource: Config.menuResource,
        imagePath: '/static/images/cache/:id.:extension'
    };

    /** application controller */
    command = {
        startRouter: { /* start router */
            execute: function () {
                Backbone.history.start({pushState: true});
                if (Backbone.history._hasPushState) {
                    $(document).on('click', 'a', function (evt) {
                        if (this.hash) {
                            router.navigate(this.hash.substr(1), true);
                            evt.preventDefault();
                            return false;
                        }
                    });
                }
            }
        },
        initMenu: { /* initialize main menu */
            execute: function () {
                view.$menu.append(model.get('mainMenu').links.map(function (link) {
                        return $(template.link(link));
                    })).children().each(function () {
                    $(this).after(template.linkSeparator);
                }).parent().children().last().remove();
            }
        },
        showGalleryResource: {
            execute: function () {
                command.hideResource.execute().done(function () {
                    view.$page.html(template.thumbnails({
                        images: model.get('playlist')
                    }));
                    view.$page.slideDown(400);
                    model.set('galleryResourceVisisble', true);
                    view.showResourceImages();
                });
            }
        },
        initGallery: { /*initialize gallery */
            execute: function (image) {
                var deferred = $.Deferred();
                model.set('playlist', model.get('images'));
                model.set('playlistIndex', 0);
                if (image) {
                    model.setCurrentImage(image);
                }
                command.showCurrentImage.execute().done(function () {
                    deferred.resolve();
                });
                return deferred.promise();
            }
        },

        showSummary: { /* show summary */
            execute: function () {
                return view.showSummary();
            }
        },
        hideSummary: {
            execute: function () {
                return view.hideSummary();
            }
        },
        initModel: { /* init model */
            execute: function (images, projects, pages, menus) {
                model.set('images', images);
                model.set('pages', pages);
                model.set('projects', projects);
                model.set('menus', menus);
                model.set('playlist', images);
            }
        },
        hidePage: { /* hide page*/
            execute: function () {
                return view.hidePage();
            }
        },
        /* show page */
        showPage: {
            execute: function () {
                return view.showPage();
            }
        },
        /* first command executed , initialize the front page */
        initPage: {
            execute: function () {
                command.hidePage.execute();
                command.hideGallery.execute();
                $.when(
                        $.getJSON(constant.imageResource),
                        $.getJSON(constant.pageResource),
                        $.getJSON(constant.projectResource),
                        $.getJSON(constant.menuResource)
                    ).done(function (images, pages, projects, menus) {
                        command.initModel.execute(images[0], projects[0], pages[0], menus[0]);
                        command.hideSpinner.execute();
                        command.initMenu.execute();
                        command.startRouter.execute();
                        command.showPage.execute();
                    });
            }
        },
        /* show gallery */
        showGallery: {
            execute: function () {
                var deferred = $.Deferred();
                if (model.get('galleryVisible') === false) {
                    model.set('galleryVisible', true);
                    view.$gallery.slideDown(400, deferred.resolve.bind(deferred));
                }
                setTimeout(deferred.resolve.bind(deferred), 1);
                return deferred;
            }
        },
        /* hide gallery */
        hideGallery: {
            execute: function () {
                var deferred = $.Deferred();
                if (model.get('galleryVisible') === true) {
                    model.set('galleryVisible', false);
                    view.$gallery.slideUp(400, deferred.resolve.bind(deferred));
                }
                setTimeout(deferred.resolve.bind(deferred), 1);
                return deferred;
            }
        },
        /* hide image */
        hideImage: {
            execute: function () {
                var deferred = $.Deferred();
                command.hideSummary.execute().done(function () {
                    view.$gallery.find('figure').fadeOut(300, function () {
                        deferred.resolve();
                    });
                });
                return deferred.promise();
            }
        },
        /* show gallery image */
        showImage: {
            execute: function (img) {
                model.set('transition', true);
                return command.hideImage.execute().pipe(command.showGallery.execute()).done(function () {
                    view.$gallery.find('figure').html(img);
                    view.$gallery.find('figure').fadeIn(200, function () {
                        view.$summary.html(template.summary(model.getCurrentImage()));
                        view.$summary.slideDown(400);
                        model.set('transition', false);
                    });
                });
            }
        },
        showCurrentImage: { /** show current image */
        execute: function () {
            var image = model.getCurrentImage() || model.getFirstImageInPlaylist();
            return command.loadImageById.execute(image.id).done(function (img) {
                command.showImage.execute(img);
            });
        }
        },
        /* move to next image */
        showNextImage: {
            canExecute: function () {
                return model.get('transition') === false;
            },
            execute: function () {
                var image, route;
                if (!this.canExecute()) {
                    return;
                }
                image = model.getNextImage();
                if (model.get('currentProject')) {
                    route = '#project/' + model.get('currentProject').id + "/image/" + image.id + '/' + image.title;
                } else {
                    route = '#image/' + image.id + '/' + image.title;
                }
                router.navigate(route, {
                    trigger: true
                });
            }
        },
        /* move to previous image */
        showPreviousImage: {
            canExecute: function () {
                return model.get('transition') === false;
            },
            execute: function () {
                var image, route;
                if (!this.canExecute()) {
                    return;
                }
                image = model.getPreviousImage();
                if (model.get('currentProject')) {
                    route = '#project/' + model.get('currentProject').id + "/image/" + image.id + '/' + image.title;
                } else {
                    route = '#image/' + image.id + '/' + image.title;
                }
                router.navigate(route, {
                    trigger: true
                });
            }
        },
        /* show loading spinner */
        showSpinner: {
            execute: function () {
                return view.showSpinner();
            }
        },
        /* hide loading spinner */
        hideSpinner: {
            execute: function () {
                return view.hideSpinner();
            }
        },
        /* show subnav */
        showSubMenu: {
            execute: function () {
                return view.showSubMenu();
            }
        },
        /** hide subnav */
        hideSubMenu: {
            execute: function () {
                return view.hideSubMenu();
            }
        },
        /** toggle subnav */
        toggleSubMenu: {
            execute: function (subMenu) {
                return view.toggleSubMenu(subMenu);
            }
        },
        /* show a resource */
        showResource: {
            execute: function (type, id) {
                var resource = model.get(type + 's').filter(function (r) {
                    return r.id === id;
                }).pop();
                return view.showResource(type, resource);
            }
        },
        hideResource: {
            execute: function () {
                return view.hideResource();
            }
        },
        loadImageById: {
            execute: function (id) {
                var deferred, image, src;
                deferred = $.Deferred();
                image = model.get('images').filter(function (img) {
                    return img.id === id;
                }).pop();
                if (image) {
                    src = util.getImageSrc(image.id, image.extension);
                    util.loadImage(src, function (err, img) {
                        if (err) {
                            $log('error loading image', src, err);
                            deferred.resolve(new Image());
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
    /** dispatch event between layers of application */
    mediator = new Backbone.Mediator({
        'load.image': function (transition) {
            if (transition) {
                command.showSpinner.execute();
            } else {
                command.hideSpinner.execute();
            }
        },
        'click.previous': function () {
            command.showPreviousImage.execute();
        },
        'click.next': function () {
            command.showNextImage.execute();
        },
        'click.menu': function (subMenuId) {
            var menu = model.getMenuById(subMenuId);
            command.toggleSubMenu.execute(menu);
        }
    });
    /** route links in the page */
    Router = Backbone.Router.extend({
        routes: {
            "project/:id(/:title)": "project",
            "project/:projectId/image/:imageId(/:title)": "projectImage",
            "page/:id(/:title)": "page",
            "image/:id(/:title)": "index",
            "": "index"
        },
        index: function (id) {
            command.hideSubMenu.execute();
            command.initGallery
                .execute(model.getImageById(id))
                .done(function () {
                    if (model.get('currentProject') || !model.get('galleryResourceVisisble')) {
                        model.set('currentProject', null);
                        command.showGalleryResource.execute();
                    }
                });
        },
        project: function (projectId) {
            var project;
            project = model.getProjectById(projectId);
            if (project) {
                command.hideSubMenu.execute();
                command.showResource.execute("project", projectId);
                model.set('currentProject', project);
                model.setCurrentImage(model.getFirstImageInPlaylist());
                command.showCurrentImage.execute();
            }
        },
        projectImage: function (projectId, imageId) {
            var project;
            project = model.getProjectById(projectId);
            if (project) {
                command.hideSubMenu.execute();
                if (!model.get('currentProject') || (model.get('currentProject').id !== projectId)) {
                    model.set('currentProject', project);
                    command.showResource.execute("project", projectId);
                }
                model.setCurrentImage(model.getImageById(imageId));
                command.showCurrentImage.execute();
            }
        },
        page: function (id) {
            command.hideGallery.execute();
            command.hideSubMenu.execute();
            command.showResource.execute('page', id);
        }
    });
    /** html templates */
    template = {
        /*thumbnails in the bottom of the sceen */
        thumbnails: _.template('<div>\
                                    <% _.each(images,function(image){ %>\
                                        <figure class="thumbnail">\
                                            <a href="#image/<%-image.id%>/<%-_.slug(image.title)%>">\
                                                <img class="thumb" data-src="/static/images/cache/<%-image.id%>.<%-image.extension%>"/>\
                                            </a>\
                                        </figure>\
                                    <% }); %>\
                                </div>\
                                <div class="space">&nbsp;</div>'),
        /* project links in the menu  */
        link: _.template('  <li>\
                                <a data-id="<%-id%>" data-item-id="<%-itemId%>" class="<%-type%>" \
                                    <% if(type!="menu"){%> href="#<%-type%>/<%-itemId%>/<%-_.slug(title)%>" <% }else{ %> href="javascript:void 0;" <% } %> >\
                                    <%-title%>\
                                </a>\
                            </li>'),

        linkSeparator: '<li class="separator">&nbsp;</li>',

        page: _.template('<div class="page">\
                                <h2 class="primary"><%-title%></h2>\
                                <div class="markdown"><%=markdown%></div>\
                                <div class="space">&nbsp;</div>\
                            </div>'),
        /* a project description,sitting at the bottom of the page */
        project: _.template('<div class="project">\
                                    <h2 class="primary inline"><%-title%></h2>\
                                    <section>\
                                        <% _.each(images,function(image){ %>\
                                            <figure class="stripped thumbnail">\
                                                <a href="#/project/<%-id%>/image/<%-_.slug(image.id)%>">\
                                                    <img class="thumb" data-src="/static/images/cache/<%-image.id%>.<%-image.extension%>"/>\
                                                </a>\
                                            </figure>\
                                        <% }); %>\
                                    </section>\
                                    <div class="markdown"><%=markdown%></div>\
                                    <div class="space">&nbsp;</div>\
                            </div>'),
        /*project summary inside the slide-show*/
        summary: _.template('<summary>\
                                <h4 class="inline primary"><%-title%></h4>\
                            </summary>\
                            <hr>\
                            <p>\
                                Project: <a href="#project/<%-project.id%>"><%-_.slug(project.title)%></a><br>\
                                Client: <%-project.client%><br>\
                                <%-description%>\
                            </p>'),

        main: _.template('  <!--HEADER -->\
                            <section class=" head">\
                                <header class="container">\
                                    <nav>\
                                        <ul id="main-menu" class="nav">\
                                        </ul>\
                                        <ul id="sub-menu" class="nav-sub">\
                                        </ul>\
                                    </nav>\
                                    <hgroup>\
                                        <h1 class="primary"><a href="#/"><%- settings.title %></a></h1>\
                                        <h5 class="text-muted">\
                                    <%- settings.subtitle %>\
                                        </h5>\
                                    </hgroup>\
                                </header>\
                            </section>\
                            <section class="gallery">\
                                <section id="gallery" class="container">\
                                    <figure><img/></figure>\
                                    <div class="controls">\
                                        <button class="previous"></button>\
                                        <button class="next"></button>\
                                    </div>\
                                    <!-- SUMMARY-->\
                                    <details id="detail" class="summary" open>\
                                    </details>\
                                    <!--ENDSUMMARY-->\
                                </section>\
                            </section>\
                            <section class="resource">\
                                <div id="page" class="container"></div>\
                            </section>\
                            <footer class="container">\
                                <section class="small">\
                                    <hr>\
                                    <div class="left">\
                                        &copy; <% print((new Date).getFullYear()) %> Marc Paraiso. Ksenia Pirovskikh.<br>\
                                        programming - marc paraiso : <a href="mailto:mparaiso@online.fr">mparaiso@online.fr</a>\
                                    </div>\
                                    <div class="right">\
                                        <a title="Share on facebook" class="share facebook" href="javascript:void 0;"><i class="icon icon-facebook"></i></a>\
                                        <a title="Share on twitter" class="share twitter" href="http://twitter.com/share" target="_blank"><i class="icon icon-twitter"></i></a>\
                                    </div>\
                                </section>\
                            </footer>')
    };
    View = Backbone.View.extend({
        events: {
            "click .next": "nextClicked",
            'click .previous': "previousClicked",
            "click .menu": 'menuClicked',
            "click .share.facebook": "shareFacebook"
        },
        el: '#main',
        template: template.main,
        initialize: function (options) {
            this.viewModel = new Backbone.Model(options.model);
            this.listenTo(this.viewModel, 'change', function () {
                console.log("model changed", arguments);
            });
        },
        render: function (callback) {
            this.$el.html(this.template(this.viewModel.toJSON()));
            this.$spinner = $('#spinner');
            this.$gallery = this.$el.find('#gallery');
            this.$summary = this.$el.find('.summary');
            this.$menu = this.$el.find('#main-menu');
            this.$subNav = this.$el.find('.nav-sub');
            this.$page = this.$el.find('#page');
            if (callback instanceof Function) {
                callback.call(this);
            }
            return this;
        },
        menuClicked: function (e) {
            var subMenuId = this.$(e.currentTarget).data('item-id');
            this.trigger('click.menu', subMenuId);
        },
        nextClicked: function () {
            this.trigger('click.next');
        },
        previousClicked: function () {
            this.trigger('click.previous');
        },
        shareFacebook: function (evt) {
            window.open("http://www.facebook.com/sharer/sharer.php?u=" + window.location.href, "_blank");
            evt.stopPropagation();
            evt.preventDefault();
            return false;
        },
        showSummary: function () {
            var deferred = $.Deferred();
            this.$summary.show(700, function () {
                deferred.resolve();
            });
            return deferred.promise();
        },
        hideSummary: function () {
            var deferred = $.Deferred();
            this.$summary.slideUp(500, function () {
                deferred.resolve();
            });
            return deferred.promise();
        },
        hidePage: function () {
            this.showSpinner();
            this.$el.hide();
            this.hideSubMenu();
        },
        /* show page */
        showPage: function () {
            this.$el.fadeIn(700);
            this.hideSpinner();
        },
        showSpinner: function () {
            this.$spinner.removeClass('hidden');
        },
        /* hide loading spinner */
        hideSpinner: function () {
            this.$spinner.addClass('hidden');
        },
        showSubMenu: function () {
            if (this.viewModel.get('subNav.hidden')) {
                this.$subNav.slideDown();
                this.viewModel.set('subNav.hidden', false);
            }
        },
        /** hide subnav */
        hideSubMenu: function () {
            if (!this.viewModel.get('subNav.hidden')) {
                this.$subNav.slideUp();
                this.viewModel.set('subNav.hidden', true);
            }
        },
        toggleSubMenu: function (subMenu) {
            if (this.viewModel.get('subNav.hidden')) {
                this.showSubMenu();
            } else {
                this.hideSubMenu();
            }
            view.$subNav.html(subMenu.links.map(
                function (link) {
                    return template.link(link);
                }));
        },
        /* show a resource */
        showResource: function (type, resource) {
            this.hideResource().done((function () {
                // cache desc or content 
                if (!resource.markdown) {
                    resource.markdown = markdown.makeHtml(_.escape(resource.description));
                }
                view.$page.html(template[type](resource));
                view.$page.slideDown(700);
                this.viewModel.set('resourceVisible', true);
                this.showResourceImages();
            }).bind(this));
        },
        hideResource: function () {
            var deferred = $.Deferred();
            if (this.viewModel.get('resourceVisible') === true) {
                this.viewModel.set('resourceVisible', false);
                this.$page.slideUp(500, deferred.resolve.bind(deferred));
            } else {
                setTimeout(deferred.resolve.bind(deferred), 1);
            }
            return deferred.promise();

        },
        showResourceImages: function () {
            this.$page.find('img.thumb').each(function (index) {
                var $this = $(this);
                $this.parent().hide();
                setTimeout(
                    util.loadImage.bind(null, $this.data('src'), function (err, image) {
                        $this.parent().hide();
                        $this.replaceWith(image);
                        $(image).parent().fadeIn();
                    }), 50 * index);
            });
        },
    });
    /** log function,can be turned off */
    $log = function () {
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
            _.slug = slug;
            markdown = new Showdown.converter();
            model = new Model();
            model.on('change:transition', function (model, transition) {
                mediator.trigger('load.image', transition);
            });
            router = new Router();
            view = new View({
                model: {
                    settings: constant.config,
                    resourceVisible: true
                }
            });
            view.render(function () {
                command.initPage.execute();
            })
                .on('click.next', function () {
                    mediator.trigger('click.next');
                })
                .on('click.previous', function () {
                    mediator.trigger('click.previous');
                })
                .on('click.menu', function (subMenuId) {
                    mediator.trigger('click.menu', subMenuId);
                });

        }
    }());
});
