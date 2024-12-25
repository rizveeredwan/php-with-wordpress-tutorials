<?php // php block start
/*
Plugin Name: Simple Communication Plugin
Plugin URI: https://example.com/
Description: A simple plugin for communication with WordPress.
Version: 1.0
Author: Redwan Ahmed Rizvee
Author URI: https://rizveeredwan.github.io/
*/

// Hook to add a simple message to the front-end
function scp_display_message() { // function block starts 
    echo '<div class="scp-message">Hello, this is a simple message from your WordPress plugin!</div>'; // a message will be shown in the web page 
} // function block ends 

// Hook to add the message to WordPress pages
add_action('wp_footer', 'scp_display_message'); 

// Example function to add a shortcode for dynamic message display
function scp_shortcode_message() {
    return 'This is a dynamic message generated by the Simple Communication Plugin!'; // a string will return from the function 
}

add_shortcode('scp_message', 'scp_shortcode_message'); // using [scp_message], we would be able to access this function from any web page

// PHP block ends
?> 