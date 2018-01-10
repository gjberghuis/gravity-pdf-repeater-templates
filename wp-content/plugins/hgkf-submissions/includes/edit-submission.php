<?php

function render_edit_submission_page() {
    global $wpdb;

    if (isset($_GET['action']) && $_GET['action'] == 'edit_submission' && isset($_GET['id'])) {
        $submission = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}submissions WHERE id = " . $_GET['id']);

        global $wpdb;

        /*
         * FIELDS
         * word1_submissions:
         * id (readonly), submission_type (readonly), submission_date (readonly), active, organization, parking_tickets, notes
         *
         * word1_submission_invoices
         * submission_id (readonly), number (readonly), firstname, lastname, adress, zipcode, city, email, extra_information
         *
         */
        $sql = "SELECT submission.id, invoice.submission_id, invoice.number, submission.active, submission.submission_type, submission.submission_date, 
submission.organization, invoice.firstname, invoice.lastname, invoice.adress, invoice.zipcode, invoice.city, invoice.email, invoice.extra_information, submission.parking_tickets, submission.reduction_code, submission.notes FROM {$wpdb->prefix}submissions AS submission INNER JOIN {$wpdb->prefix}submission_invoices AS invoice ON invoice.submission_id = submission.submission_id WHERE id = " . $_GET['id'];

        $submission = $wpdb->get_results($sql, 'ARRAY_A');

        if (count($submission ) > 0) {
            if (isset($_POST['save_submission'])) {
                if (!empty($_POST['organization'])) {
                    $submission[0]["organization"] = $_POST['organization'];
                }

                if (!empty($_POST['parking_tickets'])) {
                    $submission[0]["parking_tickets"] = $_POST['parking_tickets'];
                }

                if (!empty($_POST['notes'])) {
                    $submission[0]["notes"] = $_POST['notes'];
                }

                if (!empty($_POST['firstname'])) {
                    $submission[0]["firstname"] = $_POST['firstname'];
                }

                if (!empty($_POST['lastname'])) {
                    $submission[0]["lastname"] = $_POST['lastname'];
                }

                if (!empty($_POST['adress'])) {
                    $submission[0]["adress"] = $_POST['adress'];
                }

                if (!empty($_POST['zipcode'])) {
                    $submission[0]["zipcode"] = $_POST['zipcode'];
                }

                if (!empty($_POST['city'])) {
                    $submission[0]["city"] = $_POST['city'];
                }

                if (!empty($_POST['email'])) {
                    $submission[0]["email"] = $_POST['email'];
                }

                if (!empty($_POST['extra_information'])) {
                    $submission[0]["extra_information"] = $_POST['extra_information'];
                }

                $resultSubmission = $wpdb->update($wpdb->prefix . 'submissions',
                    array('organization' => $submission[0]["organization"],
                        'parking_tickets' => $submission[0]["parking_tickets"],
                        'notes' => $submission[0]["notes"]),
                    array('id' => $submission[0]["id"]));

                $result = $wpdb->update($wpdb->prefix .'submission_invoices',
                    array('firstname' => $submission[0]["firstname"],
                        'lastname' => $submission[0]["lastname"],
                        'adress' => $submission[0]["adress"],
                        'zipcode' => $submission[0]["zipcode"],
                        'city' => $submission[0]["city"],
                        'email' => $submission[0]["email"],
                        'extra_information' => $submission[0]["extra_information"]),
                    array('submission_id' => $submission[0]["submission_id"]));

                if ($resultSubmission > 0 || $result) {
                    $path = 'admin.php?page=my_submissions_overview';
                    $url = admin_url($path);
                    wp_redirect($url);
                }
            }

            echo '</pre>';
            echo '<div class="wrap">';

            $path = 'admin.php?page=my_submissions_overview';
            $url = admin_url($path);
            echo '<a href="' . $url . '">< Terug naar het overzicht</a>';
            echo '<h2>Aanmelding bewerken</h2>';
            echo '<br/>';
            echo '<form action="" method="post">';
            echo '<fieldset><legend>Niet te wijzigen:</legend>';
            echo '<table>';
                echo '<tr style="display: none;">';
                    echo '<td>';
                    echo '<label for="id" style="margin-right: 20px;">Id</label>';
                    echo '</td>';
                    echo '<td>';
                    echo '<input type="int" name="id" disabled value="' . $submission[0]["id"] . '"/>';
                    echo '</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td>';
                    echo '<label for="submission_id" style="margin-right: 20px;">Submission id</label>';
                    echo '</td>';
                    echo '<td>';
                    echo '<input type="int" name="submission_id" disabled value="' . $submission[0]["submission_id"] . '"/>';
                    echo '</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td>';
                    echo '<label for="invoice_number" style="margin-right: 20px;">Factuur nummer</label>';
                    echo '</td>';
                    echo '<td>';
                    echo '<input type="text" name="invoice_number" disabled value="' . $submission[0]["number"] . '"/>';
                    echo '</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td>';
                    echo '<label for="submission_type" style="margin-right: 20px;">Aanmeld type</label>';
                    echo '</td>';
                    echo '<td>';
                    echo '<input type="text" name="submission_type" disabled value="' . $submission[0]["submission_type"] . '"/>';
                    echo '</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td>';
                    echo '<label for="submission_date" style="margin-right: 20px;">Aanmeld datum</label>';
                    echo '</td>';
                    echo '<td>';
                    echo '<input type="date" name="submission_date" disabled value="' . $submission[0]["submission_date"] . '"/>';
                    echo '</td>';
                echo '</tr>';
                echo '<tr><td><br/></td></tr></table>';
            echo '</fieldset>';
            echo '<fieldset><legend>Aanmeld gegevens:</legend><table></tr>';
                echo '<tr>';
                    echo '<td>';
                    echo '<label for="organization" style="margin-right: 20px;">Organisatie</label>';
                    echo '</td>';
                    echo '<td>';
                    echo '<input type="text" style="width:300px;" name="organization" value="' . $submission[0]["organization"] . '"/>';
                    echo '</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td>';
                    echo '<label for="parking_tickets" style="margin-right: 20px;">Parkeertickets</label>';
                    echo '</td>';
                    echo '<td>';
                    echo '<input type="int" name="parking_tickets" value="' . $submission[0]["parking_tickets"] . '"/>';
                    echo '</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td>';
                    echo '<label for="notes" style="margin-right: 20px;">Opmerkingen</label>';
                    echo '</td>';
                    echo '<td>';
                    echo '<textarea type="text" style="width:300px;" name="notes">' .  $submission[0]["notes"] . '</textarea>';
                    echo '</td>';
                echo '</tr>';
                echo '<tr><td><br/></td></tr></table>';
            echo '</fieldset>';
            echo '<fieldset><legend>Factuur gegevens (let op hiermee wijzig je ook de gegevens op de factuur):</legend><table>';
                echo '<tr>';
                    echo '<td>';
                    echo '<label for="firstname" style="margin-right: 20px;">Voornaam</label>';
                    echo '</td>';
                    echo '<td>';
                    echo '<input type="text" style="width:300px;" name="firstname" value="' . $submission[0]["firstname"] . '"/>';
                    echo '</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td>';
                    echo '<label for="lastname" style="margin-right: 20px;">Achternaam</label>';
                    echo '</td>';
                    echo '<td>';
                    echo '<input type="text" style="width:300px;"  name="lastname" value="' . $submission[0]["lastname"] . '"/>';
                    echo '</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td>';
                    echo '<label for="adress" style="margin-right: 20px;">Adres</label>';
                    echo '</td>';
                    echo '<td>';
                    echo '<input type="text" style="width:300px;"  name="adress" value="' . $submission[0]["adress"] . '"/>';
                    echo '</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td>';
                    echo '<label for="zipcode" style="margin-right: 20px;">Postcode</label>';
                    echo '</td>';
                    echo '<td>';
                    echo '<input type="text" name="zipcode" value="' . $submission[0]["zipcode"] . '"/>';
                    echo '</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td>';
                    echo '<label for="city" style="margin-right: 20px;">Plaats</label>';
                    echo '</td>';
                    echo '<td>';
                    echo '<input type="text" style="width:300px;"  name="city" value="' . $submission[0]["city"] . '"/>';
                    echo '</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td>';
                    echo '<label for="email" style="margin-right: 20px;">Email</label>';
                    echo '</td>';
                    echo '<td>';
                    echo '<input type="email" style="width:300px;" name="email" value="' . $submission[0]["email"] . '"/>';
                    echo '</td>';
                echo '</tr>';
                echo '<tr>';
                    echo '<td>';
                    echo '<label for="extra_information" style="margin-right: 20px;">Extra informatie</label>';
                    echo '</td>';
                    echo '<td>';
                    echo '<textarea style="width:300px;" name="extra_information">' . $submission[0]["extra_information"] . '</textarea>';
                    echo '</td>';
                echo '</tr>';
            echo '</table>';
            echo '<input type="submit" value="Bewerken" name="save_submission" />';
            echo '</form>';
        }
        else {
            $path = 'admin.php?page=my_submissions_overview';
            $url = admin_url($path);
            wp_redirect($url);
        }
    } else {
        $path = 'admin.php?page=my_submissions_overview';
        $url = admin_url($path);
        wp_redirect($url);
    }
}
