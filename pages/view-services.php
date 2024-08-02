<?php

class ServicesClass{
    function __construct()
    {
        
    }

    function ViewServicesLayout(){
        // Render the settings page
        $services = Nookal_API::gateway()->appointmentTypes();

        $services = $services->children();
    
        ?>
        <div class="wrap">
            <h1>List of Services</h1>
            <!-- Wrap the table in a responsive container -->
            <div class="table-responsive">
                <table id="table-pagination">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name of Service</th>
                            <th>Description</th>
                            <th>Duration</th>
                            <th>Price</th>
                            <th>Tax Status</th>
                            <th>Type</th>
                            <th>Locations</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($services as $service){
                            
                            $service_locations_ids = $service->locations();
                            unset($service_locations_ids[0]);

                        ?>
                        <tr>
                            <td><?php echo $service->ID();?></td>
                            <td><?php echo $service->name();?></td>
                            <td><?php echo $service->description();?></td>
                            <td><?php echo $service->duration();?></td>
                            <td><?php echo $service->price();?></td>
                            <td><?php echo $service->hasTax()?"with tax":"without tax";?></td>
                            <td><?php echo $service->type();?></td>
                            <td>
                                <ul>
                                    <?php 
                                        $locations = $this->getLocationList($service_locations_ids);
                                        foreach($locations as $location){
                                    ?>
                                        <li><?php echo $location; ?></li>
                                    <?php 
                                        }
                                    ?>
                                </ul>
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

    function getLocationList($service_locations_ids){
        $service_locations = [];
        $locations = Nookal_API::gateway()->locations();
        $locations = $locations->children();

        foreach($service_locations_ids as $service_locations_id){
            foreach($locations as $location){
                if($location->ID() == $service_locations_id){
                    $service_location = $location;
                    $service_locations[] = $service_location->name()?$service_location->name():"";
                }
            }
        }

        return $service_locations;
    }
}