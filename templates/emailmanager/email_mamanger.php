<?php $output = get_option('use_mails_on_posttype'); 




?>
<style>



.hide{

    display: none
}
</style>

<script>

document.body.onclick= function(e){
   e=window.event? event.srcElement: e.target;
   if(e.className  && e.type == 'checkbox' && e.className.indexOf('hide_pages')!=-1){
   
console.log(e.type);

   document.querySelectorAll(".individual_pages").forEach( function(element) {
        //Toggler class mellem show og hide 
        element.classList.toggle('hide');
    });
}
}


</script>


<article class="wrap">
<h1>email manager</h1>

<ul class="nav nav-tabs">
        <!--All Emails Template tab-->
        <li class="<?php echo !isset($_POST['manager']) ? 'active': '';?>" ><a href="#tab-1">Alle E-mail skabeloner</a></li>
       
       <?php if(!empty($output)){?>

        <li class="<?php echo isset($_POST['manager']) && $_POST['manager'] == 'set_email_type' ? 'active': '';?>"><a href="#tab-2" ><?php echo !isset($_POST['edit_mail_type']) ? 'Tilføj email type': 'Rediger email type';?> </a></li>
 
       <?php }?>
      
       <?php if( isset($_POST['manager']) && $_POST['manager'] == 'gestus_email_manager'){?>
        <li class="active"><a href="#tab-3" ><?php echo !isset($_POST['edit_field']) ? 'Tilføj email skabelon': 'Rediger email skabelon';?> </a></li>
       <?php }?>
      
        <?php if(check_username()){?>
        <li ><a href="#tab-4" >Accepter emails på disse typer</a></li>
        <?php }?>
    </ul>

    <?php 
        //show All E-mail Templates
        if ( file_exists( dirname ( __FILE__ ) . '/AllEmailTemplates.php'))
        {

            require_once dirname( __FILE__) . '/AllEmailTemplates.php';
                
        }
        ?>

    <?php 
        
        if ( file_exists( dirname ( __FILE__ ) . '/AddEmailType.php'))
        {

            require_once dirname( __FILE__) . '/AddEmailType.php';
                
        }
        ?>

<?php 
        
        if ( file_exists( dirname ( __FILE__ ) . '/AddEmailTemplate.php') && isset($_POST['manager']) && $_POST['manager'] == 'gestus_email_manager')
        {

            require_once dirname( __FILE__) . '/AddEmailTemplate.php';
                
        }
        ?>

<?php 
        
        if ( file_exists( dirname ( __FILE__ ) . '/AcceptedPostTypes.php') && check_username())
        {

            require_once dirname( __FILE__) . '/AcceptedPostTypes.php';
                
        }
        ?>


<?php

function check_username()
{

    global $current_user; wp_get_current_user();
    $user = wp_get_current_user();

    if($user && isset($user->user_login) && 'netkravler' == $user->user_login) {
        
        return true;
    }
}
?>


</div>

</article>
