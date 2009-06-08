jQuery(document).ready( function() {

	jQuery("#browser").treeview();

	jQuery(".file").contextMenu({
			menu: 'sitemapmenu'
		},
		function(action, el, pos) {
        if (action == 'edit') {
            xajax_goEdit();
        } else if (action == 'copy') {
        	e = new SexyLightBox({find:'sexywhite',color:'white', OverlayStyles:{'background-color':'#000'}, imagesdir:bshe_basedir + '/sexy-lightbox-2/sexyimages'});
            e.show('ページをコピー',  bshe_basedir +'/cms/admin/sitemap/copy.html?height=160&width=350', 'sexylightbox');
        } else if (action == 'delete') {
        	e = new SexyLightBox({find:'sexywhite',color:'white', OverlayStyles:{'background-color':'#000'}, imagesdir:bshe_basedir + '/sexy-lightbox-2/sexyimages'});
            e.show('ページを削除',  bshe_basedir +'/cms/admin/sitemap/delete.html?height=160&width=350', 'sexylightbox');
        } else if (action == 'editproperty') {
        	e = new SexyLightBox({find:'sexywhite',color:'white', OverlayStyles:{'background-color':'#000'}, imagesdir:bshe_basedir + '/sexy-lightbox-2/sexyimages'});
            e.show('タイトルなどの編集',  bshe_basedir +'/cms/admin/sitemap/editproperty.html?height=160&width=350', 'sexylightbox');
        }}
	);

});

