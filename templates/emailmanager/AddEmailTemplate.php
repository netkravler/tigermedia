<div class="tab-pane <?php echo isset($_POST['manager']) && $_POST['manager'] == 'gestus_email_manager' ? 'active': '';?>" id="tab-3">

<form method="POST" action="options.php" autocomplete="off">

<input type="hidden" name="manager" value="gestus_email_manager">

   <?php 

                echo $send_posttype = $_POST['send_posttype'] == 'applicants' ? 'Ansøgere' : $_POST['send_posttype'];

                echo '<h2>Opret E-mail skabelon for <u>'. $_POST['mailtype'].'</u></h2>'; 
               


 

        $emailtags = get_option('email_scaffold');

        settings_fields( 'gestus_email_settings' );
        do_settings_sections( 'gestus_email_manager' );

           /* if (isset($_COOKIE["posttype"])) 
                echo $_COOKIE["posttype"]; 
                else 
                echo "Cookie Not Set";*/
            echo '<table class="form-table" role="presentation">
            <tbody><tr>
                <th scope="row">Use email tags in textarea</th>
                <td>
                <div style="display: flex; justify-content: space-between">';

               ///var_dump( $_COOKIE["posttype"]);

                        foreach($emailtags[$_POST['send_posttype']] as $tag => $value)
                {

                    ?>
                     
                    
                    <a  class="button button-info" onclick="tinymce.activeEditor.execCommand('mceInsertContent', false, `<?php echo '**' . $value . '**' ?>`);" ><?php echo $value  ?></a>

                    <?php
                }
                
               echo '</div></td></tr><tr><th scope="row"></th><td><div>';
    
               submit_button('Gem ændringer', 'primary ', 'submit_email_manager', false);
                
                echo '</div></td></tr></tbody></table>';


    ?>

</form>

</div>

