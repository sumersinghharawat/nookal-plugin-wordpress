<?php

class LocationClass{
    function __construct()
    {
        
    }

    function ViewLocationLayout(){
        // Render the settings page
    
        $locations = Nookal_API::gateway()->locations();

        $locations = $locations->children();
    
        ?>
        <div class="wrap">
            <h1>List of Locations</h1>
            <!-- Wrap the table in a responsive container -->
            <div class="table-responsive">
                <table id="table-pagination">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Company</th>
                            <th>Address</th>
                            <th>View Booking</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($locations as $location){
                            
                        ?>
                        <tr>
                            <td><?php echo $location->ID();?></td>
                            <td><?php echo $location->name();?></td>
                            <td><?php 
                                    $address = $location->address();
                                    echo $address->addressLine1()?$address->addressLine1():""; 
                                    echo $address->addressLine2()?$address->addressLine2():""; 
                                    echo $address->addressLine3()?$address->addressLine3():""; 
                                    echo $address->city()?", ".$address->city():""; 
                                    echo $address->state()?", ".$address->state():""; 
                                    echo $address->country()?", ".$address->country():"";
                                ?></td>
                            <td><a href="?page=nab-booking-view&locationid=<?php echo $location->ID();?>">View Booking</a></td>
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