/*jslint es5:true,browser:true,white:true,stupid:true*/
/*global jQuery,Config*/

/**
 * IMAGE MANAGEMENT SCRIPT FOR project-read route
 * @2013 mparaiso
 */
(function () {
    "use strict";
    /*global angular*/
    angular.module('ProjectRead', ['ngResource','ngAnimate'])
        .factory('Config', function () {
            return Config;
        })
        .service('Util', function ($log) {
            this.log = function () {
                $log.info(arguments);
            };
        })
        .factory('ImageResource', function ($resource, Config) {
            return $resource(
                Config.imageResource + "/:id", { /*config*/
                    id: "@id",
                    project: Config.projectId
                }, { /*actions*/
                    publish: {
                        url: Config.imagePublish,
                        method: "POST"
                    },
                    markAsPoster: {
                        url: Config.markAsPoster,
                        method: 'POST'
                    },
                    query: {
                        method: 'GET',
                        isArray: true,
                        transformResponse: function (string) {
                            return angular.fromJson(string).images;
                        }
                    }
                });
        })
        .factory('ProjectResource', function ($resource, Config) {
            return $resource(Config.posterResource + "/:id", {id: "@id"}, {
                get: {
                    method: "GET",
                    url: Config.projectResource,
                    transformResponse: function (string) {
                        return angular.fromJson(string).project;
                    }
                }
            });
        })
        .factory('Project', function (ProjectResource, Util) {
            return ProjectResource.get(Util.log);
        })
        .controller("ProjectCtrl", function ($scope, Config, Project, ImageResource, Util) {
            $scope.config=Config;
            $scope.publish = function (image) {
                image.isPublished = !image.isPublished;
                ImageResource.publish({id: image.id}, Util.log);
            };
            $scope.markAsPoster = function (image) {
                $scope.project.poster = image;
                ImageResource.markAsPoster({id: image.id}, Util.log);
            };
            $scope.isPoster = function (image) {
                if($scope.project.poster)
                    return image.id === $scope.project.poster.id;
            };
            $scope.remove = function (image) {
                $scope.project.images = $scope.project.images.filter(function (im) {
                    return im !== image;
                });
                ImageResource.delete({id: image.id}, Util.log);
            };
            $scope.imageHref = function (image) {
                return Config.imageHref.replace(':id', image.id);
            };
            $scope.imageSrc = function (image) {
                return Config.imageSrc.replace(':id', image.id).concat('.').concat(image.extension);
            };
            $scope.project = Project;
        });
}());