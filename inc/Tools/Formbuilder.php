<?php

namespace Inc\Tools;

use Inc\Base\BaseController;

class Formbuilder extends BaseController
{




    public function form_builder($args){

  
        
        foreach($args as $elem => $value){


            $type = isset($args[$elem]['type']) ? $args[$elem]['type'] : '';
            $label = isset($args[$elem]['label']) ? $args[$elem]['label'] : '';
            $name = isset($args[$elem]['name']) ? $args[$elem]['name'] : '';
            $id = isset($args[$elem]['id']) ? $args[$elem]['id'] : $name;
            $placeholder = isset($args[$elem]['placeholder']) ? $args[$elem]['placeholder'] : '';
            $required = isset($args[$elem]['required']) ? 'required='.$args[$elem]['required'] : '';
            $data_type = isset($args[$elem]['data_type']) ? 'data-type='.$args[$elem]['data_type'] : '';
            $error_empty = isset($args[$elem]['error_empty']) ? $args[$elem]['error_empty'] : '';
            $error_wrong = isset($args[$elem]['error_wrong']) ? $args[$elem]['error_wrong'] : '';
            $script = isset($args[$elem]['script']) ? $args[$elem]['script'] : '';
            $icon = isset($args[$elem]['icon']) ? $args[$elem]['icon'] : '';
        
        ?>
        
        <div class="field-container">
            <label for="<?php echo $name?>"><?php echo $label?></label>
            <div class="icon_container">
            <?php echo $icon?></div> 
            <input type="<?php echo $type ?>" name="<?php echo $name?>" id="<?php echo $id?>" placeholder="<?php echo $placeholder?>" <?php echo $required?> <?php echo $data_type?> class="field-input" <?php echo $script?> >

            <small class="field-msg error" data-error="invalid<?php echo ucwords($name)?>"><?php echo $error_empty?></small>
            <small class="field-msg_wrong error" data-error="wrongformat<?php echo ucwords($name)?>"><?php echo $error_wrong?></small>
        </div>
        
        <?php
        }
    }
}
