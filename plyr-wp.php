<?php

/*
 * Plugin Name:       Plyr.io Player for WordPress
 * Plugin URI:        https://studioinspirasi.com/product/plyr-io-player-for-wordpress/
 * Description:       Bring Plyr.io player style in your WordPress
 * Version:           1.3
 * Author:            Studio Inspirasi
 * Author URI:        https://studioinspirasi.com/
 */
 
add_shortcode( 'plyr-wp', 'plyr' );

function plyr( $atts ) {
	
	$a = shortcode_atts( array(
		'url' => '',
		'color' => ''
	), $atts );

	// Get URL	
	$url = esc_url( $a['url'] );
	preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);
	$youtube_id = $match[1];

	// Get Attributes
	$color = !empty($a['color']) ? esc_attr($a['color']) : '#FF0000';

	// Returned
	$output = '
		
		<link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
		<script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>
		
		<div class="video" id="player" style="--plyr-color-main: '. $color .';" data-plyr-config="{ \'autoplay\': \'true\' }" data-plyr-provider="youtube" data-plyr-embed-id="' . $youtube_id .'"></div>
		
		<script>
			const player = new Plyr("#player", {
				youtube: 	{ noCookie:false, rel: 0, showinfo: 0, iv_load_policy: 3, modestbranding: 1, autoplay: 1 },
				autoplay: 	true
			});
			window.player = player;
		</script>
		
	';
		
	return $output;

}