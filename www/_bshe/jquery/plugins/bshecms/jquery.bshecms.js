/**
 * Bshe B Smart HTML Extender
 *
 * http://www.bshe.org
 * Yuichiro Abe <bshe@bshe.org>
 * copyright: since 2008,all rights reserved
 *
 * @category   Bshe
 * @package    Bshe_View
 * @copyright  since 2008,all rights reserved Yuichiro Abe (http://www.bshe.org)
 * @license    LGPL
 */

;(function($) {

	    $.fn['bsheCms'] = function(options) {
	        //いったん退避
	        var elements = this;

	        //設定情報の構築
	        var settings = $.extend({
	            //optionの初期値を設定
	            'param' : 'value'
	        }, options);

	        //要素を一個ずつ処理
	        elements.each(function() {

	        	// textコントローラー作成
	            var menuDiv = document.createElement("div");

	            var menuTop = $(this).offset().top;
	            var menuLeft = $(this).offset().left;

	            var menuHTML = "";
	            menuHTML += "<div class='LTsun LTSunTextControlDiv bshe_cms_menu' id='bshe_cms_text__" + $(this).attr('id') + "'></div>";
	            menuDiv.innerHTML = menuHTML;

	            menuDiv.style.position = "absolute";
	            menuDiv.style.overflow = "hidden";
	            menuDiv.style.top = menuTop + 'px';
	            menuDiv.style.zIndex = 50;
	            menuDiv.style.left = menuLeft + 'px';
	            menuDiv.style.height = '30px';

	            menuDiv.changeOpacity = function(opacity) {
	            	this.style.opacity = (opacity / 100);
	            	this.style.MozOpacity = (opacity / 100);
	            	this.style.KhtmlOpacity = (opacity / 100);
	            	this.style.filter = "alpha(opacity=" + opacity + ")";
		    	};

		    	menuDiv.changeOpacity(30);

	            menuDiv.onmouseover = function() {
	            	this.style.height = '30px';
	            	this.changeOpacity(100);
	            };
	            menuDiv.onmouseout = function() {
	            	this.style.height = '30px';
	            	this.changeOpacity(30);
	            };

	            document.body.appendChild(menuDiv);

	            // 直接編集エリア生成
	            $(this).attr("contenteditable","true");
	        	if( $(this).html() ) {
	        		$(this).html($(this).html());
	        	}

	        	$(this).updateTextModuleHTML = function(html, saveOkay) {
	        		$(this).text(html);
	        		if(saveOkay) LTSun.saveTextModule(LTSunSettings['bshe_indexphp_path'] + "/text/noinc-save.html", elementId, document.getElementById(elementId).innerHTML, true, null, null);
	        		LTSun.hideWindow({});
	        		document.getElementById(elementId).focus();
	        	};

	        });
	        //method chain
	        return this;
	    };


})(jQuery);
