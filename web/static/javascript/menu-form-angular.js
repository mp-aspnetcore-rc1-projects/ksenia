/*global jQuery*/
/**
 * IMAGE MANAGEMENT SCRIPT FOR project-read route
 * @2013 mparaiso
 * follows the mvc pattern
 * with the controller distributed between the mediator and command
 */
"use strict";
(function () {
    angular.module('MenuForm', ['ngAnimate', 'ngResource'])
        .factory('Config', function () {
            return Config;
        })
        .factory('PageResource', function (Config, $resource) {
            return $resource(Config.pageResource, {}, {
                query: {method: 'GET', isArray: true, transformResponse: function (string) {
                    return angular.fromJson(string).pages;
                }}
            });
        })
        .factory('ProjectResource', function (Config, $resource) {
            return $resource(Config.projectResource, {}, {
                query: {method: 'GET', isArray: true, transformResponse: function (string) {
                    return angular.fromJson(string).projects;
                }}
            });
        })
        .factory('Page', function (PageResource) {
            return PageResource.query();
        })
        .factory('Project', function (ProjectResource) {
            return ProjectResource.query();
        })
        .controller('MenuFormCtrl', function (Project, Page, $scope) {
            $scope.activeTab = "Projects";
            $scope.projects = Project;
            $scope.pages = Page;
            $scope.activate = function (tab) {
                $scope.activeTab = tab;
            };
            $scope.isActive = function (tab) {
                return tab == $scope.activeTab ? "active" : "";
            };
        });
}());