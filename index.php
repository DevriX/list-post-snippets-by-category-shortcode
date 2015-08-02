<?php

/*
Plugin Name: List post snippets by category shortcode
Description: List post snippets by category shortcode plugin lists all your posts from selected category you specify in the shortcode category attribute. lists a little excerpt of everything including a thumbnail, title, excerpt, and a read more tag.
Author: Samuel Elh
Version: 1.0
Author URI: http://sam.elegance-style.com
*/

function elh_posts_by_cat ( $atts ) {
	
	$a = shortcode_atts( array(
        'category' => 'name',
        'thumbnail' => ''
    ), $atts );
	ob_start();
	
	$cat_atts = strtolower("{$a['category']}");
	$dt_atts = "{$a['thumbnail']}";
	$html = '';
	   
	require_once( ABSPATH. "wp-blog-header.php" );
    query_posts('showposts=-1');
    if(have_posts()) {
        while(have_posts()) : the_post();
            $ids = get_the_ID(). ',';
            $list = array( $ids );
            foreach ($list as $items) {
                $id = get_the_ID();
				$categ = strtolower(get_the_category_list());
				if ( is_numeric( strpos($categ, $cat_atts) ) ) {

					$title = get_the_title();
					$link = home_url('/')."?p=$id";
					$def_thumb = wp_get_attachment_url( get_post_thumbnail_id($id) );
					$thumb = (  $def_thumb != '' ) ? $def_thumb : esc_attr( $dt_atts );
					$excerpt = strip_tags( substr(get_the_content(), 0, 100), ""). "...";

					$html .= "<style type=\"text/css\" media=\"all\">
								.elh-p-snippet {
									max-width: 100%;
								}
								.elh-p-snippet img {
									float: left;
									margin-right: 1em;
								}
								.elh-p-snippet h2 {
									float: left;
									width: 100%;
									margin: 0 0 .5em 0;
								}
								.elh-p-snippet .elh-left,
								.elh-p-snippet .elh-right {
									float: left;
									max-width: 50%;
								}
							</style>";
					$html .= "<div class=\"elh-p-snippet\">";
					$html .= "<div class=\"elh-left\">";
					$html .= "<a href=\"$link\"><img src=\"$thumb\" height=\"150\" width=\"250\" alt=\"$title\" ></a>";
					$html .= "</div>";
					$html .= "<div class=\"elh-right\">";
					$html .= "<h2><a href=\"$link\">$title</a></h2><br />";
					$html .= "<p>$excerpt<br />";
					$html .= "<a href=\"$link\">Read more</a></p>";
					$html .= "</div>";
					$html .= "</div>";
					$html .= "<div style=\"clear: both; margin-bottom: 1em;\"></div>";

				}
            }
    	endwhile;
	}	
    wp_reset_query();
					
	echo $html;
	return ob_get_clean();

}
add_shortcode('posts-by-cat', 'elh_posts_by_cat');

function lpsbcs_support_link( $links ) {
    $settings_link = '<a href="http://sam.elegance-style.com/contact-me/?reason=support+list+post+snippets+by+category+shortcode" target="_new">' . __( 'Support' ) . '</a>';
    array_push( $links, $settings_link );
  	return $links;
}
function lpsbcs_donate_link( $links ) {
    $settings_link = '<a href="http://go.elegance-style.com/donate/" target="_new">' . __( 'Donate' ) . '</a>';
    array_push( $links, $settings_link );
  	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin", 'lpsbcs_support_link' );
add_filter( "plugin_action_links_$plugin", 'lpsbcs_donate_link' );
