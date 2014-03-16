/*global jQuery,Showdown,document*/
/**
 * @dependencies Showdown,jQuery;
 * Add markdown preview to text areas markdown
 */
jQuery(function($) {
	"use strict";
	var xscape = function(string){
		var i=document.createElement('i');
		i.innerHTML=string;
		return i.innerText;
	};
	var markdown = new Showdown.converter();
	var label = '	<label>Markdown Preview for :field </label>\
					<p class="help-text">For help on markdown syntax,please read the following: \
						<a target="_blank" href="http://bywordapp.com/markdown/syntax.html">Markdown syntax guide</a>\
					</p>';
	/**
	 * Add markdown preview
	 */
	$('[data-markdown-preview]').each(function() {
		console.log('markdown');
		var $this = $(this);
		var field=$this.data('markdown-preview')||null; // if value passed to attribute get it
		// create markdown preview
		var $target = $('<div>',{class:'form-control markdown-preview',style:'overflow:auto;',height:$this.height()})
			.data({
				origin: $this
			}).html(markdown.makeHtml(xscape($this.val())));
		$this.on('keyup', function() {
			$target.html(markdown.makeHtml(xscape($this.val())));
		});
		$this.on('mouseup',function(){
			$target.height($this.height());
		});
		$this.parent().parent().after($('<div>',{class:'form-group'}).append([$(label.replace(':field',field)), $target]));
	});
	/**
	 * Render element text to markdown
	 */
	$('[data-to-markdown]').each(function(){
		var $this=$(this);
		$this.html(markdown.makeHtml(xscape($this.text())));
	});
});