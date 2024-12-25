<?php
/*
Plugin Name: resume-plugin
Description: A plugin to show your resume page
Version: 1.0
Author: Redwan Ahmed Rizvee
*/

// Step 1: Create a custom table for feedback
function resume_page_plugin_activate() {
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); // importing library function  

    // Register rewrite rules for the custom page
    add_rewrite_rule('^my-resume$', 'index.php', 'top');
    // add_rewrite_rule(page-url, which-php-file.php?query-paramater=value, where-this-rule-will-be-entered)
    flush_rewrite_rules(); // flush the rule for updating the page 
}
// registering this file and this function 
register_activation_hook(__FILE__, 'resume_page_plugin_activate'); 

// Step 2: Flush rewrite rules on deactivation
function resume_page_plugin_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'resume_page_plugin_deactivate');

// Step 3: Add a query variable for the custom page
function resume_page_query_vars($vars) {
    $vars[] = 'resume'; // our query parameter 
    return $vars;
}
// registering this function denoting that there will be query variables alongside which function will execute the queries 
add_filter('query_vars', 'resume_page_query_vars');

// Step 4: Display content on the custom page
function resume_page_template_redirect() {
    resume_page_display(); // if it is, we are calling the function resume_page_display() 
    exit; // exiting of this function 
}
// a new template is called using the function feedback_page_template_redirect
add_action('template_redirect', 'resume_page_template_redirect');

// Step 5: Function to display the feedback page
function resume_page_display() {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Resume of Dr. Nazma Tara</title>
        <style>
            body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;      }
        .container {
            width: 80%;
            margin: auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);  }
        header {
            text-align: center;
            margin-bottom: 30px; }
        header h1 {
            font-size: 36px;
            color: #2c3e50;         }
        header p {            font-size: 18px;
            color: #7f8c8d; }
        .section { 
            background-color: #ecf0f1;
            margin-bottom: 30px; 
            padding: 15px;}
        .section h2 {color: #2980b9; }
        .section ul {padding: 0;    }
        .section ul li {margin-bottom: 10px; }
        .contact-info {
            background-color: #ecf0f1;
            padding: 15px;
            border-radius: 5px; }
        .contact-info ul {
            list-style-type: none;
            padding: 0;  }
        .contact-info ul li { margin: 5px 0;}
        footer {
            text-align: center;
            font-size: 14px;
            color: #7f8c8d;
            margin-top: 30px;   }
        </style>
    </head>
    <body>

    <div class="container">
        <!-- Header Section -->
        <header>
            <h1>CV of Dr. Nazma Tara</h1> <!-- Please change your name here -->
            <p>Associate Professor of National University</p> <!-- Please change your profession here -->
        </header>

        <!-- About Section -->
        <div class="section">
            <h2>About Me</h2>
            <!-- Please update regarding your enthusiasm here -->
            <p>Hello! I'm Dr. Nazma Tara, an enthusiastic educator with over 15 years of experience in university-level teaching and research. I am passionate about helping students succeed in their academic and professional careers. </p>
        </div>

        <!-- Contact Information Section -->
        <div class="section contact-info">
            <h2>Contact Information</h2>
            <ul>
                <!-- give your email address here -->
                <li>Email: <a href="mailto:nazma.tara@nu.ac.bd">nazma.tara@nu.ac.bd</a></li>
                <!-- give your phone number here -->
                <li>Phone: +880 1710839236</li>
            </ul>
        </div>
    </div> 

    </body>
    </html>
    <?php
}?>

