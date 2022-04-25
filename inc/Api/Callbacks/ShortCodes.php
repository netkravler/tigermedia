<?php

namespace Inc\Api\Callbacks ;

use Inc\Base\BaseController;

class ShortCodes extends BaseController
{

    public function volun_shortcodes($args)
    {

       
       return require_once ("$this->plugin_path/templates/volunteer/volunteers.php");
       
    }

    public function testimony_shortcodes()
    {

       return require_once ("$this->plugin_path/templates/testamonials/testamonial.php");
    }


    public function part_nershortcodes()
    {

       return require_once ("$this->plugin_path/templates/partners/partners.php");
    }

    public function teenshortcodes()
    {

       return require_once ("$this->plugin_path/templates/teens/teens.php");
    }


    public function erhvervs_partnershortcodes()
    {

       return require_once ("$this->plugin_path/templates/partners/erhvervspartners.php");
    }

    public function Management_shortcodes()
    {
       
       return require_once ("$this->plugin_path/templates/management/management.php");
    }

}