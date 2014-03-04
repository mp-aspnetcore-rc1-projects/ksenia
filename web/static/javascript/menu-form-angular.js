/*jslint white:true,unparam:true,sloppy:true*/
/*global angular,jQuery,Config*/
/**
 * IMAGE MANAGEMENT SCRIPT FOR project-read route
 * @2013 mparaiso
 * follows the mvc pattern
 * with the controller distributed between the mediator and command
 */
(function () {
    "use strict";

    angular.module('MenuForm', ['ngAnimate', 'ngResource'])
        .directive('myDrop', function () {
            return {
                link: function ($scope, elm, attrs) {
                    var dragover, dragleave, dragenter, dragend, drop;
                    dragover = function (event) {
                        event.preventDefault();
                        if (event.dataTransfer) {
                            event.dataTransfer.dropEffect = 'move';
                        }
                        return false;
                    };
                    dragenter = function () {
                        elm.addClass("ng-dragged-over");
                    };
                    dragleave = function (event) {
                        event.preventDefault();
                        elm.removeClass("ng-dragged-over");
                    };
                    dragend = function () {
                        elm.removeClass('ng-dragged-over');
                    };
                    drop = function (event) {
                        var dt;
                        event.preventDefault();
                        event.stopPropagation();
                        elm.removeClass("ng-dragged-over");
                        if ($scope[attrs.myDrop] instanceof Function) {
                            if (event.originalEvent.dataTransfer) {
                                dt = event.originalEvent.dataTransfer;
                                $scope[attrs.myDrop](angular.fromJson(dt.getData('application/json')));
                            }
                        }
                    };
                    /** register listeners */
                    elm.on('dragover', dragover);
                    elm.on('dragenter', dragenter);
                    elm.on('dragleave', dragleave);
                    elm.on('drop', drop);
                    elm.on('dragend', dragend);
                }
            };
        })
        .directive("myDraggable", function () {
            /** make an element draggable */
            return {
                scope: {
                    /** make object passed in attribute data available to the scope */
                    data: "="
                },
                link: function ($scope, elm) {
                    var dragstart, dragend;
                    dragstart = function (e) { /* on drag */
                        var dt;
                        if (e.originalEvent.dataTransfer) {
                            dt = e.originalEvent.dataTransfer;
                            dt.effectAllowed = 'move';
                            if ($scope.data) {
                                dt.setData('application/json', angular.toJson($scope.data));
                            }
                        }
                        elm.off('dragstart', dragstart);
                        elm.addClass('ng-dragged');
                        elm.on('dragend', dragend);
                    };
                    dragend = function () { /* on release */
                        elm.off('dragend', dragend);
                        elm.removeClass('ng-dragged');
                        elm.on('dragstart', dragstart);
                    };
                    /** register event listeners */
                    elm.attr('draggable', true);
                    elm.on('dragstart', dragstart);
                }
            };
        })
        .factory('Config', function () {
            return Config;
        })
        .factory('PageResource', function (Config, $resource) {
            return $resource(Config.pageResource, {}, {
                query: {method: 'GET', isArray: true, transformResponse: function (string) {
                    return angular.fromJson(string).pages.map(function (p) {
                        p.type = "page";
                        return p;
                    });
                }}
            });
        })
        .factory('ProjectResource', function (Config, $resource) {
            return $resource(Config.projectResource, {}, {
                query: {method: 'GET', isArray: true, transformResponse: function (string) {
                    return angular.fromJson(string).projects.map(function (p) {
                        p.type = "project";
                        return p;
                    });

                }}
            });
        })
        .factory('Page', function (PageResource) {
            return PageResource.query();
        })
        .factory('Project', function (ProjectResource) {
            return ProjectResource.query();
        })
        .factory('Link', function () {
            return {links: []};
        })
        .controller('MenuFormCtrl', function (Project, Page, Link, $scope, $log) {
            $scope.activeTab = "Projects";
            $scope.projects = Project;
            $scope.pages = Page;
            $scope.Link = Link;
            $scope.removeLink = function (link) {
                $scope.Link.links = $scope.Link.links.filter(function (l) {
                    return !(l === link);
                });
            };
            $scope.drop = function (item) {
                $scope.Link.links.unshift(
                    {type: item.type, title: item.title, description: item.description, itemId: item.id}
                );
                $scope.$apply('Link');
            };
            $scope.activate = function (tab) {
                $scope.activeTab = tab;
            };
            $scope.isActive = function (tab) {
                return tab === $scope.activeTab ? "active" : "";
            };
        });
}());