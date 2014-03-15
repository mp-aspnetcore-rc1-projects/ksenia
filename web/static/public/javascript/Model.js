/*jslint nomen:true,white:true,devel:true*/
/*global Backbone,_*/
/** manage application state */
(function () {
    "use strict";
    this.Model = Backbone.Model.extend({
        initialize: function (options) {
            this.on('change:menus', function (model) {
                var menus, menu;
                menus = this.get('menus');
                menu = _(menus).find(function (m) {
                    return m.isMain;
                });
                if (!menu) {
                    menu = menus[0];
                }
                this.set('mainMenu', menu);
            });
            this.on('change:currentProject', function (model, currentProject) {
                if (currentProject) {
                    this.set('playlist', this.getImagesByProject(currentProject));
                    this.set('playlistIndex', 0);
                }
            });
        },
        setCurrentImage: function (image) {
            if (!image) {
                return;
            }
            var index = this.getImageIndexInPlaylist(image);
            if (index < 0) {
                index = 0;
                console.log(image, " is not contained in current playlist", this.get('playlist'));
            }
            this.set('playlistIndex', index);
        },
        getCurrentImage: function () { /* get the current image displayed in playlist */
            return this.getImageInPlaylistAt(this.get('playlistIndex'));
        },
        getImageIndexInPlaylist: function (image) { /* get the index of an image in the playlist */
            return this.get('playlist').indexOf(this.getImageById(image.id));
        },
        getImageById: function (id) { /* get image by image id */
            return _(this.get('images')).find(function (img) {
                return id === img.id;
            });
        },
        getFirstImageInPlaylist: function () {
            return this.getImageInPlaylistAt(0);
        },
        getImageInPlaylistAt: function (index) {
            return this.get('playlist')[index];
        },
        getPreviousImage: function () { /* get previous image in current playlist */
            var index;
            index = (this.get('playlistIndex') - 1) % this.get('playlist').length;
            if (index < 0) {
                index = index + this.get('playlist').length;
            }
            this.set('playlistIndex', index);
            return this.getCurrentImage();
        },
        getNextImage: function () { /* get next image in current playlist */
            this.set('playlistIndex', (this.get('playlistIndex') + 1) % this.get('playlist').length);
            return this.getCurrentImage();
        },
        getImagesByProject: function (project) { /* find all images by project id */
            return this.get('images').filter(function (img) {
                return img.project.id.toString() === project.id.toString();
            });
        },
        getProjectById: function (id) {
            return _(this.get('projects')).find(function (project) {
                return project.id === id;
            });
        },
        defaults: {
            playlistIndex: 0,
            images: [], // all images
            playlist: [], // gallery imageList
            menus: [], // all menus
            pages: [], // all pages
            projects: [], // all projects
            galleryVisible: false, //is gallery showing
            transition: false, //is image transition happening
            resourceVisible: true //is page visible
        }
    });
}).call(this);

