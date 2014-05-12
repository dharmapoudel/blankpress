<?php 
/*-------------------------------------------------
	BlankPress - metaboxes function page
 --------------------------------------------------*/

//This page is not yet complete. And is intended to add ability to add metaboxes on settings pages.

// This creates the metabox. Do not override this method.
add_action( 'admin_init', 'metaboxes' );

function metaboxes() {
	add_meta_box( 'test-meta', 'BP Meta', 'display_admin_metabox', 'BP-options', 'secondary', 'high');
}

function display_admin_metabox(){
	echo "BlankPress!";
}