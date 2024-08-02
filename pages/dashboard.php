<?php

require_once('view-locations.php');
require_once('view-practitioner.php');
require_once('view-services.php');
require_once('view-classes.php');
require_once('view-shortcode.php');


class Dashboard{

    public $locationObj;
    public $practitionerObj;
    public $servicesObj;
    public $classesObj;
    public $shortcodeObj;
    
    
    
    function __construct()
    {
        // Create Objects
        add_action('init', array($this, 'add_cors_http_header'));

        $this->locationObj = new LocationClass;
        $this->practitionerObj = new PractitionerClass;
        $this->servicesObj = new ServicesClass;
        $this->classesObj = new ClassesClass;
        $this->shortcodeObj = new ShortcodeClass;
        
        
        $this->ViewDashboard();
    }

    function add_cors_http_header(){

        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");
        header('Content-Type: application/json');
    }
    
    function ViewDashboard(){
        add_action('admin_menu', array($this,'ShowAdminMenu'));
        add_action('admin_init', array($this,'ViewDashboardLayoutFields'));
    }

    function ShowAdminMenu(){
        add_menu_page(
            'Nookal Appointment Booking',
            'Nookal Booking',
            'manage_options',
            'nab-settings',
            array($this,'ViewDashboardLayout')
        );
        // Add submenu page
        add_submenu_page(
            'nab-settings',                // Parent slug
            'Nookal Booking Settings',     // Page title
            'Settings',                    // Submenu title
            'manage_options',              // Capability
            'nab-settings',                // Submenu slug
            array($this,'ViewDashboardLayout') // Callback function
        );
        
        if($this->CheckAPIKeyStatus()){
            // Add another submenu page
            add_submenu_page(
                'nab-settings',                 // Parent slug
                'Location',          // Page title
                'Location',                         // Submenu title
                'manage_options',               // Capability
                'nab-location',                     // Submenu slug
                array($this->locationObj, 'ViewLocationLayout')          // Callback function
            );

            // Add another submenu page
            add_submenu_page(
                'nab-settings',                 // Parent slug
                'Practitioner',          // Page title
                'Practitioner',                         // Submenu title
                'manage_options',               // Capability
                'nab-practitioner',                     // Submenu slug
                array($this->practitionerObj, 'ViewPractitionerLayout')          // Callback function
            );
            
            // Add another submenu page
            add_submenu_page(
                'nab-settings',                 // Parent slug
                'Nookal Services View',          // Page title
                'Services',                         // Submenu title
                'manage_options',               // Capability
                'nab-services-view',                     // Submenu slug
                array($this->servicesObj,'ViewServicesLayout')          // Callback function
            );
            
            // Add another submenu page
            add_submenu_page(
                'nab-settings',                 // Parent slug
                'Nookal Classes View',          // Page title
                'Classes',                         // Submenu title
                'manage_options',               // Capability
                'nab-classes-view',                     // Submenu slug
                array($this->classesObj,'ViewClassesLayout')          // Callback function
            );
            
            // Add another submenu page
            add_submenu_page(
                'nab-settings',                 // Parent slug
                'Nookal Booking View',          // Page title
                'Booking',                         // Submenu title
                'manage_options',               // Capability
                'nab-booking-view',                     // Submenu slug
                'nab_render_booking_view_page'          // Callback function
            );



            // Add another submenu page
            add_submenu_page(
                'nab-settings',                 // Parent slug
                'Nookal Booking Help',          // Page title
                'Help',                         // Submenu title
                'manage_options',               // Capability
                'nab-help',                     // Submenu slug
                array($this, 'ViewHelpPageLayout')          // Callback function
            );

            // Add another submenu page
            add_submenu_page(
                'nab-settings',                 // Parent slug
                '',          // Page title
                false,                         // Submenu title
                'manage_options',               // Capability
                'nab-patient',                     // Submenu slug
                'nab_render_patient_page'          // Callback function
            );
        }
    }


    function ViewDashboardLayout(){
    ?>
        <div class="wrap">
            <h1>Nookal Appointment Booking Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('nab_settings_group');
                do_settings_sections('nab-settings');
                submit_button();
                ?>
            </form>
        </div>
    <?php   
    }

    function ViewDashboardLayoutFields(){
        register_setting('nab_settings_group', 'nab_nookal_api_key');

        add_settings_section(
            'nab_settings_section',
            'API Settings',
            null,
            'nab-settings'
        );

        add_settings_field(
            'nab_nookal_api_key',
            'Nookal API Key',
            array($this,'nab_render_nookal_api_key_field'),
            'nab-settings',
            'nab_settings_section',
            array( 
                'label_for'    => 'nab_nookal_api_key',
                'description'  => __( 'For other pages need to activate api key', 
                                'nab_nookal_api_key' )
            )
        );

        add_settings_field(
            'nab_shortcode_field',
            'Booking Form Shortcode',
            array($this, 'nab_render_shortcode_field'),
            'nab-settings',
            'nab_settings_section',
            array(
                'label_for'   => 'nab_shortcode_field',
                'description' => __( 'Click the button to copy the shortcode to your clipboard.', 'nab_shortcode_field' ),
                'shortcode' => $this->shortcodeObj->shortcodeName
            )
        );
    }

    // Render API key field
    function nab_render_nookal_api_key_field($args) {
        $api_key = get_option('nab_nookal_api_key');
        Nookal_API::apiKey($api_key);
        $response = $this->CheckAPIKeyStatus();

        ?>
        <input type="text" name="nab_nookal_api_key" value="<?php echo esc_attr($api_key); ?>" class="regular-text">
        <p class="description">
            <?php echo esc_html($args['description']); ?>
        </p>
        <div class="apikey-status <?php echo $response?"status-active":"status-deactive";?>"><?php echo $response?"Api Key activated":"wrong key";?></div>
        <?php
    }

    public function nab_render_shortcode_field($args) {
        // Define your default shortcode here
        
        ?>
        <input type="text" id="<?php echo esc_attr($args['label_for']); ?>" value="<?php echo esc_attr($args['shortcode']); ?>" readonly />
        <button type="button" onclick="copyShortcode()">Copy Shortcode</button>
        <p class="description">
            <?php echo esc_html($args['description']); ?>
        </p>
        <?php
    }
    

    function CheckAPIKeyStatus(){
        $verify = Nookal_API::gateway()->verify();
        return $verify->isVerified();
    }

    function ViewHelpPageLayout(){
        // Render the help page
        ?>
            <div class="wrap">
                <h1>Nookal Appointment Booking Help</h1>
                <p>Here you can find help regarding the Nookal Appointment Booking plugin.</p>
            </div>
        <?php
    }
}