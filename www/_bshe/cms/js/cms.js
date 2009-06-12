jQuery(document).ready( function() {

	jQuery(".bshe_cms_text").bsheCms({
			test: 'aaa'
	});

	jQuery(".bshe_cms_menu").contextMenu({
			menu: 'bshe_cms_menu'
		},
		function(action, el, pos) {
			// elから対象idを抽出
			var elementId = jQuery(el).attr('id').substr(15);


			if (action == 'editer') {
				// エディタ起動
				e = new SexyLightBox({find:'sexywhite',color:'white', OverlayStyles:{'background-color':'#000'}, imagesdir:bshe_basedir + '/sexy-lightbox-2/sexyimages'});
	            e.show('編集',  bshe_basedir +'/cms/text/noinc-advEditor.html?height=502&width=1002&elementClass=' + jQuery('#' + elementId).attr('class') + 'elementId=' + elementId, 'sexylightbox');
			}
		}
	);

	jQuery(".bshe_cms_pagemenu").contextMenu({
			menu: 'bshe_cms_pagemenu'
		},
		function(action, el, pos) {
		alert(action);
		}
	);

});