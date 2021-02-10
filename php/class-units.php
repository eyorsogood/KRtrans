<?php
/**
 * * Units Class.
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
 * Class Units
 */
class Units extends Theme {
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

    public function createUnitForm($fieldGroupId, $button = 'Submit', $redirect = null) {
        return parent::createAcfForm($fieldGroupId, 'units', $button, $redirect );
    }

    public function updateUnitForm($postid, $fieldGroupId, $button = 'Update', $redirect = null){
        return parent::updateAcfForm($postid, $fieldGroupId, $button, $redirect);
    }

    public function getUnitsList(){
        return parent::createPostQuery('units', -1);
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
            $types = array('units');

            if(!(in_array($post_values->post_type, $types))){
                return;
            }

            if($_POST['_acf_post_id'] == "new_post"){
                /**
                 * groups set values
                 */
                if($post_values->post_type == 'units'){
                    /**
                     * update post
                     */

                    $my_post = array(
                        'ID'           => $post_id,
                        'post_title'   => $_POST['acf']['field_5fc33bf70eff8']
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

                if($post_values->post_type == 'units'){
                    /**
                     * update post
                     */

                    $my_post = array(
                        'ID'           => $post_id,
                        'post_title'   => $_POST['acf']['field_5fc33bf70eff8']
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