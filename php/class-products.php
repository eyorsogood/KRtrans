<?php
/**
 * * Products Class.
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
 * Class Products
 */
class Products extends Theme {
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

    public function createProductForm($fieldGroupId, $button = 'Submit', $redirect = null) {
        return parent::createAcfForm($fieldGroupId, 'products', $button, $redirect );
    }

    public function updateProductForm($postid, $fieldGroupId, $button = 'Update', $redirect = null){
        return parent::updateAcfForm($postid, $fieldGroupId, $button, $redirect);
    }

    public function getProductsList(){
        return parent::createPostQuery('products', -1);
    }

    public function getProductOverallSoldCount($prodid) {
        $query = parent::createPostQuery('sales', -1, false, array(array('key' => 'product', 'value' => $prodid, 'compare' 	=> '=')));

        $sold = 0;

        foreach($query[0] as $s):
            $sold += (int)$s['quantity'];
        endforeach;

        return $sold;
    }


    public function getProductsListByDate($m, $d, $y){
        return parent::createPostQuery('products', -1, false, array(), array('year' => $y, 'month' => $m, 'day' => $d));
    }

    public function getProductsListByDateRange($start, $end){
        return parent::createPostQuery('products', -1, false, array(), array('after' => $start, 'before' => $end.' 23:59:59', 'inclusive' => true));
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
            $types = array('products');

            if(!(in_array($post_values->post_type, $types))){
                return;
            }

            if($_POST['_acf_post_id'] == "new_post"){
                /**
                 * groups set values
                 */
                if($post_values->post_type == 'products'){
                    /**
                     * update post
                     */

                    $my_post = array(
                        'ID'           => $post_id,
                        'post_title'   => $_POST['acf']['field_5fd197596730b']
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

                if($post_values->post_type == 'products'){
                    /**
                     * update post
                     */

                    $my_post = array(
                        'ID'           => $post_id,
                        'post_title'   => $_POST['acf']['field_5fd197596730b']
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