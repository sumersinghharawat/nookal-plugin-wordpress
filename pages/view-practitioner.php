<?php

class PractitionerClass{
    function __construct()
    {
        
    }

    function ViewPractitionerLayout(){
        // Render the settings page
    
        $practitioners = Nookal_API::gateway()->practitioners();

        $practitioners = $practitioners->children();
    
        ?>
        <div class="wrap">
            <h1>List of Practitioners</h1>
            <!-- Wrap the table in a responsive container -->
            <div class="table-responsive">
                <table id="table-pagination">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First name</th>
                            <th>Last name</th>
                            <th>speciality</th>
                            <th>Title</th>
                            <th>email</th>
                            <th>Location</th>
                            <th>Country</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($practitioners as $practitioner){
                            $practitioner_locations_id = $practitioner->locations();
                            $practitioner_location = '';
                            $locations = Nookal_API::gateway()->locations();
                            $locations = $locations->children();


                            foreach($locations as $location){

                                // print_r($practitioner_locations_id[0]);
                                // print_r($location->ID());

                                if($location->ID() == $practitioner_locations_id[0]){
                                    $practitioner_location = $location;
                                    // print_r($practitioner_location);
                                }
                            }

                        ?>
                        <tr>
                            <td><?php echo $practitioner->ID();?></td>
                            <td><?php echo $practitioner->firstName();?></td>
                            <td><?php echo $practitioner->lastName();?></td>
                            <td><?php echo $practitioner->speciality();?></td>
                            <td><?php echo $practitioner->title()?$practitioner->title():"Not Available";?></td>
                            <td><?php echo $practitioner->email();?></td>
                            <td>
                                <?php 
                                echo $practitioner_location->name()?$practitioner_location->name():"";
                        ?>
                            </td>
                            <td>
                                <?php
                                    $address = $practitioner_location->address();
                                    echo $address->country();
                                ?>
                            </td>
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

}