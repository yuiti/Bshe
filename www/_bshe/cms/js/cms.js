jQuery(document).ready( function() {

	jQuery(".bshe_cms_text").bsheCms({
		test: 'aaa'
		}
	);

//	jQuery(".bshe_cms_img").bsheCms({
//		test: 'aaa'
//		}
//	);

	jQuery(".bshe_cms_menu").contextMenu({
			menu: 'bshe_cms_menu'
		},
		function(action, el, pos) {
			// elから対象idを抽出
			var elementId = jQuery(el).attr('id').substr(15);

			if (action == 'editer') {
				// エディタ起動
				//var e = new SexyLightBox({find:'sexywhite',color:'white', OverlayStyles:{'background-color':'#000'}, imagesdir:bshe_basedir + '/sexy-lightbox-2/sexyimages'});
				SexyLightbox.show('編集',  bshe_basedir +'/cms/text/noinc-advEditor.html?elementClass=' +
					jQuery('#' + elementId).attr('class') +
					'&elementId=' + elementId +
					'TB_iframe=true&height=' + (jQuery(window).height() -50) +
					'&width=' + (jQuery(window).width() -50), 'sexylightbox');
            } else if (action == 'save') {
            	// 下書き保存
            	document.getElementById(elementId).saveTextModule();
            } else if (action == 'publish') {
            	// 保存公開
            	document.getElementById(elementId).publishTextModule();
            } else if (action == 'undo') {
            	// 元に戻す
            	document.getElementById(elementId).undoTextModule();
            } else if (action == 'history') {
            	// 履歴を表示
            	SexyLightbox.show('編集',  bshe_basedir +'/cms/text/noinc-revisions.html?elementClass=' +
    					jQuery('#' + elementId).attr('class') +
    					'&elementId=' + elementId +
    					'&pageId=' + bshe_templatename +
    					'TB_iframe=true&height=' + (jQuery(window).height() -50) +
    					'&width=' + (jQuery(window).width() -50), 'sexylightbox');
            } else if (action == 'menu') {
            	// メニュー
            	location.href = bshe_basedir + '/cms/admin/index.html';
            } else if (action == 'logout') {
            	// ログアウト
            	location.href = location.href + '?bshe_specializer_auth=logout';
            }
		}
    );

//	jQuery(".bshe_cms_pagemenu").contextMenu({
//			menu: 'bshe_cms_pagemenu'
//		},
//		function(action, el, pos) {
//		alert(action);
//		}
//	);

	SexyLightbox = new SexyLightBox({find:'sexywhite',color:'white', OverlayStyles:{'background-color':'#000'}, imagesdir:bshe_basedir + '/sexy-lightbox-2/sexyimages'});
});

