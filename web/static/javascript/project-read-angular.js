/*global jQuery,Config*/
/**
 * IMAGE MANAGEMENT SCRIPT FOR project-read route
 * @2013 mparaiso
 */
"use strict";
/*global angular*/
var App = angular.module('ProjectRead', ['ngResource'])
	.factory('Config', function() {
		return Config;
	})
	.factory('Image', function($resource, Config) {
		console.log(Config.imageResource);
		return $resource(
			Config.imageResource + "/:id", { /*config*/
				id:"@id",
				project: Config.projectId
			}, { /*actions*/
				publish:{
					url:Config.imagePublish,
					method:"POST"
				},
				query: {
					method: 'GET',
					isArray: true,
					transformResponse: function(string) {
						return angular.fromJson(string).images;
					}
				}
			});
	});

function ImageCollectionCtrl($scope, Config, Image, $log) {
	$scope.publish = function(image) {
		image.isPublished = !image.isPublished;
		Image.publish({id:image.id},function(data){
			$log.info(arguments);
		});
	};
	$scope.remove = function(image) {
		$scope.images = $scope.images.filter(function(im) {
			return im !== image;
		});
		Image.delete(image);
	};
	$scope.imageHref = function(image) {
		return Config.imageHref.replace(':id', image.id);
	};
	$scope.imageSrc = function(image) {
		return Config.imageSrc.replace(':id', image.id);
	};
	var init = function($scope) {
		$scope.images = Image.query();
	}
	init($scope);
}