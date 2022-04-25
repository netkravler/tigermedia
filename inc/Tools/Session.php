<?php
/**
 * Created by PhpStorm.
 * User: Miv
 * Date: 26-12-2018
 * Time: 21:05
 */
namespace Inc\Tools;

class Session
{
    public static function exists($name){
        // check if requested session exsist, return if so and false if not
        return (isset($_SESSION[$name])) ? true : false;
    }
    public static function put($name,$value){
// create a sission with the name that is set
        return $_SESSION[$name] = $value;
    }

    public static function get($name){
        // get the requested sessiob
        return $_SESSION[$name];
    }

    public static function delete($name){

        if(self::exists($name)){
// if the requested function exist it will be deleted
            unset($_SESSION[$name]);
        }
    }
// create a flash message function
    public static function flash($name, $string = ''){
        // Falsh session of the name in request exist
        if(self::exists($name)){

            $session = self::get($name);
          //  delete the flashsession so the following return only works ons
            self::delete($name);

            // return the flashsession
            return $session;
        }else{
            // if the flashsession does not exist create it.
            self::put($name, $string);
        }

    }
}
