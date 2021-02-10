<?php
/**
 * * Rentals Class.
 *
 * @author    eyorsogood.com, Rouie Ilustrisimo
 * @package   Eyorsogood
 * @version   1.0.0
 */

/**
 * No direct access to this file.
 *
 * @since 1.0.0
 */
defined( 'ABSPATH' ) || die();

/**
 * Class Rentals
 */
class Rentals extends Theme {
    public function __construct( array $config = array() ) {
        $this->initActions();
        $this->initFilters();
    }

    protected function initActions() {
        /**
         * 
         * function should be public when adding to an action hook.
         */        

        add_action('acf/save_post', array($this, 'my_save_post'));
    }

    protected function initFilters() {
        /**
         * Place filters here
         */

        add_filter('acf/fields/post_object/query/key=field_5fc37a05c4858', array($this, 'platenumber_filter'), 10, 3);
        add_filter('acf/fields/post_object/query/key=field_5fc37b9ddfdfa', array($this, 'driver_filter'), 10, 3);
    }

    public function platenumber_filter($args, $field, $post_id ) {

        // query existing posts for matching value
        $q = array(
            'post_type' => 'rentals',
            'posts_per_page' => -1, 
            'post_status' => 'publish',
            'date_query'  => array('year' => date("Y"), 'month' => date("m"), 'day' => date("d"))
        );

        $query = new WP_Query($q);
        $arr = array();

        foreach($query->posts as $p):
            $arr[] = get_field('plate_number', $p->ID);
        endforeach;

        $args['post__not_in'] = $arr;

        return $args;
    }

    public function driver_filter($args, $field, $post_id ) {

        // query existing posts for matching value
        $q = array(
            'post_type' => 'rentals',
            'posts_per_page' => -1, 
            'post_status' => 'publish',
            'date_query'  => array('year' => date("Y"), 'month' => date("m"), 'day' => date("d"))
        );

        $query = new WP_Query($q);
        $arr = array();

        foreach($query->posts as $p):
            $arr[] = get_field('driver', $p->ID);
        endforeach;

        $args['post__not_in'] = $arr;

        return $args;
    }

    public function getPreDateRangeString(){
        $string = (isset($_GET['start']) && isset($_GET['end']))?'?start='.$_GET['start'].'&end='.$_GET['end']:'?flag=1';

        return $string;
    }

    public function createRentalForm($fieldGroupId, $button = 'Submit', $redirect = null) {
        return parent::createAcfForm($fieldGroupId, 'rentals', $button, $redirect );
    }

    public function updateRentalForm($postid, $fieldGroupId, $button = 'Update', $redirect = null){
        return parent::updateAcfForm($postid, $fieldGroupId, $button, $redirect);
    }

    public function getRentalsList(){
        return parent::createPostQuery('rentals', -1);
    }

    public function getAllUnitsList(){
        return parent::createPostQuery('units', -1);
    }

    public function getRentalsListByDate($m, $d, $y){
        return parent::createPostQuery('rentals', -1, false, array(), array('year' => $y, 'month' => $m, 'day' => $d));
    }

    public function getRentalsListByDateRange($start, $end){
        return parent::createPostQuery('rentals', -1, false, array(), array('after' => $start, 'before' => $end.' 23:59:59', 'inclusive' => true));
    }

    public function convertNumber($num){
        return number_format($num, 2, '.', ',');
    }

    public function my_save_post( $post_id ) {	

        if(isset($_POST['_acf_post_id'])) {
            /**
             * get post details
             */
            $post_values = get_post($post_id);


            /**
             * bail out if not a custom type and admin
             */
            $types = array('rentals');

            if(!(in_array($post_values->post_type, $types))){
                return;
            }

            if($_POST['_acf_post_id'] == "new_post"){
                /**
                 * groups set values
                 */
                if($post_values->post_type == 'rentals'){
                    /**
                     * update post
                     */

                    $time = (isset($_GET['start']) && isset($_GET['end']))?$_GET['start']:false;
                    
                    if($time){
                        $my_post = array(
                            'ID'           => $post_id,
                            'post_title'   => get_field('plate_number', $_GET['id']).' - '.get_field('driver_name', $_POST['acf']['field_5fc37b9ddfdfa']),
                            'post_date'     => date('Y-m-d 00:00:00',strtotime($time)),
                            'post_date_gmt' => get_gmt_from_date( date('F d, Y',strtotime($time)) )
                        );
                    }else{
                        $my_post = array(
                            'ID'           => $post_id,
                            'post_title'   => get_field('plate_number', $_GET['id']).' - '.get_field('driver_name', $_POST['acf']['field_5fc37b9ddfdfa'])
                        );
                    }

                    

                    update_field('plate_number', $_GET['id'], $post_id);
                    wp_update_post( $my_post );
                }

                /**
                 *  Clear POST data
                 */
                unset($_POST);

                /**
                 * notifications
                 */
         
            }
            else if($_POST['_acf_post_id'] == $post_id) {

                /**
                 *  Clear POST data
                 */

                if($post_values->post_type == 'rentals'){
                    /**
                     * update post
                     */

                    $my_post = array(
                        'ID'           => $post_id,
                        'post_title'   => get_field('plate_number', $_POST['acf']['field_5fc37a05c4858']).' - '.get_field('driver_name', $_POST['acf']['field_5fc37b9ddfdfa'])
                    );

                    wp_update_post( $my_post );
                }
                
                unset($_POST);

                /**
                 * notifications
                 */

            }
        }
    }
}

?>