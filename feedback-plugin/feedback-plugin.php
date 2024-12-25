<?php
/*
Plugin Name: Interactive Feedback Page Plugin
Description: A plugin to create a custom interactive page for feedback submissions and display.
Version: 1.0
Author: Redwan Ahmed Rizvee
*/

// Step 1: Create a custom table for feedback
function feedback_page_plugin_activate() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'feedback';
    $charset_collate = $wpdb->get_charset_collate();

    // writing the SQL code here 
    $sql = "CREATE TABLE $table_name (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL,
        feedback TEXT NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); // importing library function 
    dbDelta($sql); // execution of SQL 

    // Register rewrite rules for the custom page
    add_rewrite_rule('^interactive-feedback$', 'index.php?feedback_page=1', 'top');
    // add_rewrite_rule(page-url, which-php-file.php?query-paramater=value, where-this-rule-will-be-entered)
    flush_rewrite_rules(); // flush the rule for updating the page 
}
// registering this file and this function 
register_activation_hook(__FILE__, 'feedback_page_plugin_activate'); 

// Step 2: Flush rewrite rules on deactivation
function feedback_page_plugin_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook(__FILE__, 'feedback_page_plugin_deactivate');

// Step 3: Add a query variable for the custom page
function feedback_page_query_vars($vars) {
    $vars[] = 'feedback_page'; // our query parameter 
    return $vars;
}
// registering this function denoting that there will be query variables alongside which function will execute the queries 
add_filter('query_vars', 'feedback_page_query_vars');

// Step 4: Display content on the custom page
function feedback_page_template_redirect() {
    if (get_query_var('feedback_page')) { //trying to match if our given query is feedback_page 
        feedback_page_display(); // if it is, we are calling the function feedback_page_display() 
        exit; // exiting of this function 
    }
}
// a new template is called using the function feedback_page_template_redirect
add_action('template_redirect', 'feedback_page_template_redirect');

// Step 5: Function to display the feedback page
function feedback_page_display() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'feedback';

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['feedback_submit'])) {
        $name = sanitize_text_field($_POST['name']); // taking input for 'name', cleaning the value and storing it in name variable 
        $email = sanitize_email($_POST['email']); // taking input for 'email', cleaning the value and storing it in email variable
        $feedback = sanitize_textarea_field($_POST['feedback']); // taking input for 'feedback', cleaning the value and storing it in feedback variable

        // Insert feedback into the database
        $wpdb->insert($table_name, [
            'name' => $name,
            'email' => $email,
            'feedback' => $feedback
        ]); // adding this name, email and feeback to database 

        // A HTML block is being rendered 
        echo '<div class="notice success" style="color: green;">Feedback submitted successfully!</div>'; 
    }

    // Fetch all feedback from the database
    $feedbacks = $wpdb->get_results("SELECT * FROM $table_name");

    // Output the page HTML
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Interactive Feedback Page</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
                padding: 20px;
                max-width: 800px;
                margin: auto;
            }
            form {
                margin-bottom: 40px;
            }
            label {
                font-weight: bold;
            }
            input, textarea {
                width: 100%;
                padding: 10px;
                margin: 5px 0;
                border: 1px solid #ccc;
                border-radius: 4px;
            }
            input[type="submit"] {
                background-color: #4CAF50;
                color: white;
                border: none;
                cursor: pointer;
            }
            input[type="submit"]:hover {
                background-color: #45a049;
            }
            ul {
                list-style-type: none;
                padding: 0;
            }
            li {
                background: #f9f9f9;
                margin: 10px 0;
                padding: 10px;
                border-radius: 4px;
                border: 1px solid #ddd;
            }
        </style>
    </head>
    <body>
        <h1>Interactive Feedback Page</h1>
        <h2>Submit Your Feedback</h2>
        <form method="POST">
            <p>
                <label for="name">Name:</label><br>
                <input type="text" id="name" name="name" required>
            </p>
            <p>
                <label for="email">Email:</label><br>
                <input type="email" id="email" name="email" required>
            </p>
            <p>
                <label for="feedback">Feedback:</label><br>
                <textarea id="feedback" name="feedback" rows="5" required></textarea>
            </p>
            <p>
                <input type="submit" name="feedback_submit" value="Submit Feedback">
            </p>
        </form>

        <h2>All Feedback</h2>
        <?php if ($feedbacks): ?>
            <ul>
                <?php foreach ($feedbacks as $fb): ?>
                    <li><strong><?php echo esc_html($fb->name); ?>:</strong> <?php echo esc_html($fb->feedback); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No feedback submitted yet.</p>
        <?php endif; ?>
    </body>
    </html>
    <?php
}

