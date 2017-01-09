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
        width: 40%;
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
    width:100%;
    text-align: center;
    margin-top: 40px;
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

table.people tr th {  
     text-align:left;
           background-color: white;
    color: black;
      padding-top: 12px;
    padding-bottom: 12px;
}
</style>

<div class="container">


<div class="header">

    <div class="naw">
        <ul>
            <li><b>{Organisatie:16}</b></li>
            <li>T.a.v. {T.a.v. (Voornaam):17.3} {T.a.v. (Achternaam):17.6}</li>
            <li>{Adres (Straat + huisnummer):18.1}</li>
            <li>{Adres (Postcode):18.3}</li>
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
              <!--  <li>
                    <div class="general-label"><b>Factuurnummer</b></div>
                    <div class="general-value">12345</div>
                </li>
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
                    <div class="general-value"><?php echo date('d-m-Y', strtotime("+30 days")); ?></div>
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
             
    <div><br/>Deelnemers:</div>

              <table class="people">
              
                <tr>
                <th>Naam</th>
                <th>Emailadres</th>
                </tr>           
<?php
 $repeats = [];
 $participants =[];

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

    $participants[$keyOneEntry] = $participant;
  
    array_push($repeats, $singleRepeat);
    unset($singleRepeat);

 } 

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
                </td>

                <?php 
                    $participantsPrice = (count($participants)*195); 
                    $parkingTicket = 10;
                    $btw = $participantsPrice * 0.21;
                    $btwWithPartkingTicket = ($parkingTicket + $participantsPrice) * 0.21;
                    $totalPrice = $parkingTicket + $participantsPrice;
                    $totalPriceBtw = ($parkingTicket + $participantsPrice) * 1.21;
                    $totalPriceWithoutParkingTicket = $participantsPrice * 1.21;
                    
                ?>

                <td><?php echo count($participants) ?>,00</td>
                <td>€ 195,00</td>
                <td>21 %</td>
                <td>€ <?php echo $participantsPrice ?>,00</td>
            </tr>
            [gravityforms action="conditional" merge_tag="{Parkeerticket:22}" condition="is" value="ja"]
            <tr>
                <td>Parkeerticket</td>
                <td>1,00</td>
                <td>€ 10,00</td>
                <td>21 %</td>
                <td>€ 10,00</td>
            </tr>
            [/gravityforms]
        </table>
    </div>

    <div class="total-price">
        <table>
            <tr>
                <td>Totaal exclusief BTW</td>
                  [gravityforms action="conditional" merge_tag="{Parkeerticket:22}" condition="is" value="ja"]
                    <td>€ <?php echo number_format($totalPrice, 2, ',', ''); ?></td>
                    [/gravityforms]
                    [gravityforms action="conditional" merge_tag="{Parkeerticket:22}" condition="is" value="nee"]
                <td>€ <?php echo number_format($participantsPrice, 2, ',', ''); ?></td>
                [/gravityforms]
            </tr>
            <tr>
                <td>BTW 21%</td>
                  [gravityforms action="conditional" merge_tag="{Parkeerticket:22}" condition="is" value="ja"]
                    <td>€ <?php echo number_format($btwWithPartkingTicket, 2, ',', ''); ?></td>
                    [/gravityforms]
                    [gravityforms action="conditional" merge_tag="{Parkeerticket:22}" condition="is" value="nee"]
                <td>€ <?php echo number_format($btw, 2, ',', ''); ?></td>
                [/gravityforms]
            </tr>
            <tr>
                <th>Totaal te voldoen</th>
                  [gravityforms action="conditional" merge_tag="{Parkeerticket:22}" condition="is" value="ja"]
                    <th>€ <?php echo number_format($totalPriceBtw, 2, ',', ''); ?></th>
                    [/gravityforms]
                    [gravityforms action="conditional" merge_tag="{Parkeerticket:22}" condition="is" value="nee"]
                <th>€ <?php echo number_format($totalPriceWithoutParkingTicket, 2, ',', ''); ?></th>
                [/gravityforms]
            </tr>

        </table>
    </div>

    <div class="payment-notification">
      Wij verzoeken je vriendelijk dit bedrag binnen 14 dagen over te maken naar de Rabobank op rekeningnummer
NL93RABO0300479743 ten name van Regio Academy BV onder vermelding van het factuurnummer.

        </div>

        <div class="info">
           <div class="info-part">
               <ul>
                   <li>Regio Academy</li>
                   <li>Dasstraat 37</li>
                   <li>7559 AA Hengelo</li>
                   <li>Nederland</li>
               </ul>
           </div>
           <div class="info-part-middle">
               <ul>
                   <li>Tel (06)21874369</li>
                   <li>jaap@regioacademy.nl</li>
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
</div>
<!-- Output our HTML markup -->
<?php




/*
 * Load our core-specific styles from our PDF settings which will be passed to the PDF template $config array
 */
$show_form_title      = ( ! empty( $settings['show_form_title'] ) && $settings['show_form_title'] == 'Yes' )            ? true : false;
$show_page_names      = ( ! empty( $settings['show_page_names'] ) && $settings['show_page_names'] == 'Yes' )            ? true : false;
$show_html            = ( ! empty( $settings['show_html'] ) && $settings['show_html'] == 'Yes' )                        ? true : false;
$show_section_content = ( ! empty( $settings['show_section_content'] ) && $settings['show_section_content'] == 'Yes' )  ? true : false;
$enable_conditional   = ( ! empty( $settings['enable_conditional'] ) && $settings['enable_conditional'] == 'Yes' )      ? true : false;
$show_empty           = ( ! empty( $settings['show_empty'] ) && $settings['show_empty'] == 'Yes' )                      ? true : false;

/**
 * Set up our configuration array to control what is and is not shown in the generated PDF
 *
 * @var array
 */
$html_config = array(
    'settings' => $settings,
    'meta'     => array(
        'echo'                     => true, /* whether to output the HTML or return it */
        'exclude'                  => true, /* whether we should exclude fields with a CSS value of 'exclude'. Default to true */
        'empty'                    => $show_empty, /* whether to show empty fields or not. Default is false */
        'conditional'              => $enable_conditional, /* whether we should skip fields hidden with conditional logic. Default to true. */
        'show_title'               => $show_form_title, /* whether we should show the form title. Default to true */
        'section_content'          => $show_section_content, /* whether we should include a section breaks content. Default to false */
        'page_names'               => $show_page_names, /* whether we should show the form's page names. Default to false */
        'html_field'               => $show_html, /* whether we should show the form's html fields. Default to false */
        'individual_products'      => false, /* Whether to show individual fields in the entry. Default to false - they are grouped together at the end of the form */
        'enable_css_ready_classes' => true, /* Whether to enable or disable Gravity Forms CSS Ready Class support in your PDF */
    ),
);

/*
 * Generate our HTML markup
 *
 * You can access Gravity PDFs common functions and classes through our API wrapper class "GPDFAPI"
 */
//$pdf = GPDFAPI::get_pdf_class();
//$pdf->process_html_structure( $entry, GPDFAPI::get_pdf_class( 'model' ), $html_config );
