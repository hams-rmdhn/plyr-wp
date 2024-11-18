<?php

/** 
 * Functions  
 */

/** Set Initial Options */
function set_plyr_wp_options() {
    update_option( 'plyr_wp_update_api', 'https://studioinspirasi.com/api/wp/update/plyr-wp/plyr-wp.php', false );
    update_option( 'plyr_wp_slug', 'plyr-wp', false );
    update_option( 'plyr_wp_basename', plugin_basename( __FILE__ ), false );
    update_option( 'plyr_wp_author', 'https://studioinspirasi.com', false );
    update_option( 'plyr_wp_documentation_url', 'https://docs.studioinspirasi.com/docs/getting-started/free-plugins/plyr-io-player-for-wordpress', false );
    update_option( 'plyr_wp_settings_url_page', admin_url( 'admin.php?page=inspirasi_player_settings' ), false );
    update_option( 'plyr_wp_support_developer_url', 'https://studioinspirasi.com/support-developer', false );
}

/** Action Links */
function add_plyr_wp_action_links( $links ) {
    // Mandatory Action Links
    $mylinks = array(
        '<a href="' . esc_url( get_option( 'plyr_wp_settings_url_page' ) ) . '" target="_blank">Settings</a>',
    );

    // Additional Action Links
    $mylinks[] = '<a href="' . esc_url( get_option( 'plyr_wp_documentation_url' ) ) . '" target="_blank">Docs</a>';

    return array_merge( $links, $mylinks );
}

add_shortcode( 'plyr-wp', 'plyr' );

function plyr( $atts ) {
    $a = shortcode_atts( array(
        'url'      => '',
        'color'    => '',
        'autoplay' => '',
    ), $atts );

    // Get all options for the page
    $options = get_option( 'plyr-wp-settings', array() );

    // Get URL	
    $url = esc_url( $a['url'] );
    preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match );

    // Check if YouTube ID is found
    $youtube_id = isset( $match[1] ) ? $match[1] : '';

    // Get Attributes
    $color     = ! empty( $a['color'] ) ? esc_attr( $a['color'] ) : ( isset( $options['theme_color'] ) ? esc_attr( $options['theme_color'] ) : '' );
    $autoplay  = ! empty( $a['autoplay'] ) ? esc_attr( $a['autoplay'] ) : ( isset( $options['autoplay'] ) ? esc_attr( $options['autoplay'] ) : '' );
    $autoplyr  = $autoplay === 'true' ? '1' : '0';

    // Returned
    $output = '
        <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
        <script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>
        
        <div class="video" id="player" style="--plyr-color-main: ' . esc_attr( $color ) . ';" data-plyr-config="{ \'autoplay\': \'' . esc_attr( $autoplay ) . '\' }" data-plyr-provider="youtube" data-plyr-embed-id="' . esc_attr( $youtube_id ) . '"></div>
        
        <script>
            const player = new Plyr("#player", {
                youtube: { noCookie: false, rel: 0, showinfo: 0, iv_load_policy: 3, modestbranding: 1, autoplay: ' . esc_js( $autoplyr ) . ' },
                autoplay: ' . esc_js( $autoplay ) . '
            });
            window.player = player;
			</script>

        </script>
    ';

    return $output;
}
