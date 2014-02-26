/*jslint browser:true*/
/*global jQuery*/
"use strict";
(function($) {
	/**
	 * Return a dom object representing a file to upload
	 * @return {jqObject}
	 * @param {Object} options upload options
	 */
	$.fn.ksenia_upload = (function() {
		/**
		 * Handle file upload
		 * @return {Promise} return an ajax promise
		 */
		var _upload = function() {
			var self = this;
			var formData = new FormData();
			var data = this.data();
			var file = data.file;
			console.log("upload", file);
			formData.append('file', file);
			formData.append('title', file.title);
			return $.ajax({
				url: data.url,
				data: formData,
				processData: false,
				contentType: false,
				/* @see http://stackoverflow.com/questions/166221/how-can-i-upload-files-asynchronously-with-jquery */
				xhr:function(){ //custom xhr
					var xhr = $.ajaxSettings.xhr();
					if(xhr.upload){ //if upload property
						xhr.upload.addEventListener('progress',function(event){
							self.trigger('progress',event);
						});
					}
					return xhr;
				},
				type: 'POST',
				success: function(res) {
					self.trigger('upload', res);
				},
				error: function(err) {
					self.trigger('error', [].slice.call(arguments));
				}
			});
		};
		return function(options) {
			options = $.extend({}, options);
			this.each(function() {
				var $this = $(this);
				$this.data(options);
				$this.upload = _upload;
				if (options.auto === true) {
					$this.upload();
				}
			});
			return this;
		};
	}());
}(jQuery));
/*
(function($){
	$.fn.ksenia_dropzone = function(options){
		var enabled = (function(){
			if(window.File && window.FileReader && window.FileList && window.Blob){
				return true;
			}
			console.log('drag-n-drop wont work');
			return false;
		}());
		var settings = $.extend({},options);
		return this.each(function(){
			return this;
		});
	};
}(jQuery));
*/