<?php

/** Register Activation Hook */
function plyr_wp_activation() {

    // Set default unlicensed options
    set_plyr_wp_options();

}

/** Register Deactivation Hook */
function plyr_wp_deactivation() {

    // Set default unlicensed options
    set_plyr_wp_options();

}
