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
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

$submissionCollection = [];

class My_submission_list extends WP_List_Table {
    function __construct(){
        global $status, $page;
            parent::__construct( array(
                'singular'  => __( 'aanmelding', 'mylisttable' ),     
                'plural'    => __( 'aanmeldingen', 'mylisttable' ),   
                'ajax'      => false        
        ) );
        add_action( 'admin_head', array( $this, 'admin_header' ) );            
    }

    
    /**
    * Retrieve submission data from the database
    *
    * @param int $per_page
    * @param int $page_number
    *
    * @return mixed
    */
    public static function get_submissions( $per_page = 5, $page_number = 1 ) {
        global $wpdb;

        $sql = "SELECT * FROM {$wpdb->prefix}submissions";

        if ( ! empty( $_REQUEST['orderby'] ) ) {
            $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
            $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
        }

        $sql .= " LIMIT $per_page";

        $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


        $result = $wpdb->get_results( $sql, 'ARRAY_A' );
        $submissionCollection = $result;
        return $result;
    }

/**
 * Change the active status a submission in the export for exact
 *
 * @param int $id submission id
 */
public static function change_active_submission( $id ) {
    global $wpdb;
    $results = $wpdb->get_results("SELECT active from {$wpdb->prefix}submissions WHERE id = " . $id);

    $newStatus = 1;
    if ($results[0]->active == 1) {
        $newStatus = 0;
    }

    $wpdb->query("UPDATE {$wpdb->prefix}submissions SET active=" . $newStatus . " WHERE id = " . $id);
}

	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count() {
		global $wpdb;

		$sql = "SELECT COUNT(*) FROM {$wpdb->prefix}submissions";

		return $wpdb->get_var( $sql );
	}
    
    function column_default( $item, $column_name ) {
        switch( $column_name ) { 
            case 'invoice_number':
            case 'active':
            case 'submission_type':
            case 'submission_date':
            case 'organization':
            case 'invoice_firstname':
            case 'invoice_lastname':
            case 'price':
            case 'price_tax':
            case 'parking_tickets':
            case 'reduction_code':
            case 'notes':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ) ; //Show the whole array for troubleshooting purposes
        }
    }

    function no_items() {
        _e( 'Geen aanmeldingen gevonden.' );
    }
    
    function get_columns(){
        $columns = array(
            'cb'      => '<input type="checkbox" />',
            'invoice_number' => __( 'Factuur nummer', 'mylisttable' ),
            'active' => __( 'Negeren in export', 'mylisttable' ),
            'submission_type'    => __( 'Type aanmelding', 'mylisttable' ),
            'submission_date'      => __( 'Inzend datum', 'mylisttable' ),
            'organization' => __( 'Organisatie', 'mylisttable' ),
            'invoice_firstname' => __( 'Voornaam', 'mylisttable' ),
            'invoice_lastname' => __( 'Achternaam', 'mylisttable' ),
            'price' => __( 'Prijs excl. Btw', 'mylisttable' ),       
            'price_tax' => __( 'Prijs incl. Btw', 'mylisttable' ),
            'parking_tickets' => __( 'Parkeertickets', 'mylisttable' ),
            'reduction_code' => __( 'Kortingscode', 'mylisttable' ),
            'notes' => __( 'Opmerkingen', 'mylisttable' )
        );
         return $columns;
    }

    function column_active($item) {
  $actions = array(
            'active'    => sprintf('<a href="?page=%s&action=%s&submission=%s">Verander status</a>',$_REQUEST['page'],'active',$item['id'])
        );

  return sprintf('%1$s %2$s', $item['active'], $this->row_actions($actions) );
}


    function get_sortable_columns() {
        $sortable_columns = array(
            'invoice_number'  => array('invoice_number',false),
            'submission_type' => array('submission_type',false),
            'submission_date'   => array('submission_date',false)
        );
        return $sortable_columns;
    }

    function admin_header() {
        $page = ( isset($_GET['page'] ) ) ? esc_attr( $_GET['page'] ) : false;
        if( 'my_submissions_overview' != $page )
        return;
        echo '<style type="text/css">';
        echo '.wp-list-table .column-id { width: 5%; }';
      //  echo '.wp-list-table .column-invoice_number { width: 40%; }';
      //  echo '.wp-list-table .column-submission_type { width: 35%; }';
       // echo '.wp-list-table .column-submission_date { width: 20%;}';
        echo '</style>';
    }


    function usort_reorder( $a, $b ) {
        // If no sort, default to title
        $orderby = ( ! empty( $_GET['orderby'] ) ) ? $_GET['orderby'] : 'invoice_number';
        // If no order, default to asc
        $order = ( ! empty($_GET['order'] ) ) ? $_GET['order'] : 'asc';
        // Determine sort order
        $result = strcmp( $a[$orderby], $b[$orderby] );
        // Send final sort direction to usort
        return ( $order === 'asc' ) ? $result : -$result;
    }

    function prepare_items() {
        $columns  = $this->get_columns();
        $hidden   = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array( $columns, $hidden, $sortable );

        /** Process bulk action */
        $this->process_bulk_action();
        
        $per_page = $this->get_items_per_page( 'submissions_per_page', 10 );
        $current_page = $this->get_pagenum();
        $total_items = self::record_count();

        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page                     //WE have to determine how many items to show on a page
        ) );
        $this->items = self::get_submissions($per_page, $current_page);
    }

    public function process_bulk_action() {

  //Detect when a bulk action is being triggered...
  if ( 'active' === $this->current_action() ) {

    // In our file that handles the request, verify the nonce.
    $nonce = esc_attr( $_REQUEST['_wpnonce'] );

    self::change_active_submission( absint( $_GET['submission'] ) );

    wp_redirect( esc_url( add_query_arg() ) );
    exit;
  }

  // If the delete bulk action is triggered
  if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-active' )
       || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-active' )
  ) {

    $delete_ids = esc_sql( $_POST['bulk-active'] );

    // loop over the array of record IDs and delete them
    foreach ( $delete_ids as $id ) {
      self::change_active_submission( $id );

    }

    wp_redirect( esc_url( add_query_arg() ) );
    exit;
  }
}

            
} //class

function my_add_menu_items(){
    $hook = add_menu_page( 'Aanmeldingen overzicht', 'Aanmeldingen overzicht', 'activate_plugins', 'my_submissions_overview', 'my_render_list_page' );
    add_action( "load-$hook", 'add_options' );
}

function add_options() {
    global $myListTable;
    $option = 'per_page';
    $args = array(
            'label' => 'Aanmeldingen',
            'default' => 5,
            'option' => 'submissions_per_page'
            );
    add_screen_option( $option, $args );
    $myListTable = new My_submission_list();
}

add_action( 'admin_menu', 'my_add_menu_items' );

function my_render_list_page(){
    wp_enqueue_script('jquery-ui-datepicker');
    wp_register_style('jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
    wp_enqueue_style('jquery-ui');

    global $myListTable;
    echo '</pre><div class="wrap"><h2>Aanmeldingen overzicht</h2>'; 
    $myListTable->prepare_items(); 
    echo '<form action="" method="post">';
    echo '<table>';
    echo '<tr>';
    echo '<td>';
    echo '<label for="from_date" style="margin-right: 20px;">Vanaf</label>';
    echo '</td>';
    echo '<td>';
    echo '<input type="date" name="from_date" />';
    echo '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td>';
    echo '<label for="to_date" style="margin-right: 20px;">Tot</label>';
    echo '</td>';
    echo '<td>';
    echo '<input type="date" name="to_date" />';
    echo '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<td style="padding:20px;">';
    echo '<input type="submit" value="Download deelnemers in csv" name="download_participants" />';
    echo '</td>';
    echo '<td>';
    echo '<input type="submit" value="Download facturen in csv" name="download_invoices" />';
    echo '</td>';
    echo '</tr>';
    echo '</table>';
    echo '</form>';
    ?>
   
        <?php
    $myListTable->display(); 
    echo '</div>'; 
}

add_action( 'admin_init', 'convert_to_csv' );

function convert_to_csv()
{
    if (isset($_POST['download_participants']) || isset($_POST['download_invoices'])) {
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
        if(isset($_POST['download_participants'])) {
            $filenamePrefix = 'deelnemers_';
        }
        $output_file_name = $filenamePrefix . $fromDate . '_' . $toDate . '.csv';
        $delimiter = ',';

        global $wpdb;
        foreach ( $wpdb->get_col( "DESC " . 'word1_submissions', 0 ) as $column_name ) {
            $header[] = $column_name;
        }

        if (isset($_POST['download_participants'])) {
                $header[] = "participant_name";
                $header[] = "participant_email";
        }
     
        $f = fopen('php://output', 'w');
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename='.$output_file_name);
        fputcsv($f, $header, ';');
	    
        $submissions = $wpdb->get_results( "SELECT * FROM word1_submissions WHERE active < 1 OR active is NULL AND submission_date >= '" . $fromDate . "' AND submission_date <= '" . $toDate . "'");

        /** loop through array  */
        foreach ($submissions as $submission) {
            $submissionArray = (array) $submission;

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
                $submissionId = $submissionArray['id'];

                $submissionsOParticipants = $wpdb->get_results( "SELECT * FROM word1_submission_participants where invoice_id = " . $submissionId);

                foreach ($submissionsOParticipants as $participant) {
                    $participantArray = (array) $participant;
                    $lineArray = $submissionArray;
                    $lineArray[] = $participantArray['name'];
                    $lineArray[] = $participantArray['email'];           
                    /** default php csv handler **/
                    fputcsv($f, $lineArray, ';');
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