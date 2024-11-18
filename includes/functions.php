<?php

/** 
 * Functions  
*/

/** Set Initial Options */
function set_plyr_wp_options(){
    update_option('plyr_wp_update_api', 'https://studioinspirasi.com/api/wp/update/plyr-wp/plyr-wp.php', false);
    update_option('plyr_wp_slug', 'plyr-wp', false);
    update_option('plyr_wp_basename', plugin_basename(__FILE__), false);
    update_option('plyr_wp_author', 'https://studioinspirasi.com', false);
    update_option('plyr_wp_documentation_url', 'https://studioinspirasi.com/docs/plyr-wp', false);
    update_option('plyr_wp_support_developer_url', 'https://studioinspirasi.com/support-developer', false);
}

/** Action Links */
function add_plyr_wp_action_links($links) {
    // Mandatory Action Links
    $mylinks = array(
        '<a href="' . get_option('plyr_wp_support_developer_url') . '" target="_blank">Support</a>',
    );
    
    // Additional Action Links
    $mylinks[] = '<a href="' . get_option('plyr_wp_documentation_url') . '">Docs</a>';

    return array_merge($links, $mylinks);
}

add_shortcode( 'plyr-wp', 'plyr' );

function plyr( $atts ) {
	
	$a = shortcode_atts( array(
		'url' 		=> '',
		'color' 	=> '',
		'autoplay' 	=> ''
	), $atts );

	// Get URL	
	$url = esc_url( $a['url'] );
	preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
	$youtube_id = $match[1];

	// Get Attributes
	$color 		= !empty($a['color']) ? esc_attr($a['color']) : '#FF0000';
	$autoplay 	= !empty($a['autoplay']) ? esc_attr($a['autoplay']) : 'false';
	$autoplyr 	= $autoplay == 'true' ? '1' : '0';

	// Returned
	$output = '
		
		<link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
		<script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>
		
		<div class="video" id="player" style="--plyr-color-main: '. $color .';" data-plyr-config="{ \'autoplay\': \''.$autoplay.'\' }" data-plyr-provider="youtube" data-plyr-embed-id="' . $youtube_id .'"></div>
		
		<script>
			const player = new Plyr("#player", {
				youtube:	{ noCookie:false, rel: 0, showinfo: 0, iv_load_policy: 3, modestbranding: 1, autoplay: '.$autoplyr.' },
				autoplay:	'.$autoplay.'
			});
			window.player = player;
		</script>
		
	';
		
	return $output;

}