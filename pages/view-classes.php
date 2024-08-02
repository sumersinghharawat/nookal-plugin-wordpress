<?php

class ClassesClass{
    function __construct()
    {
        
    }

    function ViewClassesLayout(){
        // Render the settings page
        $classes = Nookal_API::gateway()->classTypes();

        $classes = $classes->children();
    
        ?>
        <div class="wrap">
            <h1>List of Classes</h1>
            <!-- Wrap the table in a responsive container -->
            <div class="table-responsive">
                <table id="table-pagination">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name of Class</th>
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
                        foreach($classes as $class){

                            $class_locations_ids = $class->locations();
                            unset($class_locations_ids[0]);

                        ?>
                        <tr>
                            <td><?php echo $class->ID();?></td>
                            <td><?php echo $class->name();?></td>
                            <td><?php echo $class->description();?></td>
                            <td><?php echo $class->duration();?></td>
                            <td><?php echo $class->price();?></td>
                            <td><?php echo $class->hasTax()?"with tax":"without tax";?></td>
                            <td><?php echo $class->type();?></td>
                            <td>
                                <ul>
                                    <?php 
                                        $locations = $this->getLocationList($class_locations_ids);
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

    function getLocationList($class_locations_ids){
        $class_locations = [];
        $locations = Nookal_API::gateway()->locations();
        $locations = $locations->children();

        foreach($class_locations_ids as $class_locations_id){
            foreach($locations as $location){
                if($location->ID() == $class_locations_id){
                    $class_location = $location;
                    $class_locations[] = $class_location->name()?$class_location->name():"";
                }
            }
        }

        return $class_locations;
    }
}