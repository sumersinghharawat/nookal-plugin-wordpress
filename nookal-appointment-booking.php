<?php
/*
Plugin Name: Nookal Appointment Booking
Description: A plugin to book appointments and manage them using the Nookal platform.
Version: 1.0
Author: Your Name
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin constants
define('NAB_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('NAB_PLUGIN_URL', plugin_dir_url(__FILE__));

    // 6Cb7Ee14-EDDD-3634-ABB9-3eFD2a1a8425
    //code...
    // Include required files
    require_once NAB_PLUGIN_PATH . 'nookal_api_0.1.14/Base.php';
    require_once NAB_PLUGIN_PATH . 'nookal_api_0.1.14/Request.php';

    // requests
    require_once NAB_PLUGIN_PATH . 'nookal_api_0.1.14/requests/Requests.php';
    require_once NAB_PLUGIN_PATH . 'nookal_api_0.1.14/requests/Response.php';



    // types
    require_once NAB_PLUGIN_PATH . 'nookal_api_0.1.14/types/Type.php';
    require_once NAB_PLUGIN_PATH . 'nookal_api_0.1.14/types/Address.php';
    require_once NAB_PLUGIN_PATH . 'nookal_api_0.1.14/types/Appointments.php';
    require_once NAB_PLUGIN_PATH . 'nookal_api_0.1.14/types/Availability.php';
    require_once NAB_PLUGIN_PATH . 'nookal_api_0.1.14/types/Booking.php';
    require_once NAB_PLUGIN_PATH . 'nookal_api_0.1.14/types/Cases.php';
    require_once NAB_PLUGIN_PATH . 'nookal_api_0.1.14/types/Contacts.php';
    require_once NAB_PLUGIN_PATH . 'nookal_api_0.1.14/types/Extras.php';
    require_once NAB_PLUGIN_PATH . 'nookal_api_0.1.14/types/Files.php';
    require_once NAB_PLUGIN_PATH . 'nookal_api_0.1.14/types/Invoices.php';
    require_once NAB_PLUGIN_PATH . 'nookal_api_0.1.14/types/Locations.php';
    require_once NAB_PLUGIN_PATH . 'nookal_api_0.1.14/types/Matrix.php';
    require_once NAB_PLUGIN_PATH . 'nookal_api_0.1.14/types/Patients.php';
    require_once NAB_PLUGIN_PATH . 'nookal_api_0.1.14/types/Practitioner.php';
    require_once NAB_PLUGIN_PATH . 'nookal_api_0.1.14/types/PractitionerTypes.php';
    require_once NAB_PLUGIN_PATH . 'nookal_api_0.1.14/types/S3.php';
    require_once NAB_PLUGIN_PATH . 'nookal_api_0.1.14/types/TreatmentNotes.php';
    require_once NAB_PLUGIN_PATH . 'nookal_api_0.1.14/types/Class.php';
    require_once NAB_PLUGIN_PATH . 'nookal_api_0.1.14/types/Service.php';
    require_once NAB_PLUGIN_PATH . 'nookal_api_0.1.14/types/Stock.php';
    require_once NAB_PLUGIN_PATH . 'nookal_api_0.1.14/types/Upload.php';
    require_once NAB_PLUGIN_PATH . 'nookal_api_0.1.14/types/Verify.php';
    require_once NAB_PLUGIN_PATH . 'nookal_api_0.1.14/types/WaitingList.php';

    // APIs
    require_once NAB_PLUGIN_PATH . 'nookal_api_0.1.14/API.php';



// require_once NAB_PLUGIN_PATH . 'includes/nab-shortcodes.php';
// require_once NAB_PLUGIN_PATH . 'includes/nab-dashboard.php';
// require_once NAB_PLUGIN_PATH . 'includes/nab-admin.php';
// require_once NAB_PLUGIN_PATH . 'includes/nab-nookal-api.php';



require_once NAB_PLUGIN_PATH . 'pages/dashboard.php';

// Enqueue scripts and styles
function nab_enqueue_scripts() {
    // Get the file modification time
    $file = plugin_dir_path(__FILE__) . 'assets/css/style.css';
    $version = file_exists($file) ? filemtime($file) : '1.0.0';
    wp_enqueue_style('nab-styles', NAB_PLUGIN_URL . 'assets/css/style.css', array(), $version);

    
    $file = plugin_dir_path(__FILE__) . 'assets/js/script.js';
    $version = file_exists($file) ? filemtime($file) : '1.0.0';
    wp_enqueue_script('nab-scripts', NAB_PLUGIN_URL . 'assets/js/script.js', array('jquery'), $version, true);
}
add_action('wp_enqueue_scripts', 'nab_enqueue_scripts');

// Enqueue scripts and styles for the admin panel
function nab_admin_enqueue_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('pagination-js', 'https://pagination.js.org/dist/2.6.0/pagination.min.js', array('jquery'), '2.6.0', true);

    // Get the file modification time
    $file = NAB_PLUGIN_URL . 'assets/css/admin-style.css';
    $version = file_exists($file) ? filemtime($file) : '1.0.0';
    wp_enqueue_style('nab-admin-styles', NAB_PLUGIN_URL . 'assets/css/admin-style.css', array(), $version);
    wp_enqueue_script('nab-admin-scripts', NAB_PLUGIN_URL . 'assets/js/admin-script.js', array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'nab_admin_enqueue_scripts');


// Activation and deactivation hooks
function nab_activate_plugin() {
    // Code to run on activation
}
register_activation_hook(__FILE__, 'nab_activate_plugin');

function nab_deactivate_plugin() {
    // Code to run on deactivation
}
register_deactivation_hook(__FILE__, 'nab_deactivate_plugin');


new Dashboard();