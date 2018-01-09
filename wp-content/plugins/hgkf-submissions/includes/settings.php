<?php
    function render_settings_page()
    {
        global $wpdb;
        $settings = $wpdb->get_results("SELECT * FROM word1_submission_settings where preset = 1");

        echo '</pre><div class="wrap"><h2>Aanmeldingen overzicht</h2>';

        echo '<form action="" method="post">';
        echo '<table style="width: 100%;">';

        echo '<tr>';
        echo '<td style="width: 20%;">';
        echo '<label for="ticket_price_single" style="margin-right: 20px;">Ticket prijs (op basis van individuele aanmelding)</label>';
        echo '</td>';
        echo '<td style="width: 80%;">';
        echo '<input type="number" style="width: 100px;" value="' . $settings[0]->ticket_price_single . '" name="ticket_price_single" />';
        echo '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td>';
        echo '<label for="ticket_price_group" style="margin-right: 20px;">Ticket prijs ( op basis van groepsaanmelding)</label>';
        echo '</td>';
        echo '<td>';
        echo '<input type="number" style="width: 100px;"  value="' . $settings[0]->ticket_price_group . '" name="ticket_price_group" />';
        echo '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td>';
        echo '<label for="price_parkingticket" style="margin-right: 20px;">Prijs parkeerticket</label>';
        echo '</td>';
        echo '<td>';
        echo '<input type="number" style="width: 100px;"  value="' . $settings[0]->price_parkingticket . '" name="price_parkingticket" />';
        echo '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td>';
        echo '<label for="btw_low_number" style="margin-right: 20px;">BTW nummer laag tarief</label>';
        echo '</td>';
        echo '<td>';
        echo '<input type="number" style="width: 100px;"  value="' . $settings[0]->btw_low_number . '" name="btw_low_number" />';
        echo '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td>';
        echo '<label for="btw_low" style="margin-right: 20px;">BTW laag tarief</label>';
        echo '</td>';
        echo '<td>';
        echo '<input type="number" style="width: 100px;"  step="0.01" value="' . $settings[0]->btw_low . '" name="btw_low" />';
        echo '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td>';
        echo '<label for="btw_high_number" style="margin-right: 20px;">BTW nummer hoog tarief</label>';
        echo '</td>';
        echo '<td>';
        echo '<input type="number" style="width: 100px;"  value="' . $settings[0]->btw_high_number . '" name="btw_high_number" />';
        echo '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td>';
        echo '<label for="btw_high" style="margin-right: 20px;">BTW hoog tarief</label>';
        echo '</td>';
        echo '<td>';
        echo '<input type="number" style="width: 100px;"  step="0.01" value="' . $settings[0]->btw_high . '" name="btw_high" />';
        echo '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td>';
        echo '<label for="food_price" style="margin-right: 20px;">Prijs voor vertering</label>';
        echo '</td>';
        echo '<td>';
        echo '<input type="number" style="width: 100px;"  step="0.01" value="' . $settings[0]->food_price . '" name="food_price" />';
        echo '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td>';
        echo '<label for="payment_detail_description_low_btw" style="margin-right: 20px;">Exact: beschrijving laag btw</label>';
        echo '</td>';
        echo '<td>';
        echo '<input type="text" style="width: 600px;"  value="' . $settings[0]->payment_detail_description_low_btw . '" name="payment_detail_description_low_btw" />';
        echo '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td>';
        echo '<label for="payment_detail_description_high_btw" style="margin-right: 20px;">Exact: beschrijving hoog btw</label>';
        echo '</td>';
        echo '<td>';
        echo '<input type="text" style="width: 600px;"  value="' . $settings[0]->payment_detail_description_high_btw . '"  name="payment_detail_description_high_btw" />';
        echo '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td>';
        echo '<label for="payment_detail_event_nr_low_btw" style="margin-right: 20px;">Exact: evenement nummer laag btw</label>';
        echo '</td>';
        echo '<td>';
        echo '<input type="text" style="width: 100px;"  value="' . $settings[0]->payment_detail_event_nr_low_btw . '" name="payment_detail_event_nr_low_btw" />';
        echo '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td>';
        echo '<label for="payment_detail_event_nr_high_btw" style="margin-right: 20px;">Exact: evenement nummer hoog btw</label>';
        echo '</td>';
        echo '<td>';
        echo '<input type="text" style="width: 100px;"  value="' . $settings[0]->payment_detail_event_nr_high_btw . '" name="payment_detail_event_nr_high_btw" />';
        echo '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td>';
        echo '<label for="invoice_expiration_days" style="margin-right: 20px;">Factuur: betalingstermijn (in dagen)</label>';
        echo '</td>';
        echo '<td>';
        echo '<input type="text" style="width: 100px;"  value="' . $settings[0]->invoice_expiration_days . '" name="invoice_expiration_days" />';
        echo '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td>';
        echo '<label for="invoice_description_text" style="margin-right: 20px;">Factuur: beschrijving op factuur</label>';
        echo '</td>';
        echo '<td>';
        echo '<textarea type="text" style="width: 600px; height: 200px;" name="invoice_description_text">' . $settings[0]->invoice_description_text . '</textarea>';
        echo '</td>';
        echo '</tr>';

        echo '<tr>';
        echo '<td style="padding:20px;">';
        echo '<input type="submit" value="Opslaan" name="submit" />';
        echo '</td>';
        echo '</tr>';
        echo '</table>';
        echo '</form>';
        echo '</div>';
    }

    add_action('admin_init', 'saveSettings');

    function saveSettings(){
        if (!empty($_POST) && isset($_POST['submit']))
        {
            global $wpdb;
            $wpdb->update('word1_submission_settings',
                array(
                    'ticket_price_single' => $_POST['ticket_price_single'],
                    'ticket_price_group' => $_POST['ticket_price_group'],
                    'price_parkingticket' => $_POST['price_parkingticket'],
                    'btw_low_number' => $_POST['btw_low_number'],
                    'btw_low' => $_POST['btw_low'],
                    'btw_high_number' => $_POST['btw_high_number'],
                    'btw_high' => $_POST['btw_high'],
                    'food_price' => $_POST['food_price'],
                    'payment_detail_description_low_btw' => $_POST['payment_detail_description_low_btw'],
                    'payment_detail_description_high_btw' => $_POST['payment_detail_description_high_btw'],
                    'payment_detail_event_nr_low_btw' => $_POST['payment_detail_event_nr_low_btw'],
                    'payment_detail_event_nr_high_btw' => $_POST['payment_detail_event_nr_high_btw'],
                    'invoice_expiration_days' => $_POST['invoice_expiration_days'],
                    'invoice_description_text' => $_POST['invoice_description_text']
                ),
                array(
                    'preset' => 1
                )
            );

            wp_redirect(admin_url()  . "?page=my_submissions_overview");
        }
    }

?>