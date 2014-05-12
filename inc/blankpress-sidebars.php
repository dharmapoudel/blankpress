<?php

// =============================================================================
// FUNCTIONS/GLOBAL/ADMIN/SIDEBARS.PHP
// -----------------------------------------------------------------------------
// Sets up functionality for unique page sidebars.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Register All Hooks
//   02. Display Sidebar
//   03. Add Options Page
//   04. Options Init
//   05. Options Admin Init
//   06. Callbacks
//   07. Validation
//   08. Add New Sidebar
// =============================================================================

// Register All Hooks
// =============================================================================

//add_filter( 'ups_sidebar', 'ups_display_sidebar' );
add_action( 'init', 'ups_options_init' );
add_action( 'admin_init', 'ups_options_admin_init' );
add_action( 'admin_menu', 'ups_options_add_page' );



// Display Sidebar
// =============================================================================

//
// Displays the sidebar, which is attached to the page being viewed.
//

/* function ups_display_sidebar( $default_sidebar ) {
  GLOBAL $post;

  $sidebars = get_option( 'ups_sidebars' );
  foreach ( $sidebars as $id => $sidebar ) {
    if ( array_key_exists( 'pages', $sidebar ) ) {
      if ( array_key_exists( 'children', $sidebar ) && $sidebar['children'] == 'on' ) {
        $child = array_key_exists( $post->post_parent, $sidebar['pages'] );
      } else {
        $child = false;
      }
      if ( array_key_exists( $post->ID, $sidebar['pages'] ) || $child ) {
        return $id;
      }
    }
  }

  return $default_sidebar;
} */



// Add Options Page
// =============================================================================

function ups_options_add_page() {
  add_theme_page( 'Sidebars', 'Sidebars', 'edit_theme_options', 'ups_sidebars', 'ups_sidebars_do_page' );
}



// Options Init
// =============================================================================

//
// Registers all sidebars for use on the front-end and Widgets page.
//

function ups_options_init() {
  $sidebars = get_option( 'ups_sidebars' );
  //print_r($sidebars);
  if ( is_array( $sidebars ) ) {
    foreach ( (array) $sidebars as $id => $sidebar ) {
      //unset( $sidebar['pages'] );
      $sidebar['id'] = $id;
      register_sidebar( $sidebar );
    }
  }
}



// Options Admin Init
// =============================================================================

//
// Adds the metaboxes to the main options page for the sidebars in the database.
//

function ups_options_admin_init() {
  //wp_enqueue_script( 'common' );
  //wp_enqueue_script( 'wp-lists' );
  //wp_enqueue_script( 'postbox' );

  // Register setting to store all the sidebar options in the *_options table
  register_setting( 'ups_sidebars_options', 'ups_sidebars', 'ups_sidebars_validate' );

  $sidebars = get_option( 'ups_sidebars' );
  if ( is_array( $sidebars ) && count ( $sidebars ) > 0 ) {
    foreach ( $sidebars as $id => $sidebar ) {
      add_meta_box(
        esc_attr( $id ),
        esc_html( $sidebar['name'] ),
        'ups_sidebar_do_meta_box',
        'ups_sidebars',
        'normal',
        'default',
        array(
          'id' => esc_attr( $id ),
          'sidebar' => $sidebar
        )
      );

      //unset( $sidebar['pages'] );
      $sidebar['id'] = esc_attr( $id );
      register_sidebar( $sidebar );
    }
  } else {
    add_meta_box( 'ups-sidebar-no-sidebars', 'No sidebars', 'ups_sidebar_no_sidebars', 'ups_sidebars', 'normal', 'default' );
  }


  //
  // Sidebar metaboxes.
  //

  add_meta_box( 'ups-sidebar-add-new-sidebar', 'Add New Sidebar', 'ups_sidebar_add_new_sidebar', 'ups_sidebars', 'side', 'default' );
}

function ups_sidebar_no_sidebars() {
  ?>
  <p>No sidebars added. Add one from right hand side.</p>
  <?php
}



// Callbacks
// =============================================================================

//
// Creates the theme page and adds a spot for the metaboxes.
//

function ups_sidebars_do_page() {
  if ( ! isset( $_REQUEST['settings-updated'] ) )
    $_REQUEST['settings-updated'] = false;
  ?>
  <div class="wrap">
    <h2>Manage Sidebars</h2>
    <?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
    <div class="updated fade"><p><strong>Sidebar settings saved.</strong> You can now go manage the <a href="<?php echo get_admin_url(); ?>widgets.php">widgets</a> for your sidebars.</p></div>
    <?php endif; ?>
    <div id="poststuff" class="metabox-holder has-right-sidebar">
      <div id="post-body" class="has-sidebar">
        <div id="post-body-content" class="has-sidebar-content">
          <form method="post" action="options.php">
            <?php settings_fields( 'ups_sidebars_options' ); ?>
            <?php do_meta_boxes( 'ups_sidebars', 'normal', null ); ?>
          </form>
        </div>
      </div>
      <div id="side-info-column" class="inner-sidebar">
        <?php do_meta_boxes( 'ups_sidebars', 'side', null ); ?>
      </div>
    </div>
  </div>
  <?php
}


//
// Adds the content of the metaboxes for each sidebar.
//

function ups_sidebar_do_meta_box( $post, $metabox ) {
  $sidebars = get_option( 'ups_sidebars' );
  $sidebar_id = esc_attr( $metabox['args']['id'] );
  $sidebar = $sidebars[$sidebar_id];

  $options_fields = array(
    'name'          => array( 'Name', '' ),
    'description'   => array( 'Description', '' ),
    'before_title'  => array( 'Before Title', '<h4 class="h-widget">' ),
    'after_title'   => array( 'Before Title', '</h4>' ),
    'before_widget' => array( 'Before Widget', '<div id="%1$s" class="widget %2$s">' ),
    'after_widget'  => array( 'After Widget', '</div>' )
  );
  ?>

  <div>
    <table class="form-table">
      <?php foreach ( $options_fields as $id => $label ) : ?>
      <tr valign="top">
          <th scope="row">
            <label for="ups_sidebars[<?php echo esc_attr( $sidebar_id ); ?>][<?php echo esc_attr( $id ); ?>]"><?php echo esc_html( $label[0] ); ?></label>
          </th>
          <td>
          <input id="ups_sidebars[<?php echo esc_attr( $sidebar_id ); ?>][<?php echo esc_attr( $id ); ?>]" class="regular-text" type="text" name="ups_sidebars[<?php echo esc_attr( $sidebar_id ); ?>][<?php echo esc_attr( $id ); ?>]" value="<?php echo esc_html( $sidebar[$id] ); ?>"  />
          </td>
      </tr>
      <?php endforeach; ?>
    </table>
  </div>
  <div class="clear submitbox">
    <input type="submit" class="button-primary" value="Update" />&nbsp;&nbsp;&nbsp;
    <label><input type="checkbox" name="ups_sidebars[delete]" value="<?php echo esc_attr( $sidebar_id ); ?>" /> Delete this sidebar</label>
  </div>
  <?php
}



// Validation
// =============================================================================

//
// Handles all the post data (adding, updating, deleting sidebars).
//

function ups_sidebars_validate( $input ) {
  if ( isset( $input['add_sidebar'] ) ) {
    $sidebars = get_option( 'ups_sidebars' );
    if ( ! empty( $input['add_sidebar'] ) ) {
      if ( is_array( $sidebars ) ) {
        $sidebar_num = count( $sidebars ) + 1;
      } else {
        $sidebar_num = 1;
      }

      $sidebars['ups-sidebar-' . $sidebar_num] = array(
        'name'          => esc_html( $input['add_sidebar'] ),
        'description'   => '',
        'before_title'  => '',
        'after_title'   => '',
        'before_widget' => '',
        'after_widget'  => ''
        //,
        //'pages'         => array(),
        //'children'      => 'off'
      );
    }
    return $sidebars;
  }

  if ( isset( $input['delete'] ) ) {
    foreach ( (array) $input['delete'] as $delete_id ) {
      unset( $input[$delete_id] );
    }
    unset( $input['delete'] );
    return $input;
  }

  return $input;
}



// Add New Sidebar
// =============================================================================

//
// Handles the content of the metabox, which allows adding new sidebars.
//

function ups_sidebar_add_new_sidebar() {
  ?>
  <form method="post" action="options.php" id="add-new-sidebar">
    <?php settings_fields( 'ups_sidebars_options' ); ?>
    <table class="form-table">
      <tr valign="top">
        <th scope="row">
          <input id="ups_sidebars[add_sidebar]" placeholder="Sidebar Name" class="text" type="text" name="ups_sidebars[add_sidebar]" value="" />
        </th>
      </tr>
    </table>
    <p class="submit" style="padding: 0;">
      <input type="submit" class="button-primary" value="Add Sidebar" />
    </p>
  </form>
  <?php
}