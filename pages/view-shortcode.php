<?php

class ShortcodeClass{

    public $shortcodeName;

    function __construct()
    {   
        $this->shortcodeName = '[Nookal_booking_form]';

        // Create shortcode
        add_action('init', array($this, 'register_my_custom_shortcode'));

        // Nookal Form APIs
        $this->NookalFormAPIs();
    }

    function ViewShortCode(){
        ?>
        <section style="width: 600px;margin: auto;">
            <div class="loader">
                <div>
                    <div class="nb-spinner"></div>
                </div>
            </div>
            <div id="booking-form">
				<form id="booking-steps-form">
                    <!-- Step 1: Select Location or Practitioner -->
                    <div class="step-from-panel">
                        <div class="selection-form">
                            <h3>Select Location or Practitioner</h3>
                            <label class="nab-button">
                                <input type="radio" class="input-step-1 active" name="selection" value="location" onchange="CheckSelection(this.value)"> Select Location
                            </label>
                            <label class="nab-button">
                                <input type="radio" class="input-step-1" name="selection" value="practitioner" onchange="CheckSelection(this.value)"> Choose Practitioner
                            </label>
                        </div>

                        <div id="location-selection" class="location-form" style="display: none;">
                            <h3>Select Location</h3>
                            <div class="locations-list" id="locations-list"></div>
                        </div>

                        <div id="practitioner-selection" class="practitioner-form" style="display: none;">
                            <h3>Select Practitioner</h3>
                            <div class="practitioners-list" id="practitioners-list"></div>
                        </div>
                    
                        <div class="classes-services" id="classes-services" style="display: none;">
                            <h3 >Select Appointment or Class</h3>
                            <div id="appointment-class-tabs">
                                <button type="button" id="tab-appointment" class="tab-button">Appointment</button>
                                <button type="button" id="tab-class" class="tab-button">Class</button>
                            </div>
                            <div id="appointment-content" class="tab-content">
                                <h4>Appointment</h4>
                                <div class="service-list" id="service-list"></div>
                                <!-- Appointment details -->
                            </div>
                            <div id="class-content" class="tab-content" style="display: none;">
                                <h4>Class</h4>
                                <div class="class-list" id="class-list"></div>
                                <!-- Class details -->
                            </div>
                        </div>

                        <div class="booking-slots" style="display: none;">
                            <h3 >Select Your Slot</h3>
                            <div class="filters">
                                <select id="month-dropdown"></select>
                                <select id="year-dropdown"></select>
                            </div>
                            <div class="calendar-container">
                                <div id="calendar"></div>
                                <div id="booking-slots"></div>
                            </div>
                        </div>

                        <div class="booking-patient-details" style="display: none;">
                            <h3 >Enter Your details</h3>
                            <label> First Name
                                <input type="text" placeholder="First Name" class="input-form-data" id="fname"/> 
                            </label>
                            <label> Last Name
                                <input type="text" placeholder="Last Name" class="input-form-data" id="lname"/> 
                            </label>
                            <label> Date of Birth
                                <input type="date" placeholder="Date of Birth" class="input-form-data" id="dob"/> 
                            </label>
                            <label> Email
                                <input type="email" placeholder="Email" class="input-form-data" id="email"/> 
                            </label>
                            <label> Phone
                                <input type="tel" placeholder="Phone" class="input-form-data" id="phone"/> 
                            </label>
                            <label> Do you want to share anything?
                                <textarea class="textarea-form-data" placeholder="Note" id="note"></textarea>
                            </label>
                            <button type="button" id="submitForm" onclick="submitBookingForm()" >Book Now</button>
                        </div>
                        <div class="thank-you" style="display: none;"></div>
                    </div>
                </form>
            </div>
        </section>
        <script>
            
        </script>
        <?php
    }

    function register_my_custom_shortcode() {
        add_shortcode('Nookal_booking_form', array($this,'ViewShortCode'));
    }

    function get_locations(){
        $locations = Nookal_API::gateway()->locations();
        $locations = $locations->children();
        $locations_list = [0 => "All"];
        foreach($locations as $location){
            $locations_list[] = $location->name();
        }
        return $locations_list;
    }

    function get_practitioners(){
        $practitioners = Nookal_API::gateway()->practitioners();
        $practitioners = $practitioners->children();

        // print_r($practitioners);

        $practitioners_list = [0 => "All"];
        foreach($practitioners as $practitioner){
            $practitioners_list[$practitioner->ID()] = $practitioner->firstName()." ".$practitioner->lastName();
        }
        return $practitioners_list;
    }

    function NookalFormAPIs(){
        // Get Locations
        add_action('rest_api_init', function () {
            register_rest_route('nookal-apis/v1', '/getlocations/', array(
                'methods'  => 'POST',
                'callback' => array($this,'get_locations_api_callback'),
                'permission_callback' => '__return_true',
            ));

            register_rest_route('nookal-apis/v1', '/getpractitioners/', array(
                'methods'  => 'POST',
                'callback' => array($this,'get_practitioners_api_callback'),
                'permission_callback' => '__return_true',
            ));

            register_rest_route('nookal-apis/v1', '/getservicesbylocations/', array(
                'methods'  => 'POST',
                'callback' => array($this,'get_services_by_location_api_callback'),
                'permission_callback' => '__return_true',
            ));

            register_rest_route('nookal-apis/v1', '/getclassesbylocations/', array(
                'methods'  => 'POST',
                'callback' => array($this,'get_classes_by_location_api_callback'),
                'permission_callback' => '__return_true',
            ));

            register_rest_route('nookal-apis/v1', '/getappointmentavailable/', array(
                'methods'  => 'POST',
                'callback' => array($this,'get_appointment_availabilities_api_callback'),
                'permission_callback' => '__return_true',
            ));

            register_rest_route('nookal-apis/v1', '/getclassavailable/', array(
                'methods'  => 'POST',
                'callback' => array($this,'get_class_availabilities_api_callback'),
                'permission_callback' => '__return_true',
            ));

            register_rest_route('nookal-apis/v1', '/bookappointment/', array(
                'methods'  => 'POST',
                'callback' => array($this,'book_appointment_api_callback'),
                'permission_callback' => '__return_true',
            ));

            register_rest_route('nookal-apis/v1', '/viewbookingbylocation/', array(
                'methods'  => 'GET',
                'callback' => array($this,'view_booking_appointment_by_location_api_callback'),
                'permission_callback' => '__return_true',
            ));

            register_rest_route('nookal-apis/v1', '/searchpatientbyfirstnamelastnamedob/', array(
                'methods'  => 'POST',
                'callback' => array($this,'search_patient_by_firstName_lastName_dob_api_callback'),
                'permission_callback' => '__return_true',
            ));

            

            
        });
    }

    
    // Define the callback function for the endpoint
    function get_locations_api_callback(WP_REST_Request $request) {
        $practitioner_id = $request->get_param('practitioner_id');

        $practitioners = Nookal_API::gateway()->practitioners();
        $practitioners = $practitioners->children();
        $practitionerDetails = "";
        $practitionerLocations = "";
        $practitionerAvailableLocations = [];

        $locations = Nookal_API::gateway()->locations();
        $locations = $locations->children();
        if($practitioner_id == null && $practitioner_id != 0){
            $practitionerAvailableLocations[0] = [
                "id"=> "0",
                "name"=> "All Locations"
            ];
            foreach($locations as $locationKey => $location){
                $practitionerAvailableLocations[$locationKey+1]['id'] = $location->ID();
                $practitionerAvailableLocations[$locationKey+1]['name'] = $location->name();
            }
        }else{
            if($practitioner_id == 0){
                foreach($locations as $locationKey => $location){
                    $practitionerAvailableLocations[$locationKey]['id'] = $location->ID();
                    $practitionerAvailableLocations[$locationKey]['name'] = $location->name();
                    foreach($practitioners as $practitioner){
                        if(in_array($location->ID(), $practitioner->locations())){
                            $practitionerAvailableLocations[$locationKey]['practitioner'] = $practitioner->ID();
                        }
                    }
                }
            }else{
                foreach($practitioners as $practitioner){
                    if($practitioner->ID() === $practitioner_id){
                        $practitionerDetails = $practitioner;
                        $practitionerLocations = $practitionerDetails->locations();
                    }
                }
                
                
                foreach($locations as $location){
                    if($practitionerLocations){
                        foreach($practitionerLocations as $practitionerLocationKey => $practitionerLocation){
                            if($location->ID() == $practitionerLocation){
                                $practitionerAvailableLocations[$practitionerLocationKey]['id'] = $location->ID();
                                $practitionerAvailableLocations[$practitionerLocationKey]['name'] = $location->name();
                            }
                        }
                    }
                }
            }
        }

        return new WP_REST_Response($practitionerAvailableLocations, 200);
    }

    function get_practitioners_api_callback(WP_REST_Request $request) {
        $location_id = $request->get_param('location_id');

        $locationAvailablepractitioner = [];

        $practitioners = Nookal_API::gateway()->practitioners();
        $practitioners = $practitioners->children();

        if($location_id == null && $location_id != 0 ){
            
            $locationAvailablepractitioner[0] = [
                "id"=> "0",
                "name"=> "All Practitioners"
            ];

            foreach($practitioners as $practitionerKey => $practitioner){
                $locationAvailablepractitioner[$practitionerKey+1]['id'] = $practitioner->ID();
                $locationAvailablepractitioner[$practitionerKey+1]['name'] = $practitioner->firstName()." ".$practitioner->lastName();
                $locationAvailablepractitioner[$practitionerKey+1]['location'] = $practitioner->locations();
            }
        }else{
            if($location_id == 0){
                foreach($practitioners as $practitionerKey => $practitioner){
                    $locationAvailablepractitioner[$practitionerKey]['id'] = $practitioner->ID();
                    $locationAvailablepractitioner[$practitionerKey]['name'] = $practitioner->firstName()." ".$practitioner->lastName();
                    $locationAvailablepractitioner[$practitionerKey]['location'] = $practitioner->locations();
                }
            }else{
                foreach($practitioners as $practitionerKey => $practitioner){
                    if(in_array($location_id, $practitioner->locations())){
                        $locationAvailablepractitioner[$practitionerKey]['id'] = $practitioner->ID();
                        $locationAvailablepractitioner[$practitionerKey]['name'] = $practitioner->firstName()." ".$practitioner->lastName();
                        $locationAvailablepractitioner[$practitionerKey]['location'] = $practitioner->locations();
                    }
                }
            }
        }

        return new WP_REST_Response($locationAvailablepractitioner, 200);
    }

    function get_services_by_location_api_callback(WP_REST_Request $request){

        $location_id = $request->get_param('location_id');

        $servicesList = [];

        $services = Nookal_API::gateway()->appointmentTypes();
        $services = $services->children();

        if($location_id == null){
            $i = 0;
            foreach($services as $serviceKey => $service){
                $servicesList[$i]['id'] = $service->ID();
                $servicesList[$i]['name'] = $service->name();
                $i++;
            }
        }else{
            $i = 0;
            foreach($services as $serviceKey => $service){
                $locations = $service->locations();
                if(in_array($location_id,$locations)){
                    $servicesList[$i]['id'] = $service->ID();
                    $servicesList[$i]['name'] = $service->name();
                    $i++;
                }
            }
        }

        $servicesList = (array) $servicesList;

        return new WP_REST_Response($servicesList, 200);
    }

    function get_classes_by_location_api_callback(WP_REST_Request $request){

        $location_id = $request->get_param('location_id');

        $classesList = [];

        $classes = Nookal_API::gateway()->classTypes();
        $classes = $classes->children();

        if($location_id == null){
            $i = 0;
            foreach($classes as $classKey => $class){
                $classesList[$i]['id'] = $class->ID();
                $classesList[$i]['name'] = $class->name();
                $i++;
            }
        }else{
            $i = 0;
            foreach($classes as $classKey => $class){
                $locations = $class->locations();
                if(in_array($location_id,$locations)){
                    $classesList[$i]['id'] = $class->ID();
                    $classesList[$i]['name'] = $class->name();
                    $i++;
                }
            }
        }

        $classesList = (array) $classesList;

        return new WP_REST_Response($classesList, 200);
    }

    function get_appointment_availabilities_api_callback(WP_REST_Request $request){
        $location_id = $request->get_param('location_id');
        $practitioner_id = $request->get_param('practitioner_id');
        $date_from = $request->get_param('date_from');
        $date_to = $request->get_param('date_to');
        $Availabilities = [];

        $appointmentAvailabilities = Nookal_API::gateway()->appointmentAvailabilities(
            [
                'location_id'       => $location_id,
                'practitioner_id'   => $practitioner_id,
                'date_from'         => $date_from,
                'date_to'           => $date_to
            ]
        );

        
        $date = DateTime::createFromFormat('d-m-Y', $date_from);

        // Check if the DateTime object was created successfully
        if ($date) {
            // Extract the month and year
            $month = $date->format('m'); // '07' for July
            $year = $date->format('Y');  // '2024'
        } else {
            // echo "Invalid date format.";
        }

        $Availabilities['booking_slot'] = $appointmentAvailabilities->availabilities();
        // $Availabilities['booking_slot_month'] = $month;
        // $Availabilities['booking_slot_year'] = $year;



        return new WP_REST_Response($Availabilities, 200);
    }

    function get_class_availabilities_api_callback(WP_REST_Request $request){
        $class_id = $request->get_param('class_id');
        $location_id = $request->get_param('location_id');
        $practitioner_id = $request->get_param('practitioner_id');
        $date_from = $request->get_param('date_from');
        $date_to = $request->get_param('date_to');

        $classAvailabilities = Nookal_API::gateway()->classAvailabilities(
            [
                'class_id'          => $class_id,
                'location_id'       => $location_id,
                'practitioner_id'   => $practitioner_id,
                'date_from'         => $date_from,
                'date_to'           => $date_to
            ]
        );

        $classAvailabilities = $classAvailabilities->availabilities();

        return new WP_REST_Response($classAvailabilities, 200);
    }

    function book_appointment_api_callback(WP_REST_Request $request){

        $firstName = $request->get_param('fname');
        $lastName = $request->get_param('lname');
        $dateOfBirth = $request->get_param('dob');
        $email = $request->get_param('email');
        $phone = $request->get_param('phone');
        
        $location_id = $request->get_param('location_id');
        $practitioner_id = $request->get_param('practitioner_id');
        $service_id = $request->get_param('service_id');
        $booking_date = $request->get_param('booking-date');
        $booking_time = (string) $request->get_param('booking-time');
        $booking_time = $this->convertTimeFormat($booking_time);
        $note = $request->get_param('note');

        if(empty($firstName)){
            return new WP_REST_Response(['message'=>'First Name is empty'], 400);
        }
        if(empty($lastName)){
            return new WP_REST_Response(['message'=>'Last Name is empty'], 400);
        }
        if(empty($dateOfBirth)){
            return new WP_REST_Response(['message'=>'Date of Birth is empty'], 400);
        }
        if(empty($email)){
            return new WP_REST_Response(['message'=>'Email is empty'], 400);
        }

        $response = Nookal_API::gateway()->addPatient(
            [
                'first_name'    => $firstName,
                'last_name'     => $lastName,
                'email'         => $email?$email:'',
                'date_of_birth' => $dateOfBirth,
                'phone'         => $phone?$phone:''
            ]
        );

        
        $patinet_id = 1;

        $bookingStatus = Nookal_API::gateway()->addBooking(
            [
                'location_id'           => $location_id,
                'appointment_date'      => $booking_date,
                'start_time'            => $booking_time,
                'patient_id'            => $patinet_id,
                'practitioner_id'       => $practitioner_id,
                'appointment_type_id'   => $service_id,
                'notes'                 => $note,
            ]
        );

        // [$location_id, $practitioner_id, $service_id, $booking_date, $booking_time, k]

        // $bookingStatus = $bookingStatus->appointmentID();

        return new WP_REST_Response($bookingStatus, 200);
    }

    function convertTimeFormat($timeString) {
        // Check if the time string already has seconds
        if (strpos($timeString, ':') === false) {
            return $timeString . ':00'; // Append :00 if no colon is present
        }
        
        // Split the time string into hours and minutes
        list($hours, $minutes) = explode(':', $timeString);
        
        // Append seconds
        return sprintf('%02d:%02d:00', $hours, $minutes);
    }

    
    function view_booking_appointment_by_location_api_callback(WP_REST_Request $request){
        $location_id = $request->get_param('location_id');

        $response = Nookal_API::gateway()->appointments(
            [
                'location_id'           => $location_id,
            ]
        );

        $response = $response->children();


        return new WP_REST_Response($response, 200);
    }

}