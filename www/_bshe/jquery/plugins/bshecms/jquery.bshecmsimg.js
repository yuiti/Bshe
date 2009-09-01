/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * copyright: since 2008,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_View
 * @copyright  since 2009,all rights reserved Yuichiro Abe (http://www.bshe.org)
 */

;(function($) {

	    $.fn['bsheCmsImg'] = function(options) {
	        //いったん退避
	        var elements = this;

	        //設定情報の構築
	        var settings = $.extend({
	            //optionの初期値を設定
	            'param' : 'value'
	        }, options);

	        //要素を一個ずつ処理
	        elements.each(function() {

	        	var target = this;


	            $(this).mouseover(function() {
	            	$(this).fadeTo("slow", 0.30);
	            	return $(this);
	            });
	            
	            $(this).mouseout(function() {
		        	$(this).fadeTo("slow", 1);
	            	return $(this);
	            });
	            
	            $(this).replace = function() {
	            	$(this).fadeTo("slow", 1);
	            	$(this).appendTo($(document.body));
	            }

	            this.updateImage = function(imageFileName, imgTitle)
	            {
	            	this.src = imageFileName;
	            	this.alt = imgTitle;
	            	SexyLightbox.display(0);
	        		this.focus();
	            }

	        	// 保存公開
	        	this.publishImageModule = function() {
	        		xajax_publishImage(bshe_templatename, this.id);
	        	}

	        	// 元に戻す
	        	this.undoImageModule = function() {
	        		xajax_undoImage(bshe_templatename, this.id);
	        	}
	        });
	        //method chain
	        return this;
	    };


})(jQuery);
