<?php
/**
 * Plugin Name: Submissions overview Het Grootste Kennisfestival
 * Plugin URI: none
 * Description: Overview of the submission and the attached invoice information from the gravity submissions
 * Version: 1.0
 * Author: Gert-Jan Berghuis
 * Author URI: none
 * License: none
 */
if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

require_once('includes/settings.php');
require_once('includes/submissions.php');
require_once('includes/edit-submission.php');
require_once('includes/reductions-codes.php');
require_once('includes/process-gravity-entry');

$submissionCollection = [];

function my_add_menu_items()
{
    $hookSubmissions = add_menu_page('Aanmeldingen', 'Aanmeldingen', 'manage_options', 'my_submissions_overview', 'render_submissions_overview_page');
    add_submenu_page(null, 'Aanmelding bewerken', 'Aanmelding bewerken', 'manage_options', 'edit_submission', 'render_edit_submission_page');
    add_submenu_page('my_submissions_overview', 'Kortingscodes', 'Kortingscodes', 'manage_options', 'reduction_codes', 'render_reduction_codes_page');
    add_submenu_page(null, 'Kortingscode toevoegen', 'Kortingscode toevoegen', 'manage_options', 'add_reduction_code', 'render_add_reduction_code_page');
    add_submenu_page(null, 'Kortingscode bewerken', 'Kortingscode bewerken', 'manage_options', 'edit_reduction_code', 'render_edit_reduction_code_page');
    add_submenu_page('my_submissions_overview', 'Instellingen', 'Instellingen', 'manage_options', 'settings', 'render_settings_page');
    add_action("load-$hookSubmissions", 'add_options_submissions');
}

function add_options_submissions()
{
    global $myListTable;

    $option = 'per_page';
    $args = array(
        'label' => 'Aanmeldingen',
        'default' => 10,
        'option' => 'submissions_per_page'
    );
    add_screen_option($option, $args);

    $myListTable = new My_submission_list;
}

add_action('admin_menu', 'my_add_menu_items');

function render_submissions_overview_page()
{
    wp_enqueue_script('jquery-ui-datepicker');
    wp_register_style('jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
    wp_enqueue_style('jquery-ui');

    global $myListTable;
    echo '</pre><div class="wrap"><h2>Aanmeldingen overzicht</h2>';

    echo '<form id="date" name="date" action="" method="post">';
        echo '<input type="hidden" name="page" value="<?php echo $_REQUEST[\'page\'] ?>" />';
        echo '<table>';
            echo '<tr>';
            echo '<td>';
            echo '<label for="from_date" style="margin-right: 20px;">Ticket prijs</label>';
            echo '</td>';
            echo '<td>';
            echo '<input type="date" name="from_date" />';
            echo '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td>';
            echo '<label for="to_date" style="margin-right: 20px;">Code</label>';
            echo '</td>';
            echo '<td>';
            echo '<input type="date" name="to_date" />';
            echo '</td>';
            echo '</tr>';
            echo '<tr>';
            echo '<td style="padding:20px;">';
            echo '<input type="submit" value="Download deelnemers in csv" name="download_participants" />';
            echo '</td>';
            echo '<td style="padding:20px;">';
            echo '<input type="submit" value="Download facturen in csv" name="download_invoices_new" />';
            echo '</td>';
            echo '</tr>';
        echo '</table>';
    echo '</form>';
    ?>

    <?php

    echo '<form action="" method="post" id="Submission" name="submissions">';
    echo '<input type="hidden" name="page" value="' . $_REQUEST['page'] . '">';
        $myListTable->prepare_items();
        $myListTable->search_box('zoeken', 'search');
        $myListTable->display();

    echo '</form>';
    echo '</div>';
}

function render_reduction_codes_page()
{
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id']) && !empty($_GET['id'])) {
        global $wpdb;

        $id = $_GET['id'];
        $result = $wpdb->delete($wpdb->prefix . 'submission_reduction_codes', array( 'id' => $id), array( '%s', '%s' ) );

        if ($result > 0) {
            $path = 'admin.php?page=reduction_codes';
            $url = admin_url($path);
            wp_redirect($url);
        }
    }

    global $reductionCodes;

    $reductionCodes = new reduction_codes();
    echo '</pre><div class="wrap"><h2>Kortingscodes overzicht</h2>';
    $reductionCodes->prepare_reduction_codes();

    $path = 'admin.php?page=add_reduction_code';
    $url = admin_url($path);

    echo '<a href="' . $url . '"><button>Nieuwe code toevoegen</button></a>';

    $reductionCodes->display();
}

function render_add_reduction_code_page() {
    if (isset($_POST['add_reduction_code'])) {
        $ticketPrice = 0;
        if (!empty($_POST['ticket_price'])) {
            $ticketPrice  = $_POST['ticket_price'];
        }

        $code = 0;
        if (!empty($_POST['code'])) {
            $code  = $_POST['code'];
        }

        $freeParkingTicket = 0;
        if (!empty($_POST['free_parking_ticket'])) {
            $freeParkingTicket  = 1;
        } else {
            $freeParkingTicket = 0;
        }

        $description = 0;
        if (!empty($_POST['description'])) {
            $description  = $_POST['description'];
        }

        global $wpdb;

        $result = $wpdb->insert($wpdb->prefix .'submission_reduction_codes', array( 'ticket_price' => $ticketPrice, 'code' => $code, 'free_parking_ticket' => $freeParkingTicket, 'description' => $description), array( '%s', '%s' ) );

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
    echo '<h2>Kortingscode toevoegen</h2>';
    echo '<br/>';
    echo '<form action="" method="post">';
    echo '<table>';
    echo '<tr>';
    echo '<td>';
    echo '<label for="ticket_price" style="margin-right: 20px;">Ticket prijs</label>';
    echo '</td>';
    echo '<td>';
    echo '<input type="int" name="ticket_price" />';
    echo '</td>';
    echo '</tr>';
    echo '<tr><td><br/></td></tr>';
    echo '<tr>';
    echo '<td>';
    echo '<label for="code" style="margin-right: 20px;">Code</label>';
    echo '</td>';
    echo '<td>';
    echo '<input type="text" style="width:600px;" name="code" />';
    echo '</td>';
    echo '</tr>';
    echo '<tr><td><br/></td></tr>';
    echo '<tr>';
    echo '<td>';
    echo '<label for="description" style="margin-right: 20px;">Gratis parkeerticket</label>';
    echo '</td>';
    echo '<td>';
    echo '<input type="checkbox" name="free_parking_ticket"/>';
    echo '</td>';
    echo '</tr>';
    echo '<tr><td><br/></td></tr>';
    echo '<tr>';
    echo '<td>';
    echo '<label for="description" style="margin-right: 20px;">Beschrijving</label>';
    echo '</td>';
    echo '<td>';
    echo '<input type="text" style="width:600px;" name="description" />';
    echo '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td style="padding:20px;">';
    echo '<input type="submit" value="Toevoegen" name="add_reduction_code" />';
    echo '</td>';
    echo '</tr>';
    echo '</table>';
    echo '</form>';
}

function render_edit_reduction_code_page() {
    global $wpdb;

    if (isset($_GET['action']) && $_GET['action'] == 'edit_reduction_code' && isset($_GET['id'])) {
        $reductionCode = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}submission_reduction_codes WHERE id = " . $_GET['id']);

        if (count($reductionCode) > 0) {
            if (isset($_POST['save_reduction_code'])) {
                if (!empty($_POST['ticket_price'])) {
                    $reductionCode[0]->ticket_price = $_POST['ticket_price'];
                }

                if (!empty($_POST['free_parking_ticket'])) {
                    $reductionCode[0]->free_parking_ticket = 1;
                } else {
                    $reductionCode[0]->free_parking_ticket = 0;
                }

                if (!empty($_POST['code'])) {
                    $reductionCode[0]->code = $_POST['code'];
                }

                if (!empty($_POST['description'])) {
                    $reductionCode[0]->description = $_POST['description'];
                }

                $result = $wpdb->update($wpdb->prefix  . 'submission_reduction_codes',
                    array('ticket_price' => $reductionCode[0]->ticket_price,
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
            $path = 'admin.php?page=reduction_codes';
            $url = admin_url($path);
            wp_redirect($url);
        }
    } else {
        $path = 'admin.php?page=reduction_codes';
        $url = admin_url($path);
        wp_redirect($url);
    }
}

add_action('admin_init', 'convert_to_csv');

function convert_to_csv()
{ 
    if (isset($_POST['download_participants']) || isset($_POST['download_invoices_new'])) {
        $downloadParticipantsFields = array('submission_type','submission_date','organization','reduction_code','notes','participant_firstname','participant_lastname','participant_email' );
        $downloadInvoicesFields = array('submission_id','debiteur_nr','book_nr','cost_post','description','follow_nr','submission_type','submission_date','expiration_days','expiration_days','timestamp','organization','firstname','lastname','adress','zipcode','city','email','extra_information','parking_tickets','reduction_code','reduction_code','notes');
   
        $date = '2016-11-01';
        $fromDate = date('Y-m-d', strtotime($date));

        if (!empty($_POST['from_date'])) {
            $fromDate = $_POST['from_date'];
        }
        $toDate = date('Y-m-d');
        if (!empty($_POST['to_date'])) {
            $toDate = $_POST['to_date'];
        }

        $filenamePrefix = 'facturen_';
        if (isset($_POST['download_participants'])) {
            $filenamePrefix = 'deelnemers_';
        }
        $output_file_name = $filenamePrefix . $fromDate . '_' . $toDate . '.csv';
        $delimiter = ',';

        global $wpdb;

        foreach ($wpdb->get_col("DESC " . $wpdb->prefix . 'submissions', 0) as $column_name) {
            if (isset($_POST['download_participants']) && in_array($column_name, $downloadParticipantsFields)) {
                $header[] = $column_name;
            } elseif (isset($_POST['download_invoices_new']) && in_array($column_name, $downloadInvoicesFields)) {
                $header[] = $column_name;
            }
        }

        foreach ($wpdb->get_col("DESC " . $wpdb->prefix . 'submission_invoices', 0) as $column_name) {
            if ($column_name != 'submission_id') {
                if (isset($_POST['download_participants']) && in_array($column_name, $downloadParticipantsFields)) {
                    $header[] = $column_name;
                } elseif (isset($_POST['download_invoices_new']) && in_array($column_name, $downloadInvoicesFields)) {
                    $header[] = $column_name;
                }
            }
        }

        if (isset($_POST['download_participants'])) {
            $header[] = "participant_firstname";
            $header[] = "participant_lastname";
            $header[] = "participant_email";
        } elseif (isset($_POST['download_invoices_new'])) {
            $header[] = "payment_event";
            $header[] = "payment_row_description";
            $header[] = "payment_price";
            $header[] = "payment_btw_type";
            $header[] = "payment_tax";
        }

        $f = fopen('php://output', 'w');
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename=' . $output_file_name);
        fputcsv($f, $header, ';');

        $submissions = $wpdb->get_results("SELECT submission.*, invoice.* FROM {$wpdb->prefix}submissions  as submission INNER JOIN {$wpdb->prefix}submission_invoices as invoice ON invoice.submission_id = submission.submission_id WHERE active < 1 OR active is NULL AND submission_date >= '" . $fromDate . "' AND submission_date <= '" . $toDate . "'");

        /* loop through array  */
        foreach ($submissions as $submission) {
            $submissionTempArray = (array)$submission;
            $submissionArray = $submissionTempArray;
            foreach ($submissionTempArray as $key => $value) {
                if (isset($_POST['download_participants']) && !in_array($key, $downloadParticipantsFields)) {
                    unset($submissionArray[$key]);
                } elseif (isset($_POST['download_invoices_new']) && !in_array($key, $downloadInvoicesFields)) {
                    unset($submissionArray[$key]);
                }
            }
            
            if (!empty($submissionArray['price'])) {
                $submissionArray['price'] = number_format($submissionArray['price'], 2, ',', '');
            }
            if (!empty($submissionArray['price_tax'])) {
                $submissionArray['price_tax'] = number_format($submissionArray['price_tax'], 2, ',', '');
            }
            if (!empty($submissionArray['tax'])) {
                $submissionArray['tax'] = number_format($submissionArray['tax'], 2, ',', '');
            }

            if (isset($_POST['download_participants'])) {
                $submissionId = $submissionTempArray['submission_id'];

                $submissionsOParticipants = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}submission_participants where submission_id = " . $submissionId);

                foreach ($submissionsOParticipants as $participant) {
                    $participantArray = (array)$participant;

                    $lineArray = $submissionArray;
                
                    $nameParticipant = explode(" ", $participantArray['name']);
                    $firstnameParticipant = "";
                    $lastnameParticipant = "";
                    for ($i = 0; $i <= count($nameParticipant); $i++) {
                        if ($i == 0) {
                            $firstnameParticipant = $nameParticipant[$i];
                        } else {
                            $lastnameParticipant .= $nameParticipant[$i];    
                        }
                    }

                    $lineArray[] = $firstnameParticipant;
                    $lineArray[] = $lastnameParticipant;
                    $lineArray[] = $participantArray['email'];

                    /** default php csv handler **/
                    fputcsv($f, $lineArray, ';');
                }
            } elseif (isset($_POST['download_invoices_new'])) {
                $submissionId = $submissionTempArray['submission_id'];
                $submissionPaymentDetails = $wpdb->get_results("SELECT event as payment_event, row_description as payment_row_description, price as payment_price,btw_type as payment_btw_type, tax as payment_tax FROM {$wpdb->prefix}submission_crm_details where submission_id = " . $submissionId);

                 foreach ($submissionPaymentDetails as $paymentDetail) {
                    $paymentArray = (array)$paymentDetail;
                    
                    $lineArray = $submissionArray;
                    $csvArray = array_merge($lineArray, $paymentArray);
                    fputcsv($f, $csvArray, ';');
                }
            } else {
                fputcsv($f, $submissionArray, ';');
            }
        }
        fclose($f);
        exit;
    }
}

function objectToArray($obj)
{
    if (is_object($obj)):
        $object = get_object_vars($obj);
    endif;

    return array_map('objectToArray', $object); // return the object, converted in array.
}