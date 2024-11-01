<?php
/**
* The template for displaying all single Member details
*
*
* @package WordPress
* @subpackage team-member
* @since 1.0
* @version 1.0
*/
get_header();
?>
<div class="member-page">
 <div class="row">
<?php
while ( have_posts()) {
the_post(); 
$post_id = get_the_ID();

$img = get_the_post_thumbnail('', 'medium', '');
		if ( $img != '' ):
			$img = get_the_post_thumbnail('', 'medium', '');
		else:
			$img = '<img src="'.site_url().'/wp-content/plugins/dm-team/images/user-icon.jpg" class="demo-image"/>';
		endif;
echo '<div class="col-md-4 col-sm-4 col-xs-12">
		<div class="member-content">
			<div class="member-image">
				' .$img . '
			</div>
		</div>
		<div class="member-designation text-center">
			<h1>'.get_post_meta($post->ID, '_invx_member_designation', true).'</h1>
		</div>';
		$invx_mobile = get_post_meta($post_id, '_team_member_mobile', true);
		$invx_email = get_post_meta($post_id, '_team_member_email', true);
		$invx_skype = get_post_meta($post_id, '_team_member_skype', true);
		$invx_facebook = get_post_meta($post_id, '_team_member_fb', true);
		$invx_twitter = get_post_meta($post_id, '_team_member_twitter', true);
		$invx_linkedin = get_post_meta($post_id, '_team_member_linkedin', true);
		$invx_googelplus = get_post_meta($post_id, '_team_member_googelplus', true);
		$invx_member_link = get_post_meta($post_id, '_invx_member_link', true);
		echo '<div class="member-content">
				<div class="social-media-links" id="member-social-links">
					<ul>';
						if ($invx_mobile != ''):
							echo '<li><a href="tel:' . $invx_mobile . '"><i class="fa fa-mobile"></i></a></li>';
						endif;
						if ($invx_email != ''):
							echo '<li><a href="mailto:' . $invx_email . '"><i class="fa fa-envelope"></i></a></li>';
						endif;
						if ($invx_skype != ''):
							echo '<li><a href="skype:' . $invx_skype . '?chat" target="_blank"><i class="fa fa-skype"></i></a></li>';
						endif;
						if ($invx_facebook != ''):
							echo '<li><a href="' . $invx_facebook . '" target="_blank"><i class="fa fa-facebook"></i></a></li>';
						endif;
						if ($invx_twitter != ''):
							echo '<li><a href="' . $invx_twitter . '" target="_blank"><i class="fa fa-twitter"></i></a></li>';
						endif;
						if ($invx_linkedin != ''):
							echo '<li><a href="' . $invx_linkedin . '" target="_blank"><i class="fa fa-linkedin"></i></a></li>';
						endif;
						if ($invx_googelplus != ''):
							echo '<li><a href="' . $invx_googelplus . '" target="_blank"><i class="fa fa-google-plus"></i></a></li>';
						endif;
						if ($invx_member_link != ''):
							echo '<li><a href="' . $invx_member_link . '" target="_blank"><i class="fa fa-chain"></i></a></li>';
						endif;
						echo '</ul>
					</div>
				</div>
			</div>
		<div class="col-md-8 col-sm-8 col-xs-12">
			<div class="text-left title-heading">
				<h1>'.get_the_title().'</h1>
			</div>
			<div class="member-text">
			'.get_the_content().'
			</div>
		</div>';
}
?>
</div>
</div>
<!-- Members Pagination -->
<div class="pagination-button">
   <div class="page-link-previous"><?php previous_post_link( '%link', '<i class="fa fa-angle-double-left"></i> Previous' )?></div>
   <div class="page-link-next"><?php next_post_link( '%link', 'Next <i class="fa fa-angle-double-right"></i>' )?></div>
</div>

<?php
//  Page sidebar section
get_sidebar();

// Default Footer section.
get_footer();
?>