<?php

/*
 * Template Name: Aanmelding groep
 * Version: 1.0
 * Description: Een template voor een factuur van een groepsaanmelding
 * Author: Gert-Jan Berghuis
 * Group: Kennisfestival
 * License: GPLv2
 * Required PDF Version: 4.0-alpha
 */

/* Prevent direct access to the template */
if ( ! class_exists( 'GFForms' ) ) {
    return;
}

/*
 * All Gravity PDF 4.x templates have access to the following variables:
 *
 * $form (The current Gravity Form array)
 * $entry (The raw entry data)
 * $form_data (The processed entry data stored in an array)
 * $settings (the current PDF configuration)
 * $fields (an array of Gravity Form fields which can be accessed with their ID number)
 * $config (The initialised template config class – eg. /config/zadani.php)
 * $gfpdf (the main Gravity PDF object containing all our helper classes)
 * $args (contains an array of all variables - the ones being described right now - passed to the template)
 */

/*
 * Load up our template-specific appearance settings
 */
$value_border_colour = ( ! empty( $settings['zadani_border_colour'] ) ) ? $settings['zadani_border_colour'] : '#CCCCCC';

?>

<!-- Include styles needed for the PDF -->
<style>

    /* Handle Gravity Forms CSS Ready Classes */
    .row-separator {
        clear: both;
        padding: 1.25mm 0;
    }

    .gf_left_half,
    .gf_left_third, .gf_middle_third,
    .gf_list_2col li, .gf_list_3col li, .gf_list_4col li, .gf_list_5col li {
        float: left;
    }

    .gf_right_half,
    .gf_right_third {
        float: right;
    }

    .gf_left_half, .gf_right_half,
    .gf_list_2col li {
        width: 49%;
    }

    .gf_left_third, .gf_middle_third, .gf_right_third,
    .gf_list_3col li {
        width: 32.3%;
    }

    .gf_list_4col li {
        width: 24%;
    }

    .gf_list_5col li {
        width: 19%;
    }

    .gf_left_half, .gf_right_half {
        padding-right: 1%;
    }

    .gf_left_third, .gf_middle_third, .gf_right_third {
        padding-right: 1.505%;
    }

    .gf_right_half, .gf_right_third {
        padding-right: 0;
    }

    /* Don't double float the list items if already floated (mPDF does not support this ) */
    .gf_left_half li, .gf_right_half li,
    .gf_left_third li, .gf_middle_third li, .gf_right_third li {
        width: 100% !important;
        float: none !important;
    }

    /*
     * Headings
     */
    h3 {
        margin: 1.5mm 0 0.5mm;
        padding: 0;
    }

    /*
     * Quiz Style Support
     */
    .gquiz-field {
        color: #666;
    }

    .gquiz-correct-choice {
        font-weight: bold;
        color: black;
    }

    .gf-quiz-img {
        padding-left: 5px !important;
        vertical-align: middle;
    }

    /*
     * Survey Style Support
     */
    .gsurvey-likert-choice-label {
        padding: 4px;
    }

    .gsurvey-likert-choice, .gsurvey-likert-choice-label {
        text-align: center;
    }

    /*
     * Terms of Service (Gravity Perks) Support
     */
    .terms-of-service-agreement {
        padding-top: 3px;
        font-weight: bold;
    }

    .terms-of-service-tick {
        font-size: 150%;
    }

    /*
     * List Support
     */
    ul, ol {
        margin: 0;
        padding-left: 1mm;
        padding-right: 1mm;
    }

    li {
        margin: 0;
        padding: 0;
        list-style-position: inside;
    }

    /*
     * Header / Footer
     */
    .alignleft {
        float: left;
    }

    .alignright {
        float: right;
    }

    .aligncenter {
        text-align: center;
    }

    p.alignleft {
        text-align: left;
        float: none;
    }

    p.alignright {
        text-align: right;
        float: none;
    }

    /*
     * Independant Template Styles
     */
    .gfpdf-field .label {
        text-transform: uppercase;
        font-size: 90%;
    }

    .gfpdf-field .value {
        border: 1px solid <?php echo $value_border_colour; ?>;
        padding: 1.5mm 2mm;
    }

    .products-title-container, .products-container {
        padding: 0;
    }

    .products-title-container h3 {
        margin-bottom: -0.5mm;
    }


    div.container {
        width: 90%;
        margin: 0 auto;    
    }

    div.logo {
        float:right;
        height: 200px;
        width: 20%;
        margin-top: -120px;
    }

    div.logo img {
        height: 200px;
    }

    div.naw {
        float:left;
      margin-top: 120px;
      width: 40%;
    }

    ul {
        list-style-type:none;
    }

    h1 {
        margin-top: 60px;
    } 

    div.general {
        width: 100%;
    }

    div.general-first {
        width: 50%;
        float:left;
    }

    div.general-second {
        width: 50%;
        float:left;
    }

    div.general-label {
        float:left;
        width: 40%;
    }

    div.general-value {
        float:left;
        width: 60%;
    }

    div.price {
        width: 100%;
        float: left;
        margin-top: 60px;    
    }

    body {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    }

    table {
        width: 100%;
        
        border-collapse: collapse;
    }

    table td {
        vertical-align: top;
    }

    div.total-price { 
        width: 40%;
        margin-left:auto; 
        margin-right:0;
    }

    div.total-price table {
        width: 100%;
        margin-top: 80px;
    }

    table th, table td{
        border: 1px solid #ddd;
        padding: 8px;
    }

    table tr th { 
            text-align:left;
            background-color: #ede8c4;
        color: black;
        padding-top: 12px;
        padding-bottom: 12px;
        }

    table tr th.description { 
            width: 50%;
        }

  

    div.payment-notification {
        margin-top: 100px;
        margin-bottom: 60px;
    }

    div.payment-notification p {
        display:block;
        margin-top: 40px;
    }

    div.info {
        margin-top: 40px;
        width: 90%;
        position:absolute;
        bottom: 0;
    }

    div.info-part{
        width: 33%;
        float: left;
    }

    div.info-part-middle{
        width: 33%;
        float: left;
    }

    div.info-part-middle ul{
    display:table;
    margin:auto;
    }

    div.info-part-last{
        width: 33%;
        float: right;
    }

    div.info-part-last ul{
        float: right;
    }

   table.people {
        margin-top:20px;
        margin-left:20px;
        padding-top:20px;
    }   
    table.people ul {
        list-style-type:none;
    }

    div.participantsInfo ul {
    padding:0;
}
</style>

<?php
    $repeats = [];
    $participants =[];


    $mainParticipant = [];
    if (!empty($form_data['field'][15]["first"]) && !empty($form_data['field'][15]["last"])) {
        $mainParticipant['Naam'] = $form_data['field'][15]["first"] . " " . $form_data['field'][15]["last"];
    } 
    if (!empty($entry[13])) {
        $mainParticipant['E-mailadres'] = $entry[13];
    } 
    $participants[] = $mainParticipant;

    // Loop through each of the form fields and find any instances of a repeater.
    // This just loops through the fields NOT the actual entries, that's next.
    foreach ($form[fields] as $key=>$formField) {
        if (get_class($formField) == 'GF_Field_Repeater') {
            $repeaterID = $formField[id];
            $repeaterChildren = $formField[repeaterChildren];
        }
    }

    // SEARCH THROUGH ENTRY FOR THE FIELD ID OF THE REPEATER
    foreach ($entry as $key=>$formEntry) {
        if ($key == $repeaterID) {
            // Breakdown the repeater's inputs. us = un-serialized.
            $usEntry = unserialize($formEntry);
        }
    }

    foreach ($usEntry as $keyOneEntry=>$oneEntry) {
        $participant = array();
        
        // MATCH UP THE FIELDS AND INPUTS
        foreach ($form[fields] as $key=>$formField) {
            $fieldId = $formField[id];
            if (array_key_exists($fieldId, $oneEntry)) {
                $singleInput = implode(" ",$oneEntry[$fieldId]);
                // Only include inputs that aren't empty
                    if (!empty($singleInput)) {
                    $participant[$formField[label]] = $singleInput;
                    $singleRepeat .= $formField[label] . ": " . $singleInput . ", ";
                }
            }
        }
        $participants[] = $participant;

        array_push($repeats, $singleRepeat);
        unset($singleRepeat);
    }

    $participantsPrice = (count($participants)*175); 
    $parkingTicket = 10;
    $numberParkingTickets = 1;
    if (!empty($entry[27])) {
        $numberParkingTickets = $entry[27];
    }
    $parkingCosts = $numberParkingTickets * $parkingTicket;
    $btw = $participantsPrice * 0.21;
    $btwWithPartkingTicket = ($parkingCosts + $participantsPrice) * 0.21;
    $totalPrice = $parkingCosts + $participantsPrice;
    $totalPriceBtw = ($parkingCosts + $participantsPrice) * 1.21;
    $totalPriceWithoutParkingTicket = $participantsPrice * 1.21;

    // Create invoice number
        global $wpdb;
        $count = $wpdb->get_var("SELECT COUNT(*) FROM word1_submissions");

        $invoiceCount = $count + 1;
        $invoice_deb_nr = $invoiceCount + 12000;
        $invoice_book_nr = '71';
        $invoice_cost_post = 'HGKF';
        $invoice_description = 'Deelname Het Grootste Kennisfestival';
        $invoice_row_description = 'Deelname Het Grootste Kennisfestival';
        $invoice_follow_nr = '2017' . str_pad($invoiceCount, 4, "0", STR_PAD_LEFT); 
        $invoiceNumber = $invoice_cost_post . $invoice_follow_nr;  

        $submission_id = $entry['id'];
        $submission_date = date("d-m-Y");
        $submission_dateDb = date("Y-m-d H:i:s");
        $invoice_expiration_days = '14';
        $expiration_date = date('d-m-Y', strtotime("+14 days"));
        $expiration_dateDb = date('Y-m-d H:i:s', strtotime("+14 days"));

        $organization = $entry['16'];
        $invoice_firstname = $entry['17.3'];
        $invoice_lastname = $entry['17.6'];
        $invoice_adress = $entry['18.1'];
        $invoice_zipcode = $entry['18.3'];
        $invoice_city = $entry['18.5'];
        $invoice_email = $entry['19'];
        $invoice_extra_information = $entry['20'];
        $invoice_event_nr = '8000';
        $invoice_btw_type_nr = '2';
        $notes = $entry['23'];
        $participant_firstname = $entry['15.3'];
        $participant_lastname = $entry['15.6'];
        $participant_email = $entry['13'];

        global $wpdb;
        $exists = $wpdb->get_var("SELECT COUNT(*) FROM word1_submissions WHERE submission_id = '$submission_id'");
        
        if ($exists < 1) {
            $wpdb->insert('word1_submissions',
                array(
                    'submission_id'=>$submission_id,
                    'invoice_debiteur_nr'=>$invoice_deb_nr,
                    'invoice_number'=>$invoiceNumber,
                    'invoice_book_nr'=>$invoice_book_nr,
                    'invoice_cost_post'=>$invoice_cost_post,
                    'invoice_description'=>$invoice_description,
                    'invoice_row_description'=>$invoice_row_description,
                    'invoice_follow_nr'=>$invoice_follow_nr,
                    'submission_type'=>'groep',
                    'submission_date'=>$submission_dateDb,
                    'invoice_expiration_days'=>$invoice_expiration_days,
                    'expiration_date'=>$expiration_dateDb,
                    'organization'=>$organization,
                    'invoice_firstname'=>$invoice_firstname,
                    'invoice_lastname'=>$invoice_lastname,
                    'invoice_adress'=>$invoice_adress,
                    'invoice_zipcode'=>$invoice_zipcode,
                    'invoice_city'=>$invoice_city,
                    'invoice_event_nr'=>$invoice_event_nr,
                    'price'=>$totalPrice,
                    'invoice_btw_type'=>$invoice_btw_type_nr,
                    'tax'=>$btw,
                    'price_tax'=>$totalPriceBtw,
                    'invoice_email'=>$invoice_email,
                    'invoice_extra_information'=>$invoice_extra_information,
                    'parking_tickets'=>$numberParkingTickets,
                    //'reduction_code'=>$kortingsCode,
                    'notes'=>$notes
                )
            );
            
            $submissionId = $wpdb->get_var("SELECT id FROM word1_submissions WHERE submission_id = '$submission_id'");

            foreach ($participants as $particpant) {
                $wpdb->insert('word1_submission_participants',
                    array(
                        'invoice_id'=>$submissionId,
                        'name'=>$particpant['Naam'],
                        'email'=>$participant['E-mailadres'] 
                    )
                );
            }
        }
?>

<div class="container">
    <div class="header">

        <div class="naw">
            <ul>
                <li><b>{Organisatie:16}</b></li>
                <li>T.a.v. {T.a.v. (Voornaam):17.3} {T.a.v. (Achternaam):17.6}</li>
                <li>{Adres (Straat + huisnummer):18.1}</li>
                <li>{Adres (Postcode):18.3} {Adres (Plaats):18.5}</li>
                <li>{Adres (Land):18.6}</li>
            </ul>   
        </div>
        <div class="logo">
            <img src="http://www.hetgrootstekennisfestivalvannederland.nl/site/wp-content/uploads/PDF_EXTENDED_TEMPLATES/images/logo-regioacademy.png"></img>
        </div>
    </div>

    <h1>Factuur</h1>

    <div class="general">
        <div class="general-first">
            <ul>
               <li>
                    <div class="general-label"><b>Factuurnummer</b></div>
                    <div class="general-value"><?php echo $invoiceNumber; ?></div>
                </li>
                <!--
                <li>
                    <div class="general-label"><b>Debiteurnummer</b></div>
                    <div class="general-value">453475</div>
                </li> -->
                <li>
                    <div class="general-label"><b>Uw referentie</b></div>
                    <div class="general-value">{Specifieke informatie op de factuur:20}</div>
                </li>
            </ul>    
        </div>
        <div class="general-second">
            <ul>
                <li>
                    <div class="general-label"><b>Factuurdatum</b></div>
                    <div class="general-value"><?php echo date("d-m-Y"); ?></div>
                </li>
                <li>
                    <div class="general-label"><b>Vervaldatum</b></div>
                    <div class="general-value"><?php echo date('d-m-Y', strtotime("+14 days")); ?></div>
                </li>
            </ul>
        </div>
    </div>

    <div class="price">
        <table>
            <tr>
                <th class="description">Omschrijving</th>
                <th>Aantal</th>
                <th>Prijs</th>
                <th>BTW %</th>
                <th>Totaalbedrag</th>
            </tr>
            <tr>
                <td>Deelname Het Grootste Kennisfestival
                </td>
                <td align="right"><?php echo number_format(count($participants), 2, ',', ''); ?></td>
                <td align="right">€ 175,00</td>
                <td align="right">21 %</td>
                <td align="right">€ <?php echo number_format($participantsPrice, 2, ',', ''); ?></td>
            </tr>
            [gravityforms action="conditional" merge_tag="{Parkeerticket:22}" condition="is" value="ja"]
            <tr>
                <td>Parkeerticket</td>
                <td align="right"><?php echo number_format($numberParkingTickets, 2, ',', ''); ?></td>
                <td align="right">€ 10,00</td>
                <td align="right">21 %</td>
                <td align="right">€ <?php echo number_format($parkingCosts, 2, ',', ''); ?></td>
            </tr>
            [/gravityforms]
        </table>
    </div>

    <div class="total-price">
        <table>
            <tr>
                <td>Totaal exclusief BTW</td>
                  [gravityforms action="conditional" merge_tag="{Parkeerticket:22}" condition="is" value="ja"]
                    <td align="right">€ <?php echo number_format($totalPrice, 2, ',', ''); ?></td>
                    [/gravityforms]
                    [gravityforms action="conditional" merge_tag="{Parkeerticket:22}" condition="is" value="nee"]
                <td align="right">€ <?php echo number_format($participantsPrice, 2, ',', ''); ?></td>
                [/gravityforms]
            </tr>
            <tr>
                <td>BTW 21%</td>
                  [gravityforms action="conditional" merge_tag="{Parkeerticket:22}" condition="is" value="ja"]
                    <td align="right">€ <?php echo number_format($btwWithPartkingTicket, 2, ',', ''); ?></td>
                    [/gravityforms]
                    [gravityforms action="conditional" merge_tag="{Parkeerticket:22}" condition="is" value="nee"]
                <td align="right">€ <?php echo number_format($btw, 2, ',', ''); ?></td>
                [/gravityforms]
            </tr>
            <tr>
                <th>Totaal te voldoen</th>
                  [gravityforms action="conditional" merge_tag="{Parkeerticket:22}" condition="is" value="ja"]
                    <th align="right">€ <?php echo number_format($totalPriceBtw, 2, ',', ''); ?></th>
                    [/gravityforms]
                    [gravityforms action="conditional" merge_tag="{Parkeerticket:22}" condition="is" value="nee"]
                <th align="right">€ <?php echo number_format($totalPriceWithoutParkingTicket, 2, ',', ''); ?></th>
                [/gravityforms]
            </tr>

        </table>
    </div>

    <div class="payment-notification">
      Wij verzoeken je vriendelijk dit bedrag binnen 14 dagen over te maken naar de Rabobank op rekeningnummer NL93RABO0300479743 ten name van Regio Academy BV onder vermelding van het factuurnummer. Mocht je vragen hebben naar aanleiding van deze factuur dan kan je een mail sturen naar  administratie@regioacademy.nl. Dan nemen we zo snel mogelijk contact met je op.
    </div>

   <div class="info">
        <div class="info-part">
            <ul>
                <li>Regio Academy</li>
                <li>Slingerbos 8</li>
                <li>7431 BV Diepenveen</li>
                <li>Nederland</li>
            </ul>
        </div>
        <div class="info-part-middle">
            <ul>
                <li>www.regioacademy.nl</li>
            </ul>
        </div>
        <div class="info-part-last">
            <ul>
                <li>IBAN NL93RABO0300479743</li>
                <li>BTW nr. NL852266248B01</li>
                <li>KvK nr. 56692897</li>
            </ul>
        </div>
    </div>

    <div class="participantsInfo">
        <h1>Deelnemers</h1>
        <div>   
           <table class="people">
                <tr>
                    <th>Naam</th>
                    <th>Emailadres</th>
                </tr>    
                
                <?php
                    foreach ($participants as $key => $value) {
                ?>

                <tr>
                    <td><?php echo($value['Naam']) ?></td>
                    <td><?php echo $value['E-mailadres']; ?></td>
                </tr>
                    
                <?php 
                    } 
                ?>
            </table>        
        </div>
    </div>
</div>            