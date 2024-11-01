<?php

class Invx_Widget_Teams_View extends WP_Widget {
	function __construct() {

		parent::__construct(
			'invx-recent-team',
			__('Team Member Widget', 'invx-team-member'),
			array('description' => __('Display a list of recently added team Member. ', 'invx-team-member'))
		);

	}

	// Show the content on the widget
	public function widget($args, $instance) {
		echo $args['before_widget'];
		if (!empty($instance['title'])) {
			echo $args['before_title'];
			echo apply_filters('widget_title', $instance['title']);
			echo $args['after_title'];
		}

		// Get the posts
		$invx_post = new WP_Query(array(
			'post_type' => 'member_team',
			'posts_per_page' => $instance['invx_member_per'],
			'orderby' => $instance['invx_order_by'],
		));

		// Post thumbnail Size
		add_image_size('invx-recent-team-thumb', 120, 120, array('center', 'center'));

		echo '<div class="invx_recent_members"><ul>';
		while ($invx_post->have_posts()): $invx_post->the_post();
			$date_new = get_the_time("l, d F");
			global $post;
			?>
				<li>
					<div class="thumbnail_left">
						<?php if (has_post_thumbnail()): ?>
						<a href="<?php the_permalink($post->ID);?>"><?php the_post_thumbnail('invx-recent-team-thumb');?></a>
						<?php else:echo '<a href="' . get_the_permalink($post->ID) . '"><img src="http://placehold.it/120x72" alt="" /></a>';endif;?>
				</div>
				<div class="invx_content">
					<div class="invx_member_title">
						<a href="<?php the_permalink($post->ID);?>"><h4><?php the_title();?></h4></a>
					</div>
					<div class="invx_member_pos">
						<p><?php echo get_post_meta($post->ID, 'invx_team_view_designation', true); ?></p>
					</div>
				</div>
			</li>

		<?php endwhile;
		wp_reset_query();
		echo '</ul></div>';
		echo $args['after_widget'];
	}

	// Add the form in sidebar
	public function form($instance) {
		$title = !empty($instance['title']) ? $instance['title'] : esc_html__('Recent Team Members', 'invx-team-member');
		$invx_member_per = !empty($instance['invx_member_per']) ? $instance['invx_member_per'] : esc_html__('5', 'invx-team-member');
		$invx_order_by = !empty($instance['invx_order_by']) ? $instance['invx_order_by'] : esc_html__('date (post_date)', 'invx-team-member');
		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'invx-team-member');?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('invx_member_per
invx-team-member')); ?>"><?php esc_attr_e('Member Per page:', 'invx-team-member');?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('invx_member_per')); ?>" name="<?php echo esc_attr($this->get_field_name('invx_member_per')); ?>" type="text" value="<?php echo esc_attr($invx_member_per); ?>">
		</p>
		<table>
		<tr>
		<td>
		<label><?php esc_attr_e('Order By:', 'invx-team-member');?>  </label></td>
		<td><select name="<?php echo esc_attr($this->get_field_name('invx_order_by')); ?>">
			<option value="date (post_date)" <?php echo ('date (post_date)' == $invx_order_by) ? 'selected' : ''; ?>><?php esc_attr_e('Latest Member:', 'invx-team-member');?></option>
			<option value="ID" <?php echo ('ID' == $invx_order_by) ? 'selected' : ''; ?>><?php esc_attr_e('ID', 'invx-team-member');?></option>
			<option value="author" <?php echo ('author' == $invx_order_by) ? 'selected' : ''; ?>><?php esc_attr_e('Author', 'invx-team-member');?></option>
			<option value="title" <?php echo ('title' == $invx_order_by) ? 'selected' : ''; ?>><?php esc_attr_e('Title', 'invx-team-member');?></option>
			<option value="name" <?php echo ('name' == $invx_order_by) ? 'selected' : ''; ?>><?php esc_attr_e('Post Slug', 'invx-team-member');?></option>
			<option value="date" <?php echo ('date' == $invx_order_by) ? 'selected' : ''; ?>><?php esc_attr_e('Date', 'invx-team-member');?></option>
			<option value="modified"<?php echo ('modified' == $invx_order_by) ? 'selected' : ''; ?>><?php esc_attr_e('Modified', 'invx-team-member');?></option>
			<option value="rand" <?php echo ('rand' == $invx_order_by) ? 'selected' : ''; ?>><?php esc_attr_e('Randomly', 'invx-team-member');?></option>
			<option value="comment_count" <?php echo ('comment_count' == $invx_order_by) ? 'selected' : ''; ?>><?php esc_attr_e('Popular Team', 'invx-team-member');?></option>
		</select>
		</td>
		</tr>
		</table>
		<?php
	}
}
add_action('widgets_init', function () {
	register_widget('Invx_Widget_Teams_View');
});

// set grid view 
function invx_team_view_front($atts) {
	ob_start();
	$args['post_type'] = 'member_team';

	if (isset($atts['limit'])):
		$args['posts_per_page'] = $atts['limit'];
	else:
		$args['posts_per_page'] = '6';
	endif;

	if (isset($atts['order'])):
		$args['order'] = $atts['order'];
	else:
		$args['order'] = 'DESC';
	endif;

	$args['post_status'] = 'publish';

    if (isset($atts['content_limit'])):
		$limit = $atts['content_limit'];
	else:
		$limit = '20';
	endif;
	
	if(isset($atts['view'])):
		switch ($atts['view']) {
			case 'square':
				$class = 'square';		
				break;
			case 'round':
				$class = 'round';		
				break;
			default:
				$class = 'rounded';
				break;
		}else:
		$class = 'rounded';
	endif;

	if (isset($atts['column'])):
		switch ($atts['column']) {
		case '1':
			$column = '12';
			$col_5 = 'col-md-5 col-sm-5 col-xs-12 member-col-one';
			$col_7 = 'col-md-7 col-sm-7 col-xs-12 member-col-seven';
			$limit = '15';
			break;
		case '2':
			$column = '6';
			$col_5 = '';
			$col_7 = '';
			break;
		case '3':
			$column = '4';
			$col_5 = '';
			$col_7 = '';
			break;
		case '4':
			$column = '3';
			$col_5 = '';
			$col_7 = '';
			break;
		case '5':
			$column = '2';
			$col_5 = '';
			$col_7 = '';
			break;
		case '6':
			$column = '2';
			$col_5 = '';
			$col_7 = '';
			break;
		default:
			$column = '4';
			break;
		} else :
		$column = '4';
	endif;

    
    if(isset($atts['group'])):
		if ($atts['group'] != ''):
    	    $args['tax_query'] = array(
    	    		array(
    	   				'taxonomy' => 'invx_team_group',
    	   				'terms' => $atts['group'],
    	   				'field' => 'slug',
    	   				'include_children' => true,
    	   			)
	   			);
    	endif;
    endif;
	
	$team = new WP_Query($args);

	while ($team->have_posts()): $team->the_post();
		$post_id = get_the_ID();
		$invx_mobile_view = get_post_meta($post_id, 'invx_team_view_mobile', true);
		$invx_email_view = get_post_meta($post_id, 'invx_team_view_email', true);
		$invx_skype_view = get_post_meta($post_id, 'invx_team_view_skype', true);
		$invx_facebook_view = get_post_meta($post_id, 'invx_team_view_fb', true);
		$invx_twitter_view = get_post_meta($post_id, 'invx_team_view_twitter', true);
		$invx_linkedin_view = get_post_meta($post_id, 'invx_team_view_linkedin', true);
		$googelplus = get_post_meta($post_id, 'invx_team_view_googelplus', true);
		$invx_member_link = get_post_meta($post_id, 'invx_team_view_link', true);

		$img = get_the_post_thumbnail('', 'medium', '');

		if ( $img != '' ):
			$img = get_the_post_thumbnail('', 'medium', '');
		else:
			$img = '<img src="'.TEAM_VIEW_INVX_URI.'/images/user-icon.jpg" class="demo-image"/>';
		endif;

		echo '<div class="team-grid text-center col-md-' . $column . ' col-sm-' . $column . ' col-xs-12">
				  <div class="team-content '.$class.'">
				  <div class="member-image '.$col_5.'">
				  		<a href="' . get_permalink() . '" class="image-link" title="' . get_the_title() . '">' . $img . '</a>
				  </div>
				  <div class="team-section '.$col_7.'">
				  <div class="member-title">
				  	<a href="' . get_permalink() . '" title="' . get_the_title() . '"><h4>' . get_the_title() . '</h4>
				  	<div class="title-border"></div>
				  	</a>
				  </div>
				  <div class="member-designation">' . get_post_meta($post_id, 'invx_team_view_designation', true) . '</div>
				  <div class="member-description"><p>"'.wp_trim_words( get_the_content(), $limit, '...' ).'"</p></div>
					<div class="social-media-links">
				<ul>';
		if ($invx_mobile_view != ''):
			echo '<li><a href="tel:' . $invx_mobile_view . '"><i class="fa fa-mobile"></i></a></li>';
		endif;
		if ($invx_email_view != ''):
			echo '<li><a href="mailto:' . $invx_email_view . '"><i class="fa fa-envelope"></i></a></li>';
		endif;
		if ($invx_skype_view != ''):
			echo '<li><a href="skype:' . $invx_skype_view . '?chat" target="_blank"><i class="fa fa-skype"></i></a></li>';
		endif;
		if ($invx_facebook_view != ''):
			echo '<li><a href="' . $invx_facebook_view . '" target="_blank"><i class="fa fa-facebook"></i></a></li>';
		endif;
		if ($invx_twitter_view != ''):
			echo '<li><a href="' . $invx_twitter_view . '" target="_blank"><i class="fa fa-twitter"></i></a></li>';
		endif;
		if ($invx_linkedin_view != ''):
			echo '<li><a href="' . $invx_linkedin_view . '" target="_blank"><i class="fa fa-linkedin"></i></a></li>';
		endif;
		if ($googelplus != ''):
			echo '<li><a href="' . $googelplus . '" target="_blank"><i class="fa fa-google-plus"></i></a></li>';
		endif;
		if ($invx_member_link != ''):
				echo '<li><a href="' . $invx_member_link . '" target="_blank"><i class="fa fa-chain"></i></a></li>';
		endif;
		echo '</ul>
		      </div>
			</div>
			</div>
		 </div>';
	endwhile;
	return ob_get_clean();
}

add_shortcode('team-grid', 'invx_team_view_front');

// set slider view 
function invx_team_view_front_slider($atts) {
	ob_start();
	$args['post_type'] = 'member_team';

	if (isset($atts['limit'])):
		$args['posts_per_page'] = $atts['limit'];
	else:
		$args['posts_per_page'] = '6';
	endif;

	$args['order'] = 'DESC';
	$args['post_status'] = 'publish';


	if (isset($atts['content_limit'])):
		$limit = $atts['content_limit'];
	else:
		$limit = '20';
	endif;

	if(isset($atts['view'])):
		switch ($atts['view']) {
			case 'square':
				$class = 'square';		
				break;
			case 'round':
				$class = 'round';		
				break;
			default:
				$class = 'rounded';
				break;
		}else:
		$class = 'rounded';
	endif;

	if (isset($atts['column'])):
		switch ($atts['column']) {
		case '1':
			$column = '1';
			break;
		case '2':
			$column = '2';
			break;
		case '3':
			$column = '3';
			break;
		case '4':
			$column = '4';
			break;
		default:
			$column = '3';
			break;
		} else :
		$column = '3';
	endif;


    if(isset($atts['group'])):
		if ($atts['group'] != ''):
    	    $args['tax_query'] = array(
    	    		array(
    	   				'taxonomy' => 'invx_team_group',
    	   				'terms' => $atts['group'],
    	   				'field' => 'slug',
    	   				'include_children' => true,
    	   			)
	   			);
    	endif;
    endif;

	$team = new WP_Query($args);

	if ($team->have_posts()):
		echo '<div class="team-slider">';
		while ($team->have_posts()): $team->the_post();
			$post_id = get_the_ID();

			$invx_mobile_view = get_post_meta($post_id, 'invx_team_view_mobile', true);
			$invx_email_view = get_post_meta($post_id, 'invx_team_view_email', true);
			$invx_skype_view = get_post_meta($post_id, 'invx_team_view_skype', true);
			$invx_facebook_view = get_post_meta($post_id, 'invx_team_view_fb', true);
			$invx_twitter_view = get_post_meta($post_id, 'invx_team_view_twitter', true);
			$invx_linkedin_view = get_post_meta($post_id, 'invx_team_view_linkedin', true);
			$invx_googelplus_view = get_post_meta($post_id, 'invx_team_view_googelplus', true);
			$invx_member_link = get_post_meta($post_id, 'invx_team_view_link', true);

			$img = get_the_post_thumbnail('', 'medium', '');
			if ( $img != '' ):
			$img = get_the_post_thumbnail('', 'medium', '');
			else:
				$img = '<img src="'.TEAM_VIEW_INVX_URI.'/images/user-icon.jpg" class="demo-image"/>';
			endif;


			echo '<div class="text-center col-xs-12">
				 <div class="team-content '.$class.'">
				  <div class="member-image text-center">
				  		<a href="' . get_permalink() . '" class="image-link"  title="' . get_the_title() . '">' .$img. '</a>
				  </div>
				  <div class="member-title">
				  	<a href="' . get_permalink() . '" title="' . get_the_title() . '"><h4>' . get_the_title() . '</h4>
  					<div class="title-border"></div>
				  	</a>
				  </div>
				  <div class="member-designation">' . get_post_meta($post_id, 'invx_team_view_designation', true) . '</div>

				  <div class="member-description"><p>"'.wp_trim_words( get_the_content(), $limit, '...' ).'"</p></div>

					<div class="social-media-links">
				<ul>';

			if ($invx_mobile_view != ''):
				echo '<li><a href="tel:' . $invx_mobile_view . '"><i class="fa fa-mobile"></i></a></li>';
			endif;
			if ($invx_email_view != ''):
				echo '<li><a href="mailto:' . $invx_email_view . '"><i class="fa fa-envelope"></i></a></li>';
			endif;
			if ($invx_skype_view != ''):
				echo '<li><a href="skype:' . $invx_skype_view . '?chat" target="_blank"><i class="fa fa-skype"></i></a></li>';
			endif;
			if ($invx_facebook_view != ''):
				echo '<li><a href="' . $invx_facebook_view . '" target="_blank"><i class="fa fa-facebook"></i></a></li>';
			endif;
			if ($invx_twitter_view != ''):
				echo '<li><a href="' . $invx_twitter_view . '" target="_blank"><i class="fa fa-twitter"></i></a></li>';
			endif;
			if ($invx_linkedin_view != ''):
				echo '<li><a href="' . $invx_linkedin_view . '" target="_blank"><i class="fa fa-linkedin"></i></a></li>';
			endif;
			if ($invx_googelplus_view != ''):
				echo '<li><a href="' . $invx_googelplus_view . '" target="_blank"><i class="fa fa-google-plus"></i></a></li>';
			endif;
			if ($invx_member_link != ''):
				echo '<li><a href="' . $invx_member_link . '" target="_blank"><i class="fa fa-chain"></i></a></li>';
			endif;
			echo '</ul>
				</div>
				</div>
			 </div>';
		endwhile;
		echo '</div>';
	endif;
	echo '<script>
	jQuery(document).ready(function($) {
	"use strict";
	// Team Carousel
		jQuery("div.team-slider").owlCarousel({
		 autoPlay:false,
		 responsive:true,
		 navigation:true,
		 navigationText:["<i class=\'fa fa-angle-left\' ></i>","<i class=\'fa fa-angle-right\' ></i>"],
		 pagination:false,
		 rewindSpeed:1000,
		 items:' . $column . ',
		 itemsDesktop:[1200,3],
		 itemsTablet:[991,2],
		 itemsMobile:[767,1]
		});
	});
	</script>';
	return ob_get_clean();
}

add_shortcode('team-slider', 'invx_team_view_front_slider');

// set overlay effects 
function invx_team_view_overlay_effect($atts){
	ob_start();

	$args['post_type'] = 'member_team';

	if (isset($atts['limit'])):
		$args['posts_per_page'] = $atts['limit'];
	else:
		$args['posts_per_page'] = '6';
	endif;

	if (isset($atts['order'])):
		$args['order'] = $atts['order'];
	else:
		$args['order'] = 'DESC';
	endif;

	$args['post_status'] = 'publish';

    if (isset($atts['content_limit'])):
		$limit = $atts['content_limit'];
	else:
		$limit = '20';
	endif;
	
	if(isset($atts['view'])):
		switch ($atts['view']) {
			case 'square':
				$class = 'square';		
				break;
			case 'round':
				$class = 'round';		
				break;
			default:
				$class = 'rounded';
				break;
		}else:
		$class = 'rounded';
	endif;


	if (isset($atts['column'])):
		switch ($atts['column']) {
		case '1':
			$column = '12';
			$col_5 = 'col-md-5 col-sm-5 col-xs-12 member-col-one';
			$col_7 = 'col-md-7 col-sm-7 col-xs-12 member-col-seven';
			$limit = '15';
			break;
		case '2':
			$column = '6';
			$col_5 = '';
			$col_7 = '';
			break;
		case '3':
			$column = '4';
			$col_5 = '';
			$col_7 = '';
			break;
		case '4':
			$column = '3';
			$col_5 = '';
			$col_7 = '';
			break;
		case '5':
			$column = '2';
			$col_5 = '';
			$col_7 = '';
			break;
		case '6':
			$column = '2';
			$col_5 = '';
			$col_7 = '';
			break;
		default:
			$column = '4';
			$col_5 = '';
			$col_7 = '';
			break;
		} else :
		$column = '4';
	endif;

	if(isset($col_5)){
		$col_5 = $col_5;
	}
	else{
		$col_5 = '';
	}
	if(isset($col_7)){
		$col_7 = $col_7;
	}
	else{
		$col_7 = '';
	}

	if(isset($atts['group'])):
		if ($atts['group'] != ''):
    	    $args['tax_query'] = array(
    	    		array(
    	   				'taxonomy' => 'invx_team_group',
    	   				'terms' => $atts['group'],
    	   				'field' => 'slug',
    	   				'include_children' => true,
    	   			)
	   			);
    	endif;
    endif;

	$team = new WP_Query($args);
	
	while ($team->have_posts()): $team->the_post();
		$post_id = get_the_ID();
		$invx_mobile_view = get_post_meta($post_id, 'invx_team_view_mobile', true);
		$invx_email_view = get_post_meta($post_id, 'invx_team_view_email', true);
		$invx_skype_view = get_post_meta($post_id, 'invx_team_view_skype', true);
		$invx_facebook_view = get_post_meta($post_id, 'invx_team_view_fb', true);
		$invx_twitter_view = get_post_meta($post_id, 'invx_team_view_twitter', true);
		$invx_linkedin_view = get_post_meta($post_id, 'invx_team_view_linkedin', true);
		$invx_googelplus_view = get_post_meta($post_id, 'invx_team_view_googelplus', true);
		$invx_member_link = get_post_meta($post_id, 'invx_team_view_link', true);

		$img = get_the_post_thumbnail('', 'medium', '');
		if ( $img != '' ):
			$img = get_the_post_thumbnail('', 'medium', '');
		else:
			$img = '<img src="'.TEAM_VIEW_INVX_URI.'/images/user-icon.jpg" class="demo-image"/>';
		endif;


		echo '<div class="team-grid overlay-grid text-center col-md-' . $column . ' col-sm-' . $column . ' col-xs-12">
				  <div class="team-content '.$class.'">
				  <div class="member-image '.$col_5.'">
				  		<a href="' . get_permalink() . '" class="image-link" title="' . get_the_title() . '">' .$img. '</a>
				  </div>
				  <div class="content-overlay '.$class.'">
				  <div class="team-section '.$col_7.'">
				  <div class="member-description"><p>"'.wp_trim_words( get_the_content(), $limit, '...' ).'"</p></div>
			</div>
			</div>
			</div>
				  <div class="member-title">
				  	<a href="' . get_permalink() . '" title="' . get_the_title() . '"><h4>' . get_the_title() . '</h4>
				  	<div class="title-border"></div>
				  	</a>
				  </div>
				  <div class="member-designation">' . get_post_meta($post_id, 'invx_team_view_designation', true) . '</div>
				  
					<div class="social-media-links '.$class.'">
				<ul>';
		if ($invx_mobile_view != ''):
			echo '<li><a href="tel:' . $invx_mobile_view . '"><i class="fa fa-mobile"></i></a></li>';
		endif;
		if ($invx_email_view != ''):
			echo '<li><a href="mailto:' . $invx_email_view . '"><i class="fa fa-envelope"></i></a></li>';
		endif;
		if ($invx_skype_view != ''):
			echo '<li><a href="skype:' . $invx_skype_view . '?chat" target="_blank"><i class="fa fa-skype"></i></a></li>';
		endif;
		if ($invx_facebook_view != ''):
			echo '<li><a href="' . $invx_facebook_view . '" target="_blank"><i class="fa fa-facebook"></i></a></li>';
		endif;
		if ($invx_twitter_view != ''):
			echo '<li><a href="' . $invx_twitter_view . '" target="_blank"><i class="fa fa-twitter"></i></a></li>';
		endif;
		if ($invx_linkedin_view != ''):
			echo '<li><a href="' . $invx_linkedin_view . '" target="_blank"><i class="fa fa-linkedin"></i></a></li>';
		endif;
		if ($invx_googelplus_view != ''):
			echo '<li><a href="' . $invx_googelplus_view . '" target="_blank"><i class="fa fa-google-plus"></i></a></li>';
		endif;
		if ($invx_member_link != ''):
				echo '<li><a href="' . $invx_member_link . '" target="_blank"><i class="fa fa-chain"></i></a></li>';
		endif;
		echo '</ul>
		      </div>
		 </div>';
	endwhile; ?>
	<script>
		jQuery(document).ready(function(){
			var img_width = jQuery('.team-content .member-image > a').width();
			var img_height = jQuery('.team-content .member-image > a').height();
			jQuery('.content-overlay').css('width',(img_width+16)+'px');
			jQuery('.content-overlay').css('height',(img_height+16)+'px');
			var overlay_width = jQuery('.overlay-grid .team-content').width();
			var overlay_height = jQuery('.overlay-grid .team-content').height();
			jQuery('.overlay-grid .team-content .content-overlay').css('width',(img_width+16)+'px');
			jQuery('.overlay-grid .team-content .content-overlay').css('height',(img_height+16)+'px');
		});
	</script>
	<?php
	return ob_get_clean();
}
add_shortcode('team-grid-overlay', 'invx_team_view_overlay_effect');

//sset slider overlay effects 
function invx_team_view_slider_overlay($atts){
	ob_start();

	$args['post_type'] = 'member_team';

	if (isset($atts['limit'])):
		$args['posts_per_page'] = $atts['limit'];
	else:
		$args['posts_per_page'] = '6';
	endif;

	if (isset($atts['order'])):
		$args['order'] = $atts['order'];
	else:
		$args['order'] = 'DESC';
	endif;

	$args['post_status'] = 'publish';

    if (isset($atts['content_limit'])):
		$limit = $atts['content_limit'];
	else:
		$limit = '20';
	endif;
	
	if(isset($atts['view'])):
		switch ($atts['view']) {
			case 'square':
				$class = 'square';		
				break;
			case 'round':
				$class = 'round';		
				break;
			default:
				$class = 'rounded';
				break;
		}else:
		$class = 'rounded';
	endif;


	if (isset($atts['column'])):
		switch ($atts['column']) {
		case '1':
			$column = '1';
			break;
		case '2':
			$column = '2';
			break;
		case '3':
			$column = '3';
			break;
		case '4':
			$column = '4';
			break;
		default:
			$column = '3';
			break;
		} else :
		$column = '3';
	endif;

	if(isset($atts['group'])):
		if ($atts['group'] != ''):
    	    $args['tax_query'] = array(
    	    		array(
    	   				'taxonomy' => 'invx_team_group',
    	   				'terms' => $atts['group'],
    	   				'field' => 'slug',
    	   				'include_children' => true,
    	   			)
	   			);
    	endif;
    endif;

	$team = new WP_Query($args);
	echo '<div class="team-overlay-slider">';
	while ($team->have_posts()): $team->the_post();
		$post_id = get_the_ID();
		$invx_mobile_view = get_post_meta($post_id, 'invx_team_view_mobile', true);
		$invx_email_view = get_post_meta($post_id, 'invx_team_view_email', true);
		$invx_skype_view = get_post_meta($post_id, 'invx_team_view_skype', true);
		$invx_facebook_view = get_post_meta($post_id, 'invx_team_view_fb', true);
		$invx_twitter_view = get_post_meta($post_id, 'invx_team_view_twitter', true);
		$invx_linkedin_view = get_post_meta($post_id, 'invx_team_view_linkedin', true);
		$invx_googelplus_view = get_post_meta($post_id, 'invx_team_view_googelplus', true);
		$invx_member_link = get_post_meta($post_id, 'invx_team_view_link', true);

		$img = get_the_post_thumbnail('', 'medium', '');
		if ( $img != '' ):
			$img = get_the_post_thumbnail('', 'medium', '');
		else:
			$img = '<img src="'.TEAM_VIEW_INVX_URI.'/images/user-icon.jpg" class="demo-image"/>';
		endif;


		echo '<div class="team-grid overlay-grid text-center col-xs-12">
				<div class="team-content '.$class.'">
					<div class="member-image">
					  <a href="' . get_permalink() . '" class="image-link" title="' . get_the_title() . '">' .$img. '</a>
			    	</div>
					<div class="content-overlay '.$class.'">
					 	 <div class="team-section">
					 		 <div class="member-description"><p>"'.wp_trim_words( get_the_content(), $limit, '...' ).'"</p></div>
						</div>
					</div>
				</div>
				<div class="member-title">
				  <a href="' . get_permalink() . '" title="' . get_the_title() . '"><h4>' . get_the_title() . '</h4>
				  <div class="title-border"></div>
				  </a>
				</div>
				<div class="member-designation">' . get_post_meta($post_id, 'invx_team_view_designation', true) . '</div>
				<div class="social-media-links '.$class.'">
					<ul>';
		if ($invx_mobile_view != ''):
			echo '<li><a href="tel:' . $invx_mobile_view . '"><i class="fa fa-mobile"></i></a></li>';
		endif;
		if ($invx_email_view != ''):
			echo '<li><a href="mailto:' . $invx_email_view . '"><i class="fa fa-envelope"></i></a></li>';
		endif;
		if ($invx_skype_view != ''):
			echo '<li><a href="skype:' . $invx_skype_view . '?chat" target="_blank"><i class="fa fa-skype"></i></a></li>';
		endif;
		if ($invx_facebook_view != ''):
			echo '<li><a href="' . $invx_facebook_view . '" target="_blank"><i class="fa fa-facebook"></i></a></li>';
		endif;
		if ($invx_twitter_view != ''):
			echo '<li><a href="' . $invx_twitter_view . '" target="_blank"><i class="fa fa-twitter"></i></a></li>';
		endif;
		if ($invx_linkedin_view != ''):
			echo '<li><a href="' . $invx_linkedin_view . '" target="_blank"><i class="fa fa-linkedin"></i></a></li>';
		endif;
		if ($googelplus != ''):
			echo '<li><a href="' . $googelplus . '" target="_blank"><i class="fa fa-google-plus"></i></a></li>';
		endif;
		if ($invx_member_link != ''):
				echo '<li><a href="' . $invx_member_link . '" target="_blank"><i class="fa fa-chain"></i></a></li>';
		endif;
		echo '</ul>
		      </div>
		 </div>';
	endwhile; ?>
</div>
	<script>
		jQuery(document).ready(function(){
			var img_width = jQuery('.team-content .member-image > a').width();
			var img_height = jQuery('.team-content .member-image > a').height();
			jQuery('.content-overlay').css('width',(img_width+16)+'px');
			jQuery('.content-overlay').css('height',(img_height+16)+'px');
			var overlay_width = jQuery('.overlay-grid .team-content').width();
			var overlay_height = jQuery('.overlay-grid .team-content').height();
			jQuery('.overlay-grid .team-content .content-overlay').css('width',(img_width+16)+'px');
			jQuery('.overlay-grid .team-content .content-overlay').css('height',(img_height+16)+'px');
		   "use strict";
		   // Team Carousel
			jQuery("div.team-overlay-slider").owlCarousel({
			 autoPlay:false,
			 responsive:true,
			 navigation:true,
			 navigationText:["<i class=\'fa fa-angle-left\' ></i>","<i class=\'fa fa-angle-right\' ></i>"],
			 pagination:false,
			 rewindSpeed:1000,
			 items:<?php echo $column;?>,
			 itemsDesktop:[1200,3],
			 itemsTablet:[991,2],
			 itemsMobile:[767,1]
			});
		});
	</script>
	<?php
	return ob_get_clean();
}
add_shortcode('team-slider-overlay', 'invx_team_view_slider_overlay');
