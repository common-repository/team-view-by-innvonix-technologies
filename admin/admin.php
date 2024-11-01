<?php
add_action('init', 'invx_team_view_register_post_types');
function invx_team_view_register_post_types() {
	$labels = array(
		'name' => __('Members', 'invx-team-member'),
		'singular_name' => __('Member', 'invx-team-member'),
		'menu_name' => __('Team', 'invx-team-member'),
		'name_admin_bar' => __('Team', 'invx-team-member'),
		'add_new' => __('Add New Member', 'invx-team-member'),
		'add_new_item' => __('Add New Member', 'invx-team-member'),
		'edit_item' => __('Edit Member', 'invx-team-member'),
		'new_item' => __('New Member', 'invx-team-member'),
		'view_item' => __('View Member', 'invx-team-member'),
		'search_items' => __('Search Member', 'invx-team-member'),
		'not_found' => __('No member found', 'invx-team-member'),
		'not_found_in_trash' => __('No members found in trash', 'invx-team-member'),
		'all_items' => __('All Members', 'invx-team-member'),
	);

	$args = array(
		"label" => __('Invx Teams', 'invx-team-member'),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => false,
		"rest_base" => "",
		"has_archive" => true,
		"show_in_menu" => true,
		'menu_icon' => 'dashicons-groups',
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => array("slug" => "team-member", "with_front" => true, 'pages' => true, 'feeds' => true),
		"query_var" => true,
		/* Only 3 caps are needed: 'manage_team', 'create_teams', and 'edit_teams'. */
		'capabilities' => array(

			// meta caps (don't assign these to roles)
			'edit_post' => 'edit_team_item',
			'read_post' => 'read_team_item',
			'delete_post' => 'delete_team_item',

			// primitive/meta caps
			'create_posts' => 'create_teams',

			// primitive caps used outside of map_meta_cap()
			'edit_posts' => 'edit_teams',
			'edit_others_posts' => 'manage_team',
			'publish_posts' => 'manage_team',
			'read_private_posts' => 'read',

			// primitive caps used inside of map_meta_cap()
			'read' => 'read',
			'delete_posts' => 'manage_team',
			'delete_private_posts' => 'manage_team',
			'delete_published_posts' => 'manage_team',
			'delete_others_posts' => 'manage_team',
			'edit_private_posts' => 'edit_teams',
			'edit_published_posts' => 'edit_teams',
		),

		"supports" => array("title", "editor", "thumbnail", "author"),
	);
	register_post_type("member_team", $args);

}

add_action('init', 'invx_team_view_register_post_types_cat');
function invx_team_view_register_post_types_cat() {
	$labels = array(
		"name" => __('Team Groups', 'invx-team-member'),
		"singular_name" => __('Team Groups', 'invx-team-member'),
		'search_items' => __('Search Groups', 'invx-team-member'),
		'all_items' => __('All Groups', 'invx-team-member'),
		'parent_item' => __('Parent Groups', 'invx-team-member'),
		'parent_item_colon' => __('Parent Groups:', 'invx-team-member'),
		'edit_item' => __('Edit Groups', 'invx-team-member'),
		'update_item' => __('Update Groups', 'invx-team-member'),
		'add_new_item' => __('Add New Groups', 'invx-team-member'),
		'new_item_name' => __('New Groups Name', 'invx-team-member'),
		'not_found' => __('No Groups found', 'invx-team-member'),
		'not_found_in_trash' => __('No Groups found in trash', 'invx-team-member'),
		'menu_name' => __('Groups', 'invx-team-member'),
	);

	$args = array(
		"label" => __('Team Groups', 'invx-team-member'),
		"labels" => $labels,
		"public" => true,
		"hierarchical" => true,
		"label" => "Team Groups",
		"show_ui" => true,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"query_var" => true,
		"rewrite" => array('slug' => 'invx_team_group', 'with_front' => false, 'pages' => true),
		"show_admin_column" => false,
		"show_in_rest" => false,
		"rest_base" => "",
		"show_in_quick_edit" => false,
	);
	register_taxonomy("invx_team_group", array("member_team"), $args);
}

add_action('admin_menu', 'invx_team_view_admin_setup');

function invx_team_view_admin_setup() {
	/* Add meta boxes an save metadata. */
	add_action('add_meta_boxes', 'invx_team_view_add_meta_boxes', 1);
	add_action('save_post', 'invx_save_team_post_options_box', 1, 2);

	//meta box action for "team_member"
	add_action('add_meta_boxes', 'invx_team_view_meta_boxes_social', 10);
	add_action('save_post', 'invx_team_view_save_social_meta_box', 2, 2);

	/* Add 32px screen icon. */
	add_action('admin_head', 'invx_view_admin_head_style');
}

//For Add meta boxes
function invx_team_view_add_meta_boxes() {

	add_meta_box('team-view-member-option', 'Member Options', 'invx_team_view_meta', 'member_team', 'normal', 'high');

}

function invx_team_view_meta($post) {

	global $post;

	// Noncename needed to verify where the data originated
	echo '<input type="hidden" name="team_view_meta_nonce" id="team_view_meta_nonce" value="' .

	wp_create_nonce(plugin_basename(__FILE__)) . '" />';

	$inxv_team_designation = get_post_meta($post->ID, 'invx_team_view_designation', true);
	$member_custom_link = get_post_meta($post->ID, 'invx_team_view_link', true);

	?>

		<div class="option-box">
			<p class="option-title"><?php _e('Member designation:', 'invx-team-member');?></p>
			<p class="option-info"></p>
			<input type="text" size="30" placeholder="Team leader"   name="invx_team_view_designation" value="<?=$inxv_team_designation?>" />
		</div>
			<div class="option-box">
			<p class="option-title"><?php _e('Custom link to this member:', 'invx-team-member');?></p>
			<p class="option-info"></p>
			<input type="text" size="30" placeholder="http://extample.com/prject-sample"   name="invx_team_view_link" value="<?=$member_custom_link?>" />
		</div>
	<?php
}

//For Save meta boxes
function invx_save_team_post_options_box($post_id, $post) {

	if (!isset($_POST['team_view_meta_nonce']) || (!wp_verify_nonce(sanitize_text_field($_POST['team_view_meta_nonce']), plugin_basename(__FILE__)))) {

		return $post->ID;
	}
	if (!current_user_can('edit_post', $post->ID)) {
		return $post->ID;
	}

	$invx_team_view_meta['invx_team_view_designation'] = sanitize_text_field($_POST['invx_team_view_designation']);
	$invx_team_view_meta['invx_team_view_link'] = sanitize_text_field($_POST['invx_team_view_link']);
	
	foreach ($invx_team_view_meta as $key => $value) {
		if ($post->post_type == 'revision') {
			return;
		}
		$value = implode(',', (array) $value);

		if (get_post_meta($post->ID, $key, FALSE)) {

			update_post_meta($post->ID, $key, $value);

		} else {

			add_post_meta($post->ID, $key, $value);
		}
		if (!$value) {
			delete_post_meta($post->ID, $key);
		}

	}

}

//For Add meta boxes social
function invx_team_view_meta_boxes_social() {

	add_meta_box('team-member-social', 'Member Social Info', 'my_team_social_meta', 'member_team', 'normal', 'high');

}

function my_team_social_meta($post) {
	global $post;
	echo '<input type="hidden" name="team_social_noncename" id="teammeta_social_noncename" value="' .
	wp_create_nonce(plugin_basename(__FILE__)) . '" />';

	echo '<div class="para-settings">';

	$invx_mobile_view = get_post_meta($post->ID, 'invx_team_view_mobile', true);
	$invx_email_view = get_post_meta($post->ID, 'invx_team_view_email', true);
	$invx_skype_view = get_post_meta($post->ID, 'invx_team_view_skype', true);
	$invx_fb_view = get_post_meta($post->ID, 'invx_team_view_fb', true);
	$invx_twitter_view = get_post_meta($post->ID, 'invx_team_view_twitter', true);
	$invx_linkedin_view = get_post_meta($post->ID, 'invx_team_view_linkedin', true);
	$invx_googelplus_view = get_post_meta($post->ID, 'invx_team_view_googelplus', true);

	?>
		<div class="option-box">
			<p class="option-title"><?php _e(' Member Mobile:', 'invx-team-member');?></p>
			<p class="option-info"></p>
			<input type="text" size="30" placeholder="+01895632456"   name="invx_team_view_mobile" value="<?=$invx_mobile_view?>" />
		</div>

		<div class="option-box">
			<p class="option-title"><?php _e(' Member Email:', 'invx-team-member');?></p>
			<p class="option-info"></p>
			<input type="text" size="30" placeholder="hello@exapmle.com"   name="invx_team_view_email" value="<?=$invx_email_view?>" />
		</div>

		<div class="option-box">
			<p class="option-title"><?php _e('Skype:', 'invx-team-member');?></p>
			<p class="option-info"></p>
			<input type="text" size="30" placeholder="skypeusername"   name="invx_team_view_skype" value="<?=$invx_skype_view?>" />
		</div>

		<div class="option-box">
			<p class="option-title"><?php _e(' Facebook	:', 'invx-team-member');?></p>
			<p class="option-info"></p>
			<input type="text" size="30" placeholder="https://www.facebook.com/username"   name="invx_team_view_fb" value="<?=$invx_fb_view?>" />
		</div>
		<div class="option-box">
			<p class="option-title"><?php _e(' Twitter:', 'invx-team-member');?></p>
			<p class="option-info"></p>
			<input type="text" size="30" placeholder="https://www.twitter.com/username"   name="invx_team_view_twitter" value="<?=$invx_twitter_view?>" />
		</div>
		<div class="option-box">
			<p class="option-title"><?php _e(' Linkedin:', 'invx-team-member');?></p>
			<p class="option-info"></p>
			<input type="text" size="30" placeholder="https://www.linkedin.com/username"   name="invx_team_view_linkedin" value="<?=$invx_linkedin_view?>" />
		</div>
		<div class="option-box">
			<p class="option-title"><?php _e(' Google plus:', 'invx-team-member');?></p>
			<p class="option-info"></p>
			<input type="text" size="30" placeholder="https://plus.google.com/username"   name="invx_team_view_googelplus" value="<?=$invx_googelplus_view?>" />
		</div>

	<?php
echo '</div>'; // end of para-settings

}

function invx_team_view_save_social_meta_box($post_id, $post) {

	if (!isset($_POST['team_social_noncename']) || (!wp_verify_nonce(sanitize_text_field($_POST['team_social_noncename']), plugin_basename(__FILE__)))) {

		return $post->ID;

	}
	if (!current_user_can('edit_post', $post->ID)) {
		return $post->ID;
	}

	$invx_team_view_meta['invx_team_view_mobile'] = sanitize_text_field($_POST['invx_team_view_mobile']);
	$invx_team_view_meta['invx_team_view_email'] = sanitize_text_field($_POST['invx_team_view_email']);
	$invx_team_view_meta['invx_team_view_skype'] = sanitize_text_field($_POST['invx_team_view_skype']);
	$invx_team_view_meta['invx_team_view_fb'] = sanitize_text_field($_POST['invx_team_view_fb']);
	$invx_team_view_meta['invx_team_view_twitter'] = sanitize_text_field($_POST['invx_team_view_twitter']);
	$invx_team_view_meta['invx_team_view_linkedin'] = sanitize_text_field($_POST['invx_team_view_linkedin']);
	$invx_team_view_meta['invx_team_view_googelplus'] =sanitize_text_field($_POST['invx_team_view_googelplus']);


	foreach ($invx_team_view_meta as $key => $value) {

		if ($post->post_type == 'revision') {
			return;	
		}

		$value = implode(',', (array) $value);

		if (get_post_meta($post->ID, $key, FALSE)) {

			update_post_meta($post->ID, $key, $value);

		} else {

			add_post_meta($post->ID, $key, $value);

		}

		if (!$value) {
			delete_post_meta($post->ID, $key);
		}
	}
}
// view designation admin screen
function team_view_add_designation_column($columns) {
    $new = array();	
    $designation = 'Designation';
    $author = 'author';
    
	foreach($columns as $key=>$title) {
	    if($key==$author) {  // when we find the date column
	       $new[$designation] = $designation;  // put the tags column before it
	    }    
	    $new[$key]=$title;
	}  
	return $new;  

    $new_columns = array(
	'designation' => __( 'Designation', 'Designation' )
    );	  

    // Combine existing columns with new columns
    $filtered_columns = array_merge( $columns, $new_columns );

   // Return our filtered array of columns
   return $filtered_columns;
}


add_filter('manage_posts_columns' , 'team_view_add_designation_column');


function team_view_add_designation_column_data($columns){
	global $post;
    switch($columns){
    	case 'Designation' :
    	$inxv_team_designation = get_post_meta($post->ID,'invx_team_view_designation',true);	 
        echo $inxv_team_designation;
        break;
    }
}
add_action('manage_posts_custom_column','team_view_add_designation_column_data',10,2);


// Add style
function invx_view_admin_head_style() {
	global $post_type;

	if ('member_team' === $post_type) { ?>
		<style type="text/css">

			#team-view-member-option, #team-member-social {
				background: rgba(90, 220, 254,0.3);
			}
			#team-view-member-option h2, #team-member-social h2{
				background: rgba(39, 39, 39,0.8);
				color:#fff;
			}
			#team-view-member-option .toggle-indicator, #team-member-social .toggle-indicator{
				color:#fff;
			}
			#designation{
				width:108px;
				height:38px;
			}
		</style>
	<?php }
}

?>