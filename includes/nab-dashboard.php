<?php

// Shortcode for displaying the user dashboard
function nab_user_dashboard_shortcode() {
    ob_start();
    ?>
    <div id="nab-user-dashboard">
        <h2>Your Appointments</h2>
        <table>
            <tr>
                <th>Service</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
            </tr>
            <?php
            // Fetch user appointments from Nookal API (pseudo-code)
            $appointments = nookal_get_user_appointments(get_current_user_id());

            if (!empty($appointments)) {
                foreach ($appointments as $appointment) {
                    echo '<tr>';
                    echo '<td>' . esc_html($appointment['service']) . '</td>';
                    echo '<td>' . esc_html($appointment['date']) . '</td>';
                    echo '<td>' . esc_html($appointment['time']) . '</td>';
                    echo '<td>' . esc_html($appointment['status']) . '</td>';
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="4">No appointments found.</td></tr>';
            }
            ?>
        </table>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('nab_user_dashboard', 'nab_user_dashboard_shortcode');
