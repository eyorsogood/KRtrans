<?php
/**
 * * Sales Class.
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
 * Class Sales
 */
class Sales extends Theme {
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
        add_action('acf/save_post', array($this, 'my_save_post2'), 5);
    }

    protected function initFilters() {
        /**
         * Place filters here
         */
    }

    public function createSaleForm($fieldGroupId, $button = 'Submit', $redirect = null) {
        return parent::createAcfForm($fieldGroupId, 'sales', $button, $redirect );
    }

    public function updateSaleForm($postid, $fieldGroupId, $button = 'Update', $redirect = null){
        return parent::updateAcfForm($postid, $fieldGroupId, $button, $redirect);
    }

    public function getSalesList(){
        return parent::createPostQuery('sales', 5, true);
    }


    public function getSalesListByDate($m, $d, $y){
        return parent::createPostQuery('sales', -1, false, array(), array('year' => $y, 'month' => $m, 'day' => $d));
    }

    public function getSalesListByDateRange($start, $end, $owner){
        if((int)$owner > 0){
            $units = parent::createQuery('units', array('key' => 'unit_owner', 'value' => $owner, 'compare' => '='));
            $metaquery = array();

            foreach($units->posts as $p):
                $metaquery[] = array(
                    'key'	 	=> 'plate_number',
                    'value'	  	=> $p->ID,
                    'compare' 	=> '='
                );
            endforeach;

            $metaquery['relation'] = 'OR';

        }else{
            $metaquery  = array();
        }

        return parent::createPostQuery('sales', -1, false, $metaquery, array('after' => $start, 'before' => $end.' 23:59:59', 'inclusive' => true));
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
            $types = array('sales');

            if(!(in_array($post_values->post_type, $types))){
                return;
            }

            if($_POST['_acf_post_id'] == "new_post"){
                /**
                 * groups set values
                 */
                if($post_values->post_type == 'sales'){
                    /**
                     * update post
                     */

                    $my_post = array(
                        'ID'           => $post_id,
                        'post_title'   => get_field('plate_number', $_POST['acf']['field_5fd1ab5a643e1']).' - '.get_field('product_name', $_POST['acf']['field_5fd1ab94643e2']),
                        'post_date'     => date('Y-m-d 00:00:00',strtotime($_POST['acf']['field_5fd1abf0643e4'])),
                        'post_date_gmt' => get_gmt_from_date( date('F d, Y',strtotime($_POST['acf']['field_5fd1abf0643e4'])) )
                    );

                    $currqty = get_field('stock_quantity', $_POST['acf']['field_5fd1ab94643e2']);
                    
                    update_field('stock_quantity', ((int)$currqty - (int)$_POST['acf']['field_5fd1abc4643e3']), $_POST['acf']['field_5fd1ab94643e2']);
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

                //unset($_POST);

                /**
                 * notifications
                 */

            }
        }
    }

    public function my_save_post2( $post_id ) {	

        if(isset($_POST['_acf_post_id'])) {
            /**
             * get post details
             */
            $post_values = get_post($post_id);


            /**
             * bail out if not a custom type and admin
             */
            $types = array('sales');

            if(!(in_array($post_values->post_type, $types))){
                return;
            }

            if($_POST['_acf_post_id'] == "new_post"){
                /**
                 * groups set values
                 */
                

                /**
                 *  Clear POST data
                 */
                //unset($_POST);

                /**
                 * notifications
                 */
         
            }
            else if($_POST['_acf_post_id'] == $post_id) {

                /**
                 *  Clear POST data
                 */

                if($post_values->post_type == 'sales'){
                    /**
                     * update post
                     */
                    
                    $my_post = array(
                        'ID'           => $post_id,
                        'post_title'   => get_field('plate_number', $_POST['acf']['field_5fd1ab5a643e1']).' - '.get_field('product_name', $_POST['acf']['field_5fd1ab94643e2']),
                        'post_date'     => date('Y-m-d 00:00:00',strtotime($_POST['acf']['field_5fd1abf0643e4'])),
                        'post_date_gmt' => get_gmt_from_date( date('F d, Y',strtotime($_POST['acf']['field_5fd1abf0643e4'])) )
                    );

                    $currqty = get_field('stock_quantity', $_POST['acf']['field_5fd1ab94643e2']);
                    $oldsaleqty = get_field('quantity', $post_id);
                    
                    if((int)$oldsaleqty > (int)$_POST['acf']['field_5fd1abc4643e3']){
                        $finalqty = ((int)$oldsaleqty - (int)$_POST['acf']['field_5fd1abc4643e3']);
                        $finalqty = $finalqty + (int)$currqty;
                    }else{
                        $finalqty = ((int)$_POST['acf']['field_5fd1abc4643e3'] - (int)$oldsaleqty);
                        $finalqty = (int)$currqty - $finalqty;
                    }
                    
                    update_field('stock_quantity', $finalqty, $_POST['acf']['field_5fd1ab94643e2']);
                    update_field('quantity', (int)$_POST['acf']['field_5fd1abc4643e3'], $post_id);

                    wp_update_post( $my_post );
                }
                
                //unset($_POST);

                /**
                 * notifications
                 */

            }
        }
    }
}

?>