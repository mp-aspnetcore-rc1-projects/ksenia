/*jslint white:true,unparam:true,sloppy:true,devel:true,nomen:true*/
/*global angular,jQuery,Config,_*/
/**
 * @2014 mparaiso
 * @licence all rights reserved
 * @dependencies angularjs,underscore,jquery
 * manage the menu widget to order and create links
 */
(function() {
    "use strict";
    angular.module('MenuForm', ['ngAnimate', 'ngResource'])
        /*content editable directive*/
        .directive('contenteditable',function(){
            return{
                restrict:'A',//restrict to attributes
                require:'?ngModel',//get NgModelController
                link:function($scope,elm,attrs,ngModel){
                    var read;
                    if(!ngModel){return;}
                    ngModel.$render=function(){
                        elm.html(ngModel.$viewValue||'');
                    };
                    elm.on('blur keyup change',function(){
                        $scope.$apply(read);
                    });
                    read=function(){
                        var html=elm.html();
                        if(attrs.stripBr && html==="<br>"){
                            html="";
                        }
                        html=html.replace(/[\n \b]*/g,"");
                        ngModel.$setViewValue(html);
                    };
                }
            };
        })
        .directive('myDrop', function() {
            /**
             * creates a drop target.
             * pass a json object to the callback function
             * ex : <div my-drop="callback" ></div>
             */
            return {
                link: function($scope, elm, attrs) {
                    var dragover, dragleave, dragenter, dragend, drop;
                    /** something is being dragged over the element */
                    dragover = function(event) {
                        event.preventDefault();
                        if (event.dataTransfer) {
                            event.dataTransfer.dropEffect = 'move';
                        }
                        return false;
                    };
                    /** something dragged enters the element */
                    dragenter = function() {
                        elm.addClass("ng-dragged-over");
                    };
                    /** something dragged leaves element */
                    dragleave = function(event) {
                        event.preventDefault();
                        elm.removeClass("ng-dragged-over");
                    };

                    dragend = function() {
                        elm.removeClass('ng-dragged-over');
                    };
                    /** something is dropped on the element */
                    drop = function(event) {
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
        .directive("myDraggable", function() {
            /**
             * make an element draggable ,
             * pass data through data attribute of the tag,
             * the data object will be serialized as an application/json string
             * applies the class .ng-dragged when element is dragged.
             * ex : <div my-draggable data="{foo:'bar',baz:'fizz'}"></div>
             */
            return {
                scope: {
                    /** make object passed in attribute data available to the scope */
                    data: "="
                },
                link: function($scope, elm) {
                    var dragstart, dragend;
                    dragstart = function(e) { /* on drag */
                        /** @var {object} dt data transfer */
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
                    dragend = function() { /* on release */
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
        .factory('Config', function() {
            return Config;
        })
        .factory('MenuResource', function(Config, $resource) {
            return $resource(Config.menuResource, {}, {
                query: {
                    method: 'GET',
                    isArray: true,
                    transformResponse: function(string) {
                        return angular.fromJson(string).map(function(p) {
                            p.type = "menu";
                            return p;
                        }).filter(function(m){
                            return m.id !== Config.menu.id;
                        });
                    }
                }
            });
        })
        .factory('PageResource', function(Config, $resource) {
            return $resource(Config.pageResource, {}, {
                query: {
                    method: 'GET',
                    isArray: true,
                    transformResponse: function(string) {
                        return angular.fromJson(string).map(function(p) {
                            p.type = "page";
                            return p;
                        });
                    }
                }
            });
        })
        .factory('ProjectResource', function(Config, $resource) {
            return $resource(Config.projectResource, {}, {
                query: {
                    method: 'GET',
                    isArray: true,
                    transformResponse: function(string) {
                        return angular.fromJson(string).map(function(p) {
                            p.type = "project";
                            return p;
                        });

                    }
                }
            });
        })
        .factory('Menu',function(MenuResource){
            return MenuResource.query();
        })
        .factory('Page', function(PageResource) {
            return PageResource.query();
        })
        .factory('Project', function(ProjectResource) {
            return ProjectResource.query();
        })
        .factory('Link', function($document, $log) {
            var $menuLinks, links;
            $menuLinks = $document.find('#menu_links');
            /** get links from input field */
            links = angular.fromJson($menuLinks.val() || "[]");
            return {
                links: links,
                /** change #menu_links[type=hidden] value to current links */
                $updateForm: function(linkCollection) {
                    var _links = angular.toJson(linkCollection);
                    $menuLinks.val(_links);
                },
                /** send the form */
                $sendForm: function() {
                    $document.find('form').submit();
                }
            };
        })
        .controller('MenuFormCtrl', function(Project, Page, Link,Menu, $scope, $filter) {
            /**
             * Menu Form Widget, allow drag and droping and re-ordering items in menu
             * @param Project projects
             * @param Page pages
             * @param Link links
             * @param $scope current scope
             * @param $filter service
             */
            $scope.links = Link.links.map(function(l) {
                l.cid = _.uniqueId('links_');
                return l;
            });
            $scope.items = {
                pages: Page,
                projects: Project,
                menus:Menu
            };
            $scope.activeTab = Object.keys($scope.items)[0];
            $scope.$watchCollection('links', function(newValue) {
                $scope.menu_links = $filter('json')(newValue);
            });
            /** submit form */
            $scope.sendForm = function() {
                Link.$updateForm($scope.links);
                Link.$sendForm();
            };
            /** add links */
            $scope.addLink = function(item) {
                $scope.addLinkAt(item, $scope.links.length);
            };
            /** add link at specified index */
            $scope.addLinkAt = function(item, index) {
                var _item;
                if (item.cid) { /*remove link from links and get it */
                    _item = $scope.removeLink(item);
                } else { /*create item */
                    _item = $scope.createLink(item);
                }
                $scope.links.splice(index, 0, _item);
            };
            /* create a new link from data */
            $scope.createLink = function(item) {
                return {
                    cid: _.uniqueId('links_'),
                    type: item.type,
                    title: item.title,
                    description: item.description,
                    itemId: item.id
                };
            };
            /* remove link from menu */
            $scope.removeLink = function(link) {
                if (link.cid) {
                    var _item = $scope.links.filter(function(i) {
                        return i.cid === link.cid;
                    }).pop();
                    return $scope.links.splice($scope.links.indexOf(_item), 1).pop();
                }
                return null;
            };
            $scope.drop = function(item) {
                $scope.addLink(item);
                $scope.$apply('Link');
            };
            $scope.onDropLink = function(item) {
                $scope.addLinkAt(item, this.$index);
                $scope.$apply('Link');
            };
            $scope.activate = function(tab) {
                $scope.activeTab = tab;
            };
            $scope.isActive = function(tab) {
                return tab === $scope.activeTab ? "active" : "";
            };
        });
}());