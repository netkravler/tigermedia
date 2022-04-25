<?php

namespace Inc\Api\Callbacks ;

use Inc\Base\BaseController;

class AdminCallbacks extends BaseController
{

 /**
 * 
 * managers
 */  

    public function adminDashboard(){

        return require_once ( "$this->plugin_path/templates/admin/admin.php");

    }

    public function managementManager(){

        return require_once ( "$this->plugin_path/templates/management/management.php");

    }

    public function volunteerManager(){

        return require_once ( "$this->plugin_path/templates/volunteer/volunteers.php");

    }


    public function postTypeManager(  ){



        return require_once ( "$this->plugin_path"."templates/post_types.php");

    }

   
    public function queryCounter(  ){




        return require_once ( "$this->plugin_path"."templates/queryCounter.php");

        echo '<script src="'.$this->plugin_path.'assets/common/print/print-min.js"></stript>';

    } 


    public function taxonomyManager(  ){



        return require_once ( "$this->plugin_path"."templates/taxonomy.php");

    }

    public function widgetManager(  ){



        return require_once ( "$this->plugin_path"."templates/widgets.php");

    }



    public function testamonialManager(  ){



        return require_once ( "$this->plugin_path"."templates/testamonials/testamonial.php");

    }

    public function templateManager(  ){



        return require_once ( "$this->plugin_path"."templates/templates.php");

    }


    public function NewsManager(  ){



        return require_once ( "$this->plugin_path"."templates/news.php");

    }

    public function emailManager()
    {

        return require_once ( "$this->plugin_path"."templates/emailmanager/email_mamanger.php");

    }

    /**
     * managers ends
     * 
     */

    public function settingsOptionsGroup( $input )
    {
       
        return $input;
    }

    public function sectionsOptionsGroup(){

        echo 'Aktiver div controllers';
    }

    public function sectionsOptionsGroup_settings(){

        echo 'Definer om aktiviteterne kræver specielle egenskaber';
    }

    public function pagesettings(){

        echo 'Indstillinger for elementer på siden';
    }

    public function settingsTextExample( $args )
    {


        $value = esc_attr( get_option('text_example'));

        echo '<input type="text" class="regular-text" id="text_example" name="text_example" value="'. $value .'" placeholder="Skriv et eller andet her">';
    }


    public function settingsNextTextExample( $args )
    {

        //var_dump($args['input_arg']['type']);

        $value = esc_attr( get_option('next_example'));


        switch($args['input_arg']['type']){

            default :
                echo '<input type="text" class="regular-text" id="next_example" name="next_example" value="'. $value .'" placeholder="Skriv et eller andet her">';
            break;

            case 'email':
                echo '<input type="email" class="regular-text" id="next_example" name="next_example" value="'. $value .'" placeholder="Skriv et eller andet her">';
            break;    
        }





    }

}
