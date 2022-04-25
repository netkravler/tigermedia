<div class="wrap">
    <h1>Gestus nord indstillinger</h1>

    <?php settings_errors();?>

    <ul class="nav nav-tabs">
        <li class="<?php echo !$_POST || $_POST['manager'] ? 'active': '';?>"><a href="#tab-1">Aktiver managers</a>
        </li>
        <li class="<?php echo isset($_POST['edit_activity']) ? 'active': '';?>"><a href="#tab-2">Indstillinger for
                aktiviteter</a></li>

        <?php
            $adm_option = get_option('gestus_nord_admin');

            if($adm_option['page_settings_manager'] ){?>

        <li class="<?php echo isset($_POST['edit_settings']) ? 'active': '';?>"><a href="#tab-3">Side indstillinger</a>
        </li>
        <li class="<?php echo isset($_POST['edit_menu']) ? 'active': '';?>"><a href="#tab-4">Admin Menuer</a></li>
        <?php }?>

        <li><a href="#tab-5">Opdateringer</a></li>
       <!-- <li><a href="#tab-6">Auktions indstillinger</a></li>-->
        <li><a href="#tab-7">Om gestus nord</a></li>
    </ul>

    <div class="tab-content ">


        <?php 
        
                if ( file_exists( dirname ( __FILE__ ) . '/managers.php'))
            {

                require_once dirname( __FILE__) . '/managers.php';
            
            }
            ?>




        <?php 
        
            if ( file_exists( dirname ( __FILE__ ) . '/activities.php'))
            {

                require_once dirname( __FILE__) . '/activities.php';
            
            }
            ?>





        <?php 
        
            if ( file_exists( dirname ( __FILE__ ) . '/page_settings.php'))
            {

                require_once dirname( __FILE__) . '/page_settings.php';
            
            }
            ?>





        <?php 
            
            if ( file_exists( dirname ( __FILE__ ) . '/admin_menu_settings.php'))
            {

                 require_once dirname( __FILE__) . '/admin_menu_settings.php';
                
            }
            ?>


        <?php 
                    
            if ( file_exists( dirname ( __FILE__ ) . '/updates.php'))
            {

                require_once dirname( __FILE__) . '/updates.php';
                    
            }
            ?>


        <?php 
        
            if ( file_exists( dirname ( __FILE__ ) . '/about.php'))
            {

                require_once dirname( __FILE__) . '/about.php';
            
            }
            ?>

<?php 
        
        if ( file_exists( dirname ( __FILE__ ) . '/auctions.php'))
        {

            //require_once dirname( __FILE__) . '/auctions.php';
        
        }
        ?>


    </div>

</div>