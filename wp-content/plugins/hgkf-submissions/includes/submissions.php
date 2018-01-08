<?php

class My_submission_list extends WP_List_Table
{
    function __construct()
    {
        global $status, $page;
        parent::__construct(array(
            'singular' => __('aanmelding', 'mylisttable'),
            'plural' => __('aanmeldingen', 'mylisttable'),
            'ajax' => false
        ));
        add_action('admin_head', array($this, 'admin_header'));
    }

    function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'number':
            case 'active':
            case 'submission_type':
            case 'submission_date':
            case 'organization':
            case 'firstname':
            case 'lastname':
            case 'price':
            case 'price_tax':
            case 'parking_tickets':
            case 'reduction_code':
            case 'notes':
                return $item[$column_name];
            default:
                return print_r($item, true); //Show the whole array for troubleshooting purposes
        }
    }

    function no_items()
    {
        _e('Geen aanmeldingen gevonden.');
    }

    function column_active($item)
    {
        $actions = array(
            'active' => sprintf('<a href="?page=%s&action=%s&submission=%s">Verander status</a>', $_REQUEST['page'], 'active', $item['id'])
        );

        return sprintf('%1$s %2$s', $item['active'], $this->row_actions($actions));
    }

    function admin_header()
    {
        $page = (isset($_GET['page'])) ? esc_attr($_GET['page']) : false;
        if ('my_submissions_overview' != $page)
            return;
        echo '<style type="text/css">';
        echo '.wp-list-table .column-id { width: 5%; }';
        //  echo '.wp-list-table .column-invoice_number { width: 40%; }';
        //  echo '.wp-list-table .column-submission_type { width: 35%; }';
        // echo '.wp-list-table .column-submission_date { width: 20%;}';
        echo '</style>';
    }

    function usort_reorder($a, $b)
    {
// If no sort, default to title
        $orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'invoice_number';
// If no order, default to asc
        $order = (!empty($_GET['order'])) ? $_GET['order'] : 'asc';
// Determine sort order
        $result = strcmp($a[$orderby], $b[$orderby]);
// Send final sort direction to usort
        return ($order === 'asc') ? $result : -$result;
    }

    function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);

        /** Process bulk action */
        $this->process_bulk_action();

        $per_page = $this->get_items_per_page('submissions_per_page', 10);
        $current_page = $this->get_pagenum();
        $total_items = self::record_count();

        $this->set_pagination_args(array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page' => $per_page                     //WE have to determine how many items to show on a page
        ));
        $this->items = self::get_submissions($per_page, $current_page);
    }

    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'number' => __('Factuur nummer', 'mylisttable'),
            'active' => __('Negeren in export', 'mylisttable'),
            'submission_type' => __('Type aanmelding', 'mylisttable'),
            'submission_date' => __('Inzend datum', 'mylisttable'),
            'organization' => __('Organisatie', 'mylisttable'),
            'firstname' => __('Voornaam', 'mylisttable'),
            'lastname' => __('Achternaam', 'mylisttable'),
            'price' => __('Prijs excl. Btw', 'mylisttable'),
            'price_tax' => __('Prijs incl. Btw', 'mylisttable'),
            'parking_tickets' => __('Parkeertickets', 'mylisttable'),
            'reduction_code' => __('Kortingscode', 'mylisttable'),
            'notes' => __('Opmerkingen', 'mylisttable')
        );
        return $columns;
    }

    function get_sortable_columns()
    {
        $sortable_columns = array(
            'invoice_number' => array('invoice_number', false),
            'submission_type' => array('submission_type', false),
            'submission_date' => array('submission_date', false)
        );
        return $sortable_columns;
    }

    public function process_bulk_action()
    {

//Detect when a bulk action is being triggered...
        if ('active' === $this->current_action()) {

// In our file that handles the request, verify the nonce.
            $nonce = esc_attr($_REQUEST['_wpnonce']);

            self::change_active_submission(absint($_GET['submission']));

            wp_redirect(esc_url(add_query_arg()));
            exit;
        }

// If the delete bulk action is triggered
        if ((isset($_POST['action']) && $_POST['action'] == 'bulk-active')
            || (isset($_POST['action2']) && $_POST['action2'] == 'bulk-active')
        ) {

            $delete_ids = esc_sql($_POST['bulk-active']);

// loop over the array of record IDs and delete them
            foreach ($delete_ids as $id) {
                self::change_active_submission($id);

            }

            wp_redirect(esc_url(add_query_arg()));
            exit;
        }
    }

    /**
     * Change the active status a submission in the export for exact
     *
     * @param int $id submission id
     */
    public static function change_active_submission($id)
    {
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
    public static function record_count()
    {
        global $wpdb;

        $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}submissions";

        return $wpdb->get_var($sql);
    }

    /**
     * Retrieve submission data from the database
     *
     * @param int $per_page
     * @param int $page_number
     *
     * @return mixed
     */
    public static function get_submissions($per_page = 5, $page_number = 1)
    {
        global $wpdb;
        $sql = "SELECT invoice.number, submission.active, submission.submission_type, submission.submission_date, submission.organization, invoice.firstname, invoice.lastname, submission.price, submission.price_tax, submission.parking_tickets, submission.reduction_code, submission.notes FROM {$wpdb->prefix}submissions AS submission INNER JOIN {$wpdb->prefix}submission_invoices AS invoice ON invoice.submission_id = submission.submission_id";

        if (!empty($_REQUEST['orderby'])) {
            $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
            $sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
        }

        $sql .= " LIMIT $per_page";

        $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;


        $result = $wpdb->get_results($sql, 'ARRAY_A');
        $submissionCollection = $result;
        return $result;
    }
} //class
