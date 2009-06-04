$(document).ready( function() {
	$("#browser").treeview();

	$(".file").contextMenu({
			menu: 'sitemapmenu'
		},
		function(action, el, pos) {
			alert('aaa');
		}
	);
});