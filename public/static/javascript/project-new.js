/*jslint browser:true*/
/*global jQuery,escape,_,FileReader,FormData*/
"use strict";
jQuery(function($) {
	
	//console.log('project-new init');
	// input[type=file] view
	var inputTemplate = '<input type="file" accept="image/*" id="files" name="files[]" multiple/>\
	<div id="drop_zone">Drop files here</div>\
	<output id="list"></output>';
	// a file view
	var fileTemplate = '<div class="item" title="{{name}}"></div>';
	// the form
	var $form = $("form[name='project']");
	$form.append(inputTemplate);
	var $output = $("output", $form);
	var $input = $('input[type=file]', $form);
	var $dropZone = $('#drop_zone', $form);
	/**
	 * generate a unique id
	 * @return {String} a unique id
	 */
	var uniqId = function() {
		return _.uniqueId('file_');
	};
	var renderOutput = function(files) {
		/**
		 * renderOutput
		 * @param  {Array} file [description]
		 * @return {Array} an array of jQuery objects
		 */
		return [].slice.call(files).map(function(file) {
			var html = fileTemplate.replace(/(\{\{(\w+)?\}\})/gi, function(a, b, prop) {
				if (file[prop] !== 'undefined') {
					return file[prop];
				}
				return "";
			});
			var $html = $(html).ksenia_upload({
				file: file,
				id: uniqId(),
				url: "/private/upload",
				auto: true
			});
			$html.on('uploaded', function() {
				console.log('uploaded!', arguments);
				var reader = new FileReader();
				reader.onload = function(e){
					var $img= $('<img>',{src:e.target.result});
					if($img.width()<$img.height){
						$img.css({width:"100%"});
					}else{
						$img.css({height:"100%"});
					}
					$img.on('click',function(e){
						window.open($img.attr('src'),file.name);
						return false;
					});
					$html.append($img);
					return false;
				};
				reader.readAsDataURL($html.data('file'));
			});
			return $html;
		});
	};
	// on input
	$form.on('change', $input, function(event) {
		// file is a FileList of File objects
		var files = [].slice.call(event.target.files);
		// append file template to output tag
		$output.html(renderOutput(files));
	});
	$dropZone.css({
	});
	$dropZone.on({
		'dragover': function(event) {
			event.stopPropagation();
			event.preventDefault();
			event.originalEvent.dataTransfer.dropEffect = "copy";
			$(this).toggleClass('dragged-over', true);
		},
		'dragleave': function(event) {
			$(this).toggleClass('dragged-over', false);
		},
		'drop': function(event) {
			$(this).toggleClass('dragged-over', false);
			event.stopPropagation();
			event.preventDefault();
			var files = [].slice.call(event.originalEvent.dataTransfer.files);
			files = files.filter(function(file) {
				//filter in image files
				//console.log(file);
				return file.type.match(/^image\/.*/i);
			});
			$dropZone.html(renderOutput(files));
		}
	});
});