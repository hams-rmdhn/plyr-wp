<?php

/** Set Initial Options */
function set_bambu_runcing_options() {
    update_option( 'bambu_runcing_update_api', 'https://studioinspirasi.com/api/wp/update/bambu-runcing/bambu-runcing.php', false );
    update_option( 'bambu_runcing_slug', 'bambu-runcing', false );
    update_option( 'bambu_runcing_basename', plugin_basename( __FILE__ ), false );
    update_option( 'bambu_runcing_author', 'https://studioinspirasi.com', false );
    update_option( 'bambu_runcing_documentation_url', 'https://docs.studioinspirasi.com/docs/getting-started/free-plugins/bambu-runcing-security', false );
    update_option( 'bambu_runcing_settings_url_page', admin_url( 'admin.php?page=bambu_runcing_security' ), false );
    update_option( 'bambu_runcing_support_developer_url', 'https://studioinspirasi.com/support-developer', false );
}

/** Action Links */
function add_bambu_runcing_action_links( $links ) {
    // Mandatory Action Links
    $mylinks = array(
        '<a href="' . esc_url( get_option( 'bambu_runcing_settings_url_page' ) ) . '" target="_blank">Settings</a>',
    );

    // Additional Action Links
    $mylinks[] = '<a href="' . esc_url( get_option( 'bambu_runcing_documentation_url' ) ) . '" target="_blank">Docs</a>';

    return array_merge( $links, $mylinks );
}

// Get all options for the page
$options = get_option( 'bambu-runcing-security', array() );

// XML RPC
if (isset($options['xml-rpc']) && $options['xml-rpc'] === 'false') {
    add_filter('xmlrpc_enabled', '__return_false');
}

// Define the function once to avoid redefinition errors
if (!function_exists('remove_wp_version_strings')) {
    function remove_wp_version_strings($src) {
        return remove_query_arg('ver', $src);
    }
}

// WP Version
if (isset($options['wordpress_version']) && $options['wordpress_version'] === 'false') {
    // Remove WordPress version meta tag from the <head> section
    remove_action('wp_head', 'wp_generator');

    // Remove WordPress version from RSS feeds
    add_filter('the_generator', '__return_empty_string');

    // Disable version in scripts and styles
    add_filter('style_loader_src', 'remove_wp_version_strings', 10, 2);
    add_filter('script_loader_src', 'remove_wp_version_strings', 10, 2);
}

// File Editor
if (isset($options['file_editor']) && $options['file_editor'] === 'false') {
    function brs_disable_file_edit() {
        define( 'DISALLOW_FILE_EDIT', true );
    }
    add_action( 'init', 'brs_disable_file_edit' );
}

// Limit Login Attempts
if (isset($options['limit_login_attempts'])) {
    // Function to limit login attempts
    function brs_limit_login_attempts() {
        $max_login_attempts = 3; // Set the maximum number of login attempts
        $login_attempts = (int) get_transient( 'brs_login_attempts_' . $_SERVER['REMOTE_ADDR'] );

        if ( $login_attempts >= $max_login_attempts ) {
            wp_die( 'Too many login attempts. Please try again later.' );
        }
    }
    add_action( 'wp_login_failed', 'brs_limit_login_attempts' );

    // Function to record login attempts
    function brs_record_login_attempt() {
        $login_attempts = (int) get_transient( 'brs_login_attempts_' . $_SERVER['REMOTE_ADDR'] );
        $login_attempts++;
        set_transient( 'brs_login_attempts_' . $_SERVER['REMOTE_ADDR'], $login_attempts, 3600 ); // Reset every hour
    }
    add_action( 'wp_login_failed', 'brs_record_login_attempt' );
}

// Admin Bar
if (isset($options['admin_bar']) && $options['admin_bar'] === 'false') {
    // Function to disable the admin bar for all users except administrators
    function brs_disable_admin_bar_for_non_admins() {
        if ( ! current_user_can( 'administrator' ) ) {
            return false; // Disable the admin bar for all non-administrators
        }
        return true; // Enable the admin bar for administrators
    }
    add_filter( 'show_admin_bar', 'brs_disable_admin_bar_for_non_admins' );
}


// WordPress Logo
if (isset($options['wordpress_logo']) && $options['wordpress_logo'] === 'false') {
    function brs_remove_wp_logo( $wp_admin_bar ) {
        $wp_admin_bar->remove_node( 'wp-logo' );
    }
    add_action( 'admin_bar_menu', 'brs_remove_wp_logo', 999 );
}

// Function to remove the WordPress footer credit
if (isset($options['wordpress_footer_credit']) && $options['wordpress_footer_credit'] === 'false') {
    function brs_remove_footer_admin() {
        echo '';
    }
    add_filter( 'admin_footer_text', 'brs_remove_footer_admin' );
}

// Function to remove the WordPress version number from the footer
if (isset($options['wordpress_version_credit']) && $options['wordpress_version_credit'] === 'false') {
    function brs_remove_footer_version() {
        return ''; 
    }
    add_filter( 'update_footer', 'brs_remove_footer_version', 999 );
}

// Function to remove the update menu from the admin bar
if (isset($options['update_bar']) && $options['update_bar'] === 'false') {
    function brs_remove_update_menu( $wp_admin_bar ) {
        $wp_admin_bar->remove_node( 'updates' ); // Remove the updates menu
    }
    add_action( 'admin_bar_menu', 'brs_remove_update_menu', 999 ); // Attach the function to the admin bar menu
}

// Function to remove the comments menu from the admin bar
if (isset($options['comment_bar']) && $options['comment_bar'] === 'false') {
    function brs_remove_comments_menu( $wp_admin_bar ) {
        $wp_admin_bar->remove_node( 'comments' ); // Remove the comments menu
    }
    add_action( 'admin_bar_menu', 'brs_remove_comments_menu', 999 ); // Attach the function to the admin bar menu
}

// Function to remove all submenus under the New menu in the admin bar
if (isset($options['newpost_bar']) && $options['newpost_bar'] === 'false') {
    function brs_remove_new_submenus( $wp_admin_bar ) {
        // Remove specific submenus under the New menu
        $wp_admin_bar->remove_node( 'new-post' );      // Remove New Post
        $wp_admin_bar->remove_node( 'new-media' );     // Remove New Media
        $wp_admin_bar->remove_node( 'new-page' );      // Remove New Page
        $wp_admin_bar->remove_node( 'new-user' );      // Remove New User
        // Add any other submenu items you want to remove
    }
    add_action( 'admin_bar_menu', 'brs_remove_new_submenus', 999 ); // Attach the function to the admin bar menu
}

// Honeypot
if (isset($options['honeypot']) && $options['honeypot'] === 'true') {
    // Add honeypot field to the login form
    function brs_add_honeypot_login() {
        echo '<input type="text" name="brs_honeypot" style="display:none;" />';
    }
    add_action('login_form', 'brs_add_honeypot_login');
    // Validate honeypot field
    function brs_validate_honeypot_login($user, $username, $password) {
        if (isset($_POST['brs_honeypot']) && !empty($_POST['brs_honeypot'])) {
            return new WP_Error('spam_login', __('Spam detected. Please try again.'));
        }
        return $user;
    }
    add_filter('wp_authenticate_user', 'brs_validate_honeypot_login', 10, 3);

    // Add honeypot field to the registration form
    function brs_add_honeypot_registration() {
        echo '<input type="text" name="brs_honeypot" style="display:none;" />';
    }
    add_action('register_form', 'brs_add_honeypot_registration');
    // Validate honeypot field
    function brs_validate_honeypot_registration($errors) {
        if (isset($_POST['brs_honeypot']) && !empty($_POST['brs_honeypot'])) {
            $errors->add('spam_registration', __('Spam detected. Please try again.'));
        }
        return $errors;
    }
    add_filter('registration_errors', 'brs_validate_honeypot_registration');

    // Add honeypot field to the password reset form
    function brs_add_honeypot_password_reset() {
        echo '<input type="text" name="brs_honeypot" style="display:none;" />';
    }
    add_action('password_reset', 'brs_add_honeypot_password_reset');
    // Validate honeypot field for password reset
    function brs_validate_honeypot_password_reset($user) {
        if (isset($_POST['brs_honeypot']) && !empty($_POST['brs_honeypot'])) {
            return new WP_Error('spam_password_reset', __('Spam detected. Please try again.'));
        }
        return $user;
    }
    add_filter('allow_password_reset', 'brs_validate_honeypot_password_reset');

    // Add honeypot field to the comment form
    function brs_add_honeypot_comment() {
        echo '<input type="text" name="brs_honeypot" style="display:none;" />';
    }
    add_action('comment_form_after_fields', 'brs_add_honeypot_comment');
    // Validate honeypot field for comments
    function brs_validate_honeypot_comment($commentdata) {
        if (isset($_POST['brs_honeypot']) && !empty($_POST['brs_honeypot'])) {
            wp_die(__('Spam detected. Your comment has not been submitted.'));
        }
        return $commentdata;
    }
    add_filter('preprocess_comment', 'brs_validate_honeypot_comment');

}


// Function to set security headers
if (isset($options['security_headers']) && $options['security_headers'] === 'true') {
    // Function to set security headers for logged-out users
    function brs_set_security_headers() {
        // Set X-Content-Type-Options to prevent MIME type sniffing
        header("X-Content-Type-Options: nosniff");
        
        // Set X-XSS-Protection to enable XSS protection
        header("X-XSS-Protection: 1; mode=block");
        
        // Set X-Frame-Options to prevent clickjacking
        header("X-Frame-Options: DENY");
        
        // Set HTTP Strict Transport Security (HSTS) to enforce HTTPS
        header("Strict-Transport-Security: max-age=31536000;");
		
		// Set Content Security Policy
        header("Content-Security-Policy: upgrade-insecure-requests;");
		
		// Set HTTP Referrer Policy
        header("Referrer-Policy: strict-origin-when-cross-origin;");
		
        // Set Permission Policy
		header("Permissions-Policy: accelerometer=(), autoplay=(), camera=(), cross-origin-isolated=(), display-capture=(self), encrypted-media=(), fullscreen=*, geolocation=(self), gyroscope=(), keyboard-map=(), magnetometer=(), microphone=(), midi=(), payment=*, picture-in-picture=*, publickey-credentials-get=(), screen-wake-lock=(), sync-xhr=*, usb=(), xr-spatial-tracking=(), gamepad=(), serial=()");
    }

    // Hook the function to the init action
    add_action('init', 'brs_set_security_headers');
}

// Function to enforce HTTPS
if (isset($options['always_https']) && $options['always_https'] === 'true') {
    function brs_enforce_https() {
        // Check if the request is not secure
        if ( ! is_ssl() ) {
            // Check if the request is not already over HTTPS
            header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], true, 301);
            exit;
        }
    }

    // Hook into the 'init' action to perform the check
    add_action('init', 'brs_enforce_https');
}

// Function to redirect all users after login
if (isset($options['redirect_after_login'])) {
    function brs_redirect_all_users_after_login($redirect_to, $request, $user) {
        $options    = get_option( 'bambu-runcing-security', array() );
        $redirect_to = $options['redirect_after_login'];
        return $redirect_to;
    }

    // Hook the function to the login_redirect filter
    add_filter('login_redirect', 'brs_redirect_all_users_after_login', 10, 3);
}

if (isset($options['redirect_after_registration'])) {
    // Function to redirect users after registration
    function brs_redirect_after_registration() {
        $options    = get_option( 'bambu-runcing-security', array() );
        $redirect_to = $options['redirect_after_login'];
        return $redirect_to;
    }

    // Hook the function to the registration_redirect filter
    add_filter('registration_redirect', 'brs_redirect_after_registration');

}