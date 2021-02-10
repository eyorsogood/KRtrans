<?php
/**
 * * Gasolines Class.
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
 * Class Gasolines
 */
class Gasolines extends Theme {
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

    public function getPreDateRangeString(){
        $string = (isset($_GET['start']) && isset($_GET['end']))?'?start='.$_GET['start'].'&end='.$_GET['end']:'?flag=1';

        return $string;
    }

    public function createGasolineForm($fieldGroupId, $button = 'Submit', $redirect = null) {
        return parent::createAcfForm($fieldGroupId, 'gasolines', $button, $redirect );
    }

    public function updateGasolineForm($postid, $fieldGroupId, $button = 'Update', $redirect = null){
        return parent::updateAcfForm($postid, $fieldGroupId, $button, $redirect);
    }

    public function getGasolinesList(){
        return parent::createPostQuery('gasolines', -1);
    }

    public function getGasolinesListByDate($m, $d, $y){
        return parent::createPostQuery('gasolines', -1, false, array(), array('year' => $y, 'month' => $m, 'day' => $d));
    }

    public function getOdoListByDate($m, $d, $y){
        return parent::createPostQuery('odometers', -1, false, array(), array('year' => $y, 'month' => $m, 'day' => $d));
    }

    public function getGasolinesListByDateRange($start, $end){
        return parent::createPostQuery('gasolines', -1, false, array(), array('after' => $start, 'before' => $end.' 23:59:59', 'inclusive' => true));
    }

    public function getOdoListByDateRange($start, $end){
        return parent::createPostQuery('odometers', -1, false, array(), array('after' => $start, 'before' => $end.' 23:59:59', 'inclusive' => true));
    }

    public function getGasolineFormToShow(){
        if((isset($_GET['start']) && isset($_GET['end']))){
            $start = $_GET['start'];
            $end = $_GET['end'];

            $gasoline = parent::createPostQuery('gasolines', 1, false, array(), array('after' => $start, 'before' => $end.' 23:59:59', 'inclusive' => true));
        }else{
            $gasoline = parent::createPostQuery('gasolines', 1, false, array(), array('year' => date("Y"), 'month' => date("m"), 'day' => date("d")));
        }

        if(count($gasoline[0]) > 0){
            foreach($gasoline[0] as $id => $fields){
                return $this->updateGasolineForm($id, 147, 'Update Gasoline Sales', 'daily-gasoline-sales/'.$this->getPreDateRangeString());
                break;
            }
        }else{
            return $this->createGasolineForm(147, 'Update Gasoline Sales', 'daily-gasoline-sales');
        }
    }

    public function convertNumber($num){
        return number_format($num, 2, '.', ',');
    }

    public function getOdoFormToShow(){
        if((isset($_GET['start']) && isset($_GET['end']))){
            $start = $_GET['start'];
            $end = $_GET['end'];
            
            $odo = parent::createPostQuery('odometers', 1, false, array(), array('after' => $start, 'before' => $end.' 23:59:59', 'inclusive' => true));
        }else{
            $odo = parent::createPostQuery('odometers', 1, false, array(), array('year' => date("Y"), 'month' => date("m"), 'day' => date("d")));
        }

        if(count($odo[0]) > 0){
            foreach($odo[0] as $id => $fields){
                return $this->updateGasolineForm($id, 134, 'Update ODO Meter Values', 'daily-gasoline-sales/'.$this->getPreDateRangeString());
                break;
            }
        }else{
            return parent::createAcfForm(134, 'odometers', 'Update ODO Meter Values', 'daily-gasoline-sales' );
        }
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
            $types = array('gasolines', 'odometers');

            if(!(in_array($post_values->post_type, $types))){
                return;
            }

            if($_POST['_acf_post_id'] == "new_post"){
                /**
                 * groups set values
                 */
                if($post_values->post_type == 'gasolines'){
                    /**
                     * update post
                     */

                    $my_post = array(
                        'ID'           => $post_id,
                        'post_title'   => date("m").'-'.date("d").'-'.date("Y")
                    );

                    wp_update_post( $my_post );
                }

                if($post_values->post_type == 'odometers'){
                    /**
                     * update post
                     */

                    $my_post = array(
                        'ID'           => $post_id,
                        'post_title'   => date("m").'-'.date("d").'-'.date("Y")
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
                unset($_POST);

                /**
                 * notifications
                 */

            }
        }
    }
}

?>