<?php

// Shortcode for displaying the booking form
function nab_booking_form_shortcode() {
    ob_start();
    ?>
    <form id="nab-booking-form" method="post" action="">
        <label for="nab-service">Service</label>
        <select id="nab-service" name="service">
            <option value="service1">Service 1</option>
            <option value="service2">Service 2</option>
        </select>
        <label for="nab-date">Date</label>
        <input type="date" id="nab-date" name="date" required>
        <label for="nab-time">Time</label>
        <input type="time" id="nab-time" name="time" required>
        <label for="nab-name">Name</label>
        <input type="text" id="nab-name" name="name" required>
        <label for="nab-email">Email</label>
        <input type="email" id="nab-email" name="email" required>
        <button type="submit">Book Appointment</button>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('nab_booking_form', 'nab_booking_form_shortcode');

// Handle form submission
function nab_handle_booking_form() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get form data
        $service = sanitize_text_field($_POST['service']);
        $date = sanitize_text_field($_POST['date']);
        $time = sanitize_text_field($_POST['time']);
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);

        // Call Nookal API to book appointment (pseudo-code)
        $response = nookal_book_appointment($service, $date, $time, $name, $email);

        if ($response['success']) {
            echo '<p>Appointment booked successfully!</p>';
        } else {
            echo '<p>Failed to book appointment. Please try again.</p>';
        }
    }
}
add_action('wp_head', 'nab_handle_booking_form');
