<?php

// Function to book an appointment using Nookal API (pseudo-code)
function nookal_book_appointment($service, $date, $time, $name, $email) {
    $api_key = get_option('nab_nookal_api_key');
    $endpoint = 'https://api.nookal.com/v1/appointments';

    $response = wp_remote_post($endpoint, array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type' => 'application/json'
        ),
        'body' => json_encode(array(
            'service' => $service,
            'date' => $date,
            'time' => $time,
            'name' => $name,
            'email' => $email
        ))
    ));

    if (is_wp_error($response)) {
        return array('success' => false, 'message' => $response->get_error_message());
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (isset($data['success']) && $data['success']) {
        return array('success' => true);
    } else {
        return array('success' => false, 'message' => $data['message']);
    }
}

// Function to get user appointments using Nookal API (pseudo-code)
function nookal_get_user_appointments($user_id) {
    $api_key = get_option('nab_nookal_api_key');
    $endpoint = 'https://api.nookal.com/v1/users/' . $user_id . '/appointments';

    $response = wp_remote_get($endpoint, array(
        'headers' => array(
            'Authorization' => 'Bearer ' . $api_key,
            'Content-Type' => 'application/json'
        )
    ));

    if (is_wp_error($response)) {
        return array();
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    return isset($data['appointments']) ? $data['appointments'] : array();
}
