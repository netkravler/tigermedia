<?php
/**
 * 
 * @package gestus_nord
 */

namespace Inc ;

final class Init
{

    /**
     * return all classes inside array
     * @return array Full list of classes
     */
    public static function get_services(){

        return [

            Api\Widgets\MediaWidget::class,
            Pages\Dashboard::class,
            Base\ApplicantsController::class,            
            Base\TeenController::class,            
            Base\ManagementController::class,
            Base\VolunteerController::class,
            Base\AdminMenuController::class,
            Base\Enqueue::class,
            Base\CustomWidget::class,
            Base\Shortcodes::class,
            Base\CustomPostTypeController::class,
            Base\TaxonomyController::class,
            Base\WidgetController::class,
            Base\TestamonialController::class, 
            Base\PartnerController::class, 
            Base\ErhvervsPartnerController::class, 
            Base\TemplateController::class,
            Base\AidController::class,
            
            Base\NewsController::class,

            
            Base\VolunteerFormController::class,
            Base\ResponseMailController::class,
            Base\PageSettingsController::class,
            //Base\RoleController::class,
            Tools\ImgCompression::class,
            Tools\GetMetaByKey::class,
            Tools\Tools::class,
            Base\AuctionController::class,
            Base\Add_Fields_User_Registraion::class,
            Base\MailController::class,
            Base\AddNewUser::class,
            Tools\Session::class,
            Base\ControllerTypeMailTags::class,
            Base\TestController::class,
            Base\AddEmailsToPosttype::class,
            Base\testMailSender::class,
            Base\QueryCounterController::class,
            Base\PickupController::class,

            Base\ActivitySettings::class,
            


        ];
    }

    /**
     * Loop through all the classes, initialize them, 
     * and call register method if it exists
     * @return 
     */
    public static function register_services(){

        foreach ( self::get_services() as $class){

            $service = self::instantiate( $class);

            if  ( method_exists( $service, 'register' ) ){

                $service->register();

            }

        }


    }

    /**
     * 
     * Initialize the class
     * @param class $class class from the services array
     * @return class instance new instance of the class
     * 
     */
    private static function instantiate( $class ){

        $service = new $class();

        return $service;


    }
}

