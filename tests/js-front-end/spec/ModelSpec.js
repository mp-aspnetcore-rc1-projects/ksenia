/*jslint white:true*/
/*global describe,it,expect,beforeEach,Model,ModelData*/
/**
 * TEST for Model object in front end application
 * @copyrights 2014 mparaiso <mparaiso@online.fr>
 * @dependencies JasmineHTMLSpecRunner , Jasmine 2.*, Backbone, Underscore, ModelData, Model;
 */

describe("Model", function() {
    "use strict";
    beforeEach(function() {
        this.model = new Model({
            images: ModelData.images,
            projects: ModelData.projects,
            menus: ModelData.menus,
            pages: ModelData.pages
        });
    });
    it('should be initialized properly', function() {
        expect(this.model.get('images')).toEqual(ModelData.images);
        expect(this.model.get('projects')).toEqual(ModelData.projects);
        expect(this.model.get('menus')).toEqual(ModelData.menus);
        expect(this.model.get('pages')).toEqual(ModelData.pages);
    });
    describe('change:currentProject event', function() {
        it('should set playlist and playlistIndex', function() {
            this.model.set('currentProject', this.model.get('projects')[0]);
            expect(this.model.get('playlistIndex')).toEqual(0);
            this.model.get('playlist').forEach(function(image) {
                expect(this.model.get('currentProject').images).toContain(image);
            }, this);
        });
    });
    describe('#setCurrentImage', function() {
        beforeEach(function() {
            this.model.set('currentProject', this.model.get('projects')[0]);
        });
        it('should set the right playlist index', function() {
            this.model.setCurrentImage(this.model.get('currentProject').images[1]);
            expect(this.model.get('playlistIndex')).toEqual(1);
            this.model.setCurrentImage(this.model.get('currentProject').images[2]);
            expect(this.model.get('playlistIndex')).toEqual(2);
        });
        it('should set index to 0 when image is not found in playlist', function() {
            this.model.setCurrentImage(this.model.get('projects')[1].images[2]);
            expect(this.model.get('playlistIndex')).toEqual(0);
        });
    });
    describe('#getCurrentImage', function() {
        it('should be null', function() {
            expect(this.model.getCurrentImage()).toBeUndefined();
        });
        it('should be first image to the current project', function() {
            this.model.set('currentProject', this.model.get('projects')[0]);
            expect(this.model.getCurrentImage()).toEqual(this.model.get('currentProject').images[0]);
        });
    });
    describe('#getImageIndexInPlaylist', function() {
        beforeEach(function() {
            this.model.set('playlist', this.model.get('images'));
            this.image0 = this.model.get('images')[0];
            this.imageArrayLength = this.model.get('images').length;
            this.lastImage = this.model.get('images')[this.imageArrayLength - 1];
        });
        it('should be 0', function() {
            expect(this.model.getImageIndexInPlaylist(this.image0)).toEqual(0);
        });
        it('should be the last image index', function() {
            expect(this.model.getImageIndexInPlaylist(this.lastImage)).toEqual(this.imageArrayLength - 1);
        });
    });
    describe('#getImageById', function() {
        beforeEach(function() {
            this.images = this.model.get('images');
            this.id0 = this.images[0].id;
            this.id1 = this.images[1].id;
            this.id2 = this.images[2].id;
        });
        it('should work as expected', function() {
            expect(this.model.getImageById(this.id0)).toEqual(this.images[0]);
            expect(this.model.getImageById(this.id1)).toEqual(this.images[1]);
            expect(this.model.getImageById(this.id2)).toEqual(this.images[2]);
        });
    });
    describe('#getFirstImageInPlaylist', function() {
        beforeEach(function() {
            this.model.set('playlist', this.model.get('images'));
        });
        it('should return the first image in the playlist', function() {
            expect(this.model.getFirstImageInPlaylist()).toEqual(this.model.get('images')[0]);
        });
    });
    describe('#getImageInPlaylistAt', function() {
        beforeEach(function() {
            this.model.set('playlist', this.model.get('images'));
            this.images = this.model.get('images');
        });
        it('should return the right image at index', function() {
            expect(this.model.getImageInPlaylistAt(0)).toEqual(this.images[0]);
            expect(this.model.getImageInPlaylistAt(5)).toEqual(this.images[5]);
            expect(this.model.getImageInPlaylistAt(10)).toEqual(this.images[10]);
        });
    });
    describe('#getPreviousImage', function() {
        beforeEach(function() {
            this.model.set('currentProject', this.model.get('projects')[0]);
            this.projectImagesLength = this.model.get('currentProject').images.length;
        });
        it('should return the previous image', function() {
            expect(this.model.getPreviousImage()).toEqual(this.model.get('currentProject').images[this.projectImagesLength - 1]);
        });
    });
    describe('#getNextImage', function() {
        beforeEach(function() {
            this.model.set('currentProject', this.model.get('projects')[0]);
            this.projectImagesLength = this.model.get('currentProject').images.length;
        });
        it('should return the next image', function() {
            expect(this.model.getNextImage()).toEqual(this.model.get('currentProject').images[1]);
        });
    });
    describe('#getImagesByProject', function() {
        beforeEach(function() {
            this.project = this.model.get('projects')[0];
        });
        it('should return the right list of images', function() {
            expect(this.model.getImagesByProject(this.project).every(function(image) {
                return image.project.id === this.project.id;
            }, this)).toBeTruthy();
        });
    });

    describe("#getProjectById", function() {
        beforeEach(function() {
            this.project = this.model.get('projects')[0];
            this.projectId = this.project.id;
        });
        it('should return the right project', function() {
            expect(this.model.getProjectById(this.projectId)).toEqual(this.project);
        });
    });
    describe("#getLinkById", function() {
        beforeEach(function() {
            console.log(this.model.get('menus'));
            this.link = this.model.get('menus')[0].links[0];
            this.linkId = this.link.id;
        });
        it('should return the right link', function() {
            expect(this.model.getLinkById(this.linkId)).toEqual(this.link);
        });
    });
    describe("#getMenuById", function() {
        beforeEach(function() {
            console.log(this.model.get('menus'));
            this.menu = this.model.get('menus')[0];
            this.menuId = this.menu.id;
        });
        it('should return the right menu', function() {
            expect(this.model.getMenuById(this.menuId)).toEqual(this.menu);
        });
    });
});

/**
 * jasmine matchers
 *
 * expect(a).toBe(b);
 * expect(a).not.toBe(null);
 *
 * expect(a).toEqual(12);
 * expect(foo).toEqual(bar);
 *
 * expect(message).toMatch(/bar/);
 * expect(message).toMatch("bar");
 * expect(message).not.toMatch(/quux/);
 *
 * expect(a.foo).toBeDefined();
 * expect(a.bar).not.toBeDefined();
 *
 * expect(a.foo).not.toBeUndefined();
 * expect(a.bar).toBeUndefined();
 *
 * expect(null).toBeNull();
 * expect(a).toBeNull();
 * expect(foo).not.toBeNull();
 *
 * expect(foo).toBeTruthy();
 * expect(a).not.toBeTruthy();
 *
 * expect(a).toBeFalsy();
 * expect(foo).not.toBeFalsy();
 *
 * expect(a).toContain("bar");
 * expect(a).not.toContain("quux");
 *
 * expect(e).toBeLessThan(pi);
 * expect(pi).not.toBeLessThan(e);
 *
 * expect(pi).toBeGreaterThan(e);
 * expect(e).not.toBeGreaterThan(pi);
 *
 * expect(pi).not.toBeCloseTo(e, 2);
 * expect(pi).toBeCloseTo(e, 0);
 *
 * expect(foo).not.toThrow();
 * expect(bar).toThrow();
 */