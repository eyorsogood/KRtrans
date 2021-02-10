<?php
/**
 * * Expenses Class.
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
 * Class Expenses
 */
class Expenses extends Theme {
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
    }

    public function createExpenseForm($fieldGroupId, $button = 'Submit', $redirect = null) {
        return parent::createAcfForm($fieldGroupId, 'expenses', $button, $redirect );
    }

    public function updateExpenseForm($postid, $fieldGroupId, $button = 'Update', $redirect = null){
        return parent::updateAcfForm($postid, $fieldGroupId, $button, $redirect);
    }

    public function getExpensesList(){
        return parent::createPostQuery('expenses', 5, true);
    }


    public function getExpensesListByDate($m, $d, $y){
        return parent::createPostQuery('expenses', -1, false, array(), array('year' => $y, 'month' => $m, 'day' => $d));
    }

    public function getExpensesListByDateRange($start, $end, $owner){
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

        return parent::createPostQuery('expenses', -1, false, $metaquery, array('after' => $start, 'before' => $end.' 23:59:59', 'inclusive' => true));
    }

    public function convertNumber($num){
        return number_format($num, 2, '.', ',');
    }

    public function getUnitOwnerList() {
        return parent::createPostQuery('owners', -1);
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
            $types = array('expenses');

            if(!(in_array($post_values->post_type, $types))){
                return;
            }

            if($_POST['_acf_post_id'] == "new_post"){
                /**
                 * groups set values
                 */
                if($post_values->post_type == 'expenses'){
                    /**
                     * update post
                     */

                    $my_post = array(
                        'ID'           => $post_id,
                        'post_title'   => get_field('plate_number', $_POST['acf']['field_5fd586eb74397']).' - '.$_POST['acf']['field_5fd5870274398'].' - '.$_POST['acf']['field_5fd5872574399']
                    );

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

                if($post_values->post_type == 'expenses'){
                    /**
                     * update post
                     */

                    $my_post = array(
                        'ID'           => $post_id,
                        'post_title'   => get_field('plate_number', $_POST['acf']['field_5fd586eb74397']).' - '.$_POST['acf']['field_5fd5870274398'].' - '.$_POST['acf']['field_5fd5872574399']
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