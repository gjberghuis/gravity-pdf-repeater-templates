<?php

function render_edit_submission_page() {
    global $wpdb;

    if (isset($_GET['action']) && $_GET['action'] == 'edit_submission' && isset($_GET['id'])) {
        $submission = $wpdb->get_results("SELECT * FROM word1_submissions WHERE id = " . $_GET['id']);

        if (count($submission ) > 0) {
            if (isset($_POST['save_reduction_code'])) {
                if (!empty($_POST['ticket_price'])) {
                    $submission [0]->ticket_price = $_POST['ticket_price'];
                }

                if (!empty($_POST['free_parking_ticket'])) {
                    $submission [0]->free_parking_ticket = 1;
                } else {
                    $submission [0]->free_parking_ticket = 0;
                }

                if (!empty($_POST['code'])) {
                    $submission [0]->code = $_POST['code'];
                }

                if (!empty($_POST['description'])) {
                    $submission [0]->description = $_POST['description'];
                }

                $result = $wpdb->update('word1_submission_reduction_codes',
                    array('ticket_price' => $submission [0]->ticket_price,
                        'free_parking_ticket' => $reductionCode[0]->free_parking_ticket,
                        'code' => $reductionCode[0]->code,
                        'description' => $reductionCode[0]->description),
                    array('id' => $reductionCode[0]->id));

                if ($result > 0) {
                    $path = 'admin.php?page=reduction_codes';
                    $url = admin_url($path);
                    wp_redirect($url);
                }
            }

            echo '</pre>';
            echo '<div class="wrap">';

            $path = 'admin.php?page=reduction_codes';
            $url = admin_url($path);

            echo '<a href="' . $url . '">< Terug naar het overzicht</a>';
            echo '<h2>Kortingscode bewerken</h2>';
            echo '<br/>';
            echo '<form action="" method="post">';
            echo '<table>';
            echo '<tr>';
            echo '<td>';
            echo '<label for="ticket_price" style="margin-right: 20px;">Ticket prijs</label>';
            echo '</td>';
            echo '<td>';
            echo '<input type="int" name="ticket_price" value="' . $reductionCode[0]->ticket_price . '"/>';
            echo '</td>';
            echo '</tr>';
            echo '<tr><td><br/></td></tr>';
            echo '<tr>';
            echo '<td>';
            echo '<label for="ticket_price" style="margin-right: 20px;">Gratis parkeer ticket</label>';
            echo '</td>';
            echo '<td>';
            echo '<input type="checkbox" name="free_parking_ticket"' .  ($reductionCode[0]->free_parking_ticket==1 ? 'checked' : '') . '/>';
            echo '</td>';
            echo '</tr>';
            echo '<tr><td><br/></td></tr>';
            echo '<tr>';
            echo '<td>';
            echo '<label for="code" style="margin-right: 20px;">Code</label>';
            echo '</td>';
            echo '<td>';
            echo '<input type="text" style="width:600px;" name="code" value="' . $reductionCode[0]->code . '" />';
            echo '<br/>';
            echo '</td>';
            echo '</tr>';
            echo '<tr><td><br/></td></tr>';
            echo '<tr>';
            echo '<td>';
            echo '<label for="description" style="margin-right: 20px;">Beschrijving</label>';
            echo '</td>';
            echo '<td>';
            echo '<input type="textarea" style="width:600px;" name="description" value="' . $reductionCode[0]->description . '" />';
            echo '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td style="padding:20px;">';
            echo '<input type="submit" value="Bewerken" name="save_reduction_code" />';
            echo '</td>';
            echo '</tr>';
            echo '</table>';
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
