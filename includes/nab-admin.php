<?php







// Location
function nab_render_location_page(){

}


function nab_render_booking_view_page(){
    
    if(isset($_GET['locationid'])){
        $appointments = Nookal_API::gateway()->appointments(
            [
                'location_id' => $_GET['locationid'],
            ]
        );
    }else{
        $appointments = Nookal_API::gateway()->appointments();
    }

    $appointments = $appointments->children();
    // print_r($appointments);
    ?>
    <div class="wrap">
        <h1>List of Locations</h1>
        <!-- Wrap the table in a responsive container -->
        <div class="table-responsive">
            <table id="appointments">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Location</th>
                        <th>Patient Name</th>
                        <th>Practitioner</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>Sttatus</th>
                        <th>View Appointment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach($appointments as $appointment){
                    
                        
                        // Get appointment details
                        $patientdetails = Nookal_API::gateway()->patientSearch(
                            [
                                'patient_id' => $appointment->patientID()
                            ]
                        );
                        
                        $patientdetails = $patientdetails->children()[0];
                        
                        // Location
                        $data = Nookal_API::gateway()->locations([
                            "location_id" => $appointment->locationID()
                        ]) ;
                        $location = $data->children();
                        $addressDetails = "";
                        $addressDetails .= $location[0]->name().", ";
                        $address = $location[0]->address();
                        $addressDetails .= $address->addressLine1()?$address->addressLine1():""; 
                        $addressDetails .= $address->addressLine2()?$address->addressLine2():""; 
                        $addressDetails .= $address->addressLine3()?$address->addressLine3():""; 
                        $addressDetails .= $address->city()?", ".$address->city():""; 
                        $addressDetails .= $address->state()?", ".$address->state():""; 
                        $addressDetails .= $address->country()?", ".$address->country():"";
                        
                        $data = Nookal_API::gateway()->practitioners([
                            "practitioner_id" => $appointment->practitionerID()
                        ]) ;
                        
                        $practitioners = $data->children();
                        $practitionerdetails = null;
                        foreach($practitioners as $practitioner){
                            if($practitioner->ID() === $appointment->practitionerID()){
                                $practitionerdetails = $practitioner;
                            }
                        }

                        // Type Details
                        $classDetails = "";
                        $response = Nookal_API::gateway()->classTypes();
                        $classes = $response->children();

                        foreach($classes as $class){
                            if($class->ID() == $appointment->typeID()){
                                $classDetails = $class;
                            }
                        }

                
                    ?>
                    <tr>
                        <td><?php echo $appointment->ID();?></td>
                        <td><?php echo $appointment->date();?></td>
                        <td><?php echo date('h:i A', strtotime($appointment->startTime()))."-".date('h:i A', strtotime($appointment->endTime()));?></td>
                        <td><?php echo $addressDetails;?></td>
                        <td>
                            <?php    
                                echo $patientdetails->firstName()." ".$patientdetails->middleName()." ".$patientdetails->lastName();
                            ?>
                        </td>
                        <td><?php echo $practitionerdetails->firstName()." ".$practitionerdetails->lastName();?></td>
                        <td><?php print_r($classDetails->type());?></td>
                        <td><?php echo $appointment->emailReminderSent();?></td>
                        <td><?php echo $appointment->arrived();?></td>
                        <td><?php echo $appointment->DNA();?></td>
                        <td><?php echo $appointment->cancelled();?></td>
                        <td><?php echo $appointment->invoiceGenerated();?></td>
                        <td><?php echo $appointment->cancellationDate();?></td>
                        <td><?php echo $appointment->notes();?></td>
                        <td><?php echo $appointment->dateModified();?></td>
                        <td><?php echo $appointment->dateCreated();?></td>
                        
                        <td><a href="?page=nab-booking-view&locationid=<?php //echo $location->ID();?>">View Booking</a></td>
                    </tr>
                    <?php
                    }
                    ?>
                    <!-- Add more rows as needed -->
                </tbody>
            </table>
            <div id="pagination-container"></div>
        </div>

    </div>
    <?php
}


function nab_render_patient_page(){
    echo "adsf";
}