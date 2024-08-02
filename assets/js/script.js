// Custom JavaScript for the plugin
jQuery(document).ready(function($) {

    // Handle form submission or further processing as needed

    var i = $(".step-from-panel.active").attr("data-step");

    $('#turnBack').click(()=>{
        var i = $(".step-from-panel.active").hide();
        $('#step-'+i).show();
    });

    $('#submitForm').click(()=>{
        
        let selection = $('input[name="selection"]:checked').val();
        let location_id = $("#location").val();
        let practitioner_id = $("#practitioner").val();
        
        console.log("selection", selection);
        console.log("location_id", location_id);
        console.log("practitioner_id", practitioner_id);
    });

    // Handle tab switching
    $('#tab-appointment').click(function() {
        $('#appointment-content').show();
        $('#class-content').hide();
    });

    $('#tab-class').click(function() {
        $('#appointment-content').hide();
        $('#class-content').show();
    });

});


function submitBookingForm(){
    let fname = document.querySelector("#fname").value;
    if(fname == ""){
        alert("Please enter first name");
    }

    let lname = document.querySelector("#lname").value;
    if(lname == ""){
        alert("Please enter last name");
    }
    
    let dob = document.querySelector("#dob").value;
    if(dob == ""){
        alert("Please enter Date of Birth");
    }
    
    let email = document.querySelector("#email").value;
    if(fname == ""){
        alert("Please enter email");
    }
    
    let phone = document.querySelector("#phone").value;
    if(fname == ""){
        alert("Please enter phone");
    }
    let note = document.querySelector("#note").value;

    let location_id = localStorage.getItem("location_id");
    let practitioner_id = localStorage.getItem("practitioner_id");
    let service_id = localStorage.getItem("service_id");
    let booking_date = localStorage.getItem("booking-date");
    let booking_time = localStorage.getItem("booking-time");

    jQuery.ajax( {
        method: "POST",
        url: baseURL+"bookappointment",
        data: { 
            fname: fname, 
            lname: lname, 
            dob: dob, 
            phone: phone?phone:'', 
            email: email?email:'' , 
            location_id: location_id,
            practitioner_id: practitioner_id,
            service_id: service_id,
            booking_date: booking_date,
            booking_time: booking_time,
            note: note,
        }
    })
    .done(function(res) {
        console.log(res);

        document.querySelector(".booking-patient-details").style.display = "none";

        
        jQuery(".loader").hide();
    })
    .fail(function() {
        console.log( "error" );
    });   
}

function CheckSelection(selection){
    if(selection !== ""){
        document.querySelector(".selection-form").style.display = "none";
    }

    if(selection === 'location'){
        viewLocationsList();
        document.querySelector("#location-selection").style.display = "block";
    }
    
    if(selection === 'practitioner'){
        viewPractitionersList();
        document.querySelector("#practitioner-selection").style.display = "block";
    }
}

// Handle selection between Location and Practitioner


// $('input[name="selection"]').change(function(e) {
//     $('input[name="selection"]').removeClass("active");
//     $(e.target).addClass("active");
//     let selection = e.target.value;
//     CheckSelection(selection);
// });

// jQuery('input[name="location"]').change(function(e) {
//     jQuery('input[name="location"]').removeClass("active");
//     jQuery(e.target).addClass("active");
//     let location = e.target.value;
//     CheckLocation(location);
// });

// // Handle selection of Practitioner
// jQuery('input[name="practitioner"]').change(function(e) {
//     jQuery('input[name="practitioner"]').removeClass("active");
//     jQuery(e.target).addClass("active");
//     let practitioner = e.target.value;
//     CheckPractitioner(practitioner);
// });

// APIs Calling for collecting data
var baseURL = "http://localhost/wordpress/wp-json/nookal-apis/v1/"
localStorage.removeItem("location_id");
localStorage.removeItem("practitioner_id");
localStorage.removeItem("service_id");
localStorage.removeItem("class_id");
localStorage.removeItem("booking-time");
localStorage.removeItem("booking-date");

jQuery(".loader").hide();

function viewLocationsList(){

    jQuery(".loader").show();
    
    let practitioner_id = localStorage.getItem('practitioner_id');

    jQuery.ajax( {
        method: "POST",
        url: baseURL+"getlocations",
        data: { practitioner_id: practitioner_id }
    })
    .done(function(res) {
        console.log(res);
        
        // Show list of locations
        let listOfData = "";

        res.forEach(element => {
            listOfData += '<label class="nab-button"><input type="radio" class="input-step-2" name="location" value="'+element.id+'" onchange="SelectLocation(this.value,'+element.practitioner+')">'+element.name+'</label>';
        });

        jQuery("#locations-list").html(listOfData);
        jQuery(".loader").hide();
    })
    .fail(function() {
        console.log( "error" );
    });

}

function viewPractitionersList(){

    jQuery(".loader").show();

    let location_id = localStorage.getItem('location_id');
    
    jQuery.ajax( {
        method: "POST",
        url: baseURL+"getpractitioners",
        data: { location_id: location_id }
    })
    .done(function(res) {
        console.log(res);
        
        // Show list of locations
        let listOfData = "";

        res.forEach(element => {
            listOfData += '<label class="nab-button"><input type="radio" class="input-step-2" name="practitioner" value="'+element.id+'" onchange="SelectPractitioner(this.value,'+element.location+')">'+element.name+'</label>';
        });

        jQuery("#practitioners-list").html(listOfData);
        jQuery(".loader").hide();
    })
    .fail(function() {
        console.log( "error" );
    });

}



function viewAppointmentList(){

    jQuery(".loader").show();

    let location_id = localStorage.getItem('location_id');
    
    jQuery.ajax( {
        method: "POST",
        url: baseURL+"getservicesbylocations",
        data: { location_id: location_id }
    })
    .done(function(res) {
        console.log(res);
        
        // Show list of locations
        let listOfData = "";

        if(res.length){
            res.forEach(element => {
                listOfData += '<label class="nab-button"><input type="radio" class="input-step-2" name="service" value="'+element.id+'" onchange="SelectService(this.value)">'+element.name+'</label>';
            });
        }else{
            listOfData = "Service not available";
        }
        jQuery("#service-list").html(listOfData);
        jQuery(".loader").hide();
    })
    .fail(function() {
        console.log( "error" );
    });

}

function viewClassesList(){

    jQuery(".loader").show();

    let location_id = localStorage.getItem('location_id');
    
    jQuery.ajax( {
        method: "POST",
        url: baseURL+"getclassesbylocations",
        data: { location_id: location_id }
    })
    .done(function(res) {
        console.log(res);
        
        // Show list of locations
        let listOfData = "";

        if(res.length){
            res.forEach(element => {
                listOfData += '<label class="nab-button"><input type="radio" class="input-step-2" name="class" value="'+element.id+'" onchange="SelectClass(this.value)">'+element.name+'</label>';
            });
        }else{
            listOfData = "Class not available";
        }
        jQuery("#class-list").html(listOfData);
        jQuery(".loader").hide();
    })
    .fail(function() {
        console.log( "error" );
    });

}


function SelectLocation(value, location_practitioner_id){
    localStorage.setItem('location_id', value);
    let practitioner_id = localStorage.getItem('practitioner_id');
    if(practitioner_id == null){
        if(LocationIDPractitionerId()){
            document.querySelector("#location-selection").style.display = "none";
            document.querySelector("#practitioner-selection").style.display = "none";
        }else{
            console.log("no practitioner_id");
            document.querySelector("#location-selection").style.display = "none";
            document.querySelector("#practitioner-selection").style.display = "block";
            viewPractitionersList();
        }
    }else{
        if(practitioner_id == 0){
            localStorage.setItem('practitioner_id', location_practitioner_id);
        }
        let location_id = localStorage.getItem('location_id');
        if(location_id != null){
            viewClassAppointment();
        }
    }
}

function SelectPractitioner(value, practitioner_location_id){
    localStorage.setItem('practitioner_id', value);
    let location_id = localStorage.getItem('location_id');
    
    if(location_id == null){
        if(LocationIDPractitionerId()){
            document.querySelector("#location-selection").style.display = "none";
            document.querySelector("#practitioner-selection").style.display = "none";
        }else{
            console.log("no location_id");
            document.querySelector("#practitioner-selection").style.display = "none";
            document.querySelector("#location-selection").style.display = "block";
            viewLocationsList();
        }
    }else{
        if(location_id == 0){
            localStorage.setItem('location_id', practitioner_location_id);
        }

        let practitioner_id = localStorage.getItem('practitioner_id');
        if(practitioner_id != null){
            viewClassAppointment();
        }
    }
}

function SelectService(value){
    localStorage.setItem('service_id', value);
    viewServiceBookingSlot();
}

function SelectClass(value){
    localStorage.setItem('class_id', value);
    let location_id = localStorage.getItem('location_id');
    if(location_id == null){

    }else{
        viewClassBookingSlot();
    }
}

function LocationIDPractitionerId(){
    let location_id = localStorage.getItem('location_id');
    let practitioner_id = localStorage.getItem('practitioner_id');
    if(location_id == null && practitioner_id == null){
        return true;
    }else{
        return false;
    }
}

function viewClassAppointment(){
    document.querySelector("#practitioner-selection").style.display = "none";
    document.querySelector("#location-selection").style.display = "none";
    document.querySelector("#classes-services").style.display = "block";

    viewAppointmentList();
    viewClassesList();
}

function viewServiceBookingSlot(){
    // Get the first day of the current month
    let now = new Date();
    let startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1);
    let endOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0);
    

    let yearDate_from = startOfMonth.getFullYear();
    let monthDate_from = (startOfMonth.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-based
    let dayDate_from = startOfMonth.getDate().toString().padStart(2, '0');
    let date_from = `${yearDate_from}-${monthDate_from}-${dayDate_from}`;

    let yearDate_to = endOfMonth.getFullYear();
    let monthDate_to = (endOfMonth.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-based
    let dayDate_to = endOfMonth.getDate().toString().padStart(2, '0');
    let date_to = `${yearDate_to}-${monthDate_to}-${dayDate_to}`;
    

    ViewCalenderLoad(date_from, addOneDay(date_to), startOfMonth, endOfMonth);
    initializeDropdowns();

}

function initializeDropdowns() {
    let months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    let currentYear = new Date().getFullYear();
    let years = [];

    for (let i = currentYear - 10; i <= currentYear + 10; i++) {
        years.push(i);
    }

    let monthDropdown = jQuery('#month-dropdown');
    months.forEach((month, index) => {
        monthDropdown.append(new Option(month, index));
    });

    let yearDropdown = jQuery('#year-dropdown');
    years.forEach(year => {
        yearDropdown.append(new Option(year, year));
    });

    // Set current month and year
    monthDropdown.val(new Date().getMonth());
    yearDropdown.val(currentYear);
}


function loadCalendar() {
    let month = jQuery('#month-dropdown').val();
    let year = jQuery('#year-dropdown').val();

    let startDate = new Date(year, month, 1);
    let endDate = new Date(year, parseInt(month) + 1, 1);

    let yearDate_from = startDate.getFullYear();
    let monthDate_from = (startDate.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-based
    let dayDate_from = startDate.getDate().toString().padStart(2, '0');
    let date_from = `${yearDate_from}-${monthDate_from}-${dayDate_from}`;

    let yearDate_to = endDate.getFullYear();
    let monthDate_to = (endDate.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-based
    let dayDate_to = endDate.getDate().toString().padStart(2, '0');
    let date_to = `${yearDate_to}-${monthDate_to}-${dayDate_to}`;

    ViewCalenderLoad(date_from, date_to, startDate, endDate)
}


function ViewCalenderLoad(date_from, date_to, startOfMonth, endOfMonth){

    jQuery(".loader").show();

    let location_id = localStorage.getItem("location_id");
    let practitioner_id = localStorage.getItem("practitioner_id");

    jQuery.ajax( {
        method: "POST",
        url: baseURL+"getappointmentavailable",
        data: { location_id: location_id, practitioner_id: practitioner_id, date_from: date_from, date_to: date_to }
    })
    .done(function(res) {
        console.log(res);

        generateCalendar(startOfMonth, endOfMonth, res.booking_slot);
        
        // Event listeners for dropdown changes
        jQuery('#month-dropdown').change(loadCalendar);
        jQuery('#year-dropdown').change(loadCalendar);
        
        document.querySelector("#classes-services").style.display = "none";    
        document.querySelector(".booking-slots").style.display = "block";

        jQuery(".loader").hide();
    })
    .fail(function() {
        console.log( "error" );
    });
}

function addOneDay(dateString) {
    // Convert string to Date object
    let date = new Date(dateString);
    
    // Add one day
    date.setDate(date.getDate() + 1);
    
    // Format the new date back to YYYY-MM-DD
    let year = date.getFullYear();
    let month = (date.getMonth() + 1).toString().padStart(2, '0');
    let day = date.getDate().toString().padStart(2, '0');
    
    return `${year}-${month}-${day}`;
}


function generateCalendar(startDate, endDate, bookingSlots) {
    let calendarDiv = jQuery("#calendar");
    calendarDiv.empty();
    
    let daysOfWeek = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat','Sun'];
    let currentDate = new Date(startDate);

    // Create headers for days of the week
    daysOfWeek.forEach(day => {
        calendarDiv.append('<div class="day-header">' + day + '</div>');
    });

    // Calculate the starting day of the week
    let startDay = new Date(startDate);
    startDay.setDate(0); // Set to the first day of the month
    let firstDayOfWeek = startDay.getDay(); // 0 for Sunday, 1 for Monday, etc.

    // Add empty days for the initial alignment
    for (let i = 0; i < firstDayOfWeek; i++) {
        calendarDiv.append('<div class="day empty"></div>');
    }

    // Create calendar days

    while (currentDate <= endDate) {
        let year = currentDate.getFullYear();
        let month = (currentDate.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-based
        let day = currentDate.getDate().toString().padStart(2, '0');
        let dateString = `${year}-${month}-${day}`;

        let dayDiv = jQuery('<div class="day"></div>').text(currentDate.getDate());
        let haveAvailable = 0;

        for (const date in bookingSlots) {
            if (dateString == date) {
                const slots = bookingSlots[date];
                for (const slot in slots) {
                    if (slots[slot] === "available") {
                        haveAvailable++;
                    }
                }
            }
        }

        if (haveAvailable === 0) {
            dayDiv.addClass('no-availability');
        } else {
            dayDiv.addClass('availability');
        }

        // Add click event to show available slots
        dayDiv.click(function() {
            showAvailableSlots(dateString, bookingSlots[dateString] || {});
        });
        
        calendarDiv.append(dayDiv);
        currentDate.setDate(currentDate.getDate() + 1);
    }
}


function showAvailableSlots(date, slots) {
    // console.log(date, slots);
    
    let listOfData = `<h3>Available Slots for ${date}</h3><div class="available-list">`;

    for (let time in slots) {
        if (slots[time] === "available") {
            listOfData += `<div class="available-slot" data-date="${date}" data-time="${time}" onclick="SaveSlot(this)">${time}</div>`;
        }
    }

    if (listOfData === `<h3>Available Slots for ${date}</h3><div class="available-list">`) {
        listOfData = `<h3>No Available Slots for ${date}</h3><div class="available-list">`;
    }

    listOfData += '</div>'; 

    jQuery("#booking-slots").html(listOfData);
}

function viewClassBookingSlot(){

}

function SaveSlot(element){
    var attribsDate = jQuery(element).attr('data-date');
    var attribsTime = jQuery(element).attr('data-time');

    localStorage.setItem('booking-date',attribsDate);
    localStorage.setItem('booking-time',attribsTime);

    document.querySelector(".booking-slots").style.display = "none";
    ViewPatientDetails();
}

function ViewPatientDetails(){
    
    document.querySelector(".booking-patient-details").style.display = "flex";
}
