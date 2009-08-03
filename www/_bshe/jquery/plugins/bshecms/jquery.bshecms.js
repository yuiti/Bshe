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

	        	var target = this;

	        	// textコントローラー作成
	            var menuDiv = $(document.createElement("div"));
	            menuDiv
	            	.attr({
	            	'class': 'Bshe BsheTextControlDiv bshe_cms_menu',
	            	'id': 'bshe_cms_text__' + $(target).attr('id')
            	});
	            menuDiv.replace = function() {
	            	this.css({
	            		'position': "absolute",
	            		'overflow': "hidden",
	            		'top': $(target).offset().top + 'px',
	            		'zIndex': 50,
	            		'left': $(target).offset().left + 'px',
	            		'height': '30px'
	            	});
	            	return this;
	            };
	            menuDiv.changeOpacity = function(opacity) {
	            	this.css({
	            		'opacity':  (opacity / 100),
	            		'MozOpacity': (opacity / 100),
	            		'KhtmlOpacity': (opacity / 100),
	            		'filter': "alpha(opacity=" + opacity + ")"
	            	});
	            	return this;
		    	};


	            menuDiv.mouseover(function() {
	            	$(this).css({ height: '30px'});
	            	$(this).fadeTo("slow", 1);
	            	return $(this);
	            });
		        menuDiv.mouseout(function() {
		        	$(this).css({ height: '30px'});
		        	$(this).fadeTo("slow", 0.30);
	            	return $(this);
	            });
	            menuDiv.replace()
			    	.fadeTo("slow", 0.30)
	            	.appendTo($(document.body));

			    $(window).bind("resize", function() {menuDiv.replace()});



	            // 直接編集エリア生成
	            $(this).attr("contentEditable","true");
	        	//if( $(this).html() ) {
	        	//	$(this).html($(this).html());
	        	//}
	        	//$(this).focus();

	            this.updateTextModuleHTML = function(html) {
	        		$(this).html(html);
	        		//if(saveOkay) $(this).saveTextModule($(this).text(), true, null, null);
	        		SexyLightbox.display(0);
	        		$(this).focus();
	        	};

	        	// メッセージ表示
	        	this.showTextMessage = function(message) {
	        		var srcLDiv = document.createElement("div");
	        		var msgWidth = $(this).width();
	        		var msgHeight = $(this).height();
	        		var msgTop = $(this).offset().top;
	        		var msgLeft = $(this).offset().left;

	        		srcLDiv.id = "bshe_window_srcLDiv" + $(this).attr('id');
	        		srcLDiv.style.opacity = 0.8;
	        		srcLDiv.style.filter = "alpha(opacity=80)";
	        		srcLDiv.style.width = msgWidth + 'px';
	        		srcLDiv.style.height = msgHeight + 'px';
	        		srcLDiv.style.position = "absolute";
	        		srcLDiv.style.top = msgTop + 'px';
	        		srcLDiv.style.left = msgLeft + 'px';
	        		srcLDiv.style.zIndex = 2000;
	        		srcLDiv.style.backgroundColor = "#000000";
	        		srcLDiv.innerHTML = "<table width='"+msgWidth+"' height='"+msgHeight+"' cellpadding='0' cellspacing='0' border='0' style='border-collapse:collapse;'><tr><td style='line-height:18px;font-size:20px;color:#ffffff;text-align:center;font-weight:bold;cursor:default;' id='bshe_window_srcLDiv_content_"+$(this).attr('id')+"'>" + message + "</td></tr></table>";

	        		document.body.appendChild(srcLDiv);
	        	}

	        	// メッセージ内容変更
	        	this.changeTextMessage = function(message) {
	        		if($("bshe_window_srcLDiv" + this.id)) $("bshe_window_srcLDiv" + this.id).html(message);
	        	}

	        	this.hideTextMessage = function() {
	        		//if($("bshe_window_srcLDiv" + this.id)) {
	        			document.body.removeChild(document.getElementById("bshe_window_srcLDiv" + this.id));
	        		//}
	        	}

	        	// 下書き保存
	        	this.saveTextModule = function() {
	        		this.showTextMessage('下書き保存中');
	        		xajax_saveText(bshe_templatename, this.id, this.innerHTML);
	        		this.hideTextMessage();
	        	}

	        	// 保存公開
	        	this.publishTextModule = function() {
	        		this.showTextMessage('保存公開中');
	        		xajax_publishText(bshe_templatename, this.id, this.innerHTML);
	        		this.hideTextMessage();
	        	}

	        	// 元に戻す
	        	this.undoTextModule = function() {
	        		this.showTextMessage('元に戻す');
	        		xajax_undoText(bshe_templatename, this.id);
	        		this.hideTextMessage();
	        	}
	        });
	        //method chain
	        return this;
	    };


})(jQuery);

