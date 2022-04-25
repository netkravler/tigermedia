<?php

namespace Inc\Base;


use Inc\Base\BaseController;

use WP_Error;

class Add_Fields_User_Registraion extends BaseController
{

    public $first_name_label;
    public $billing_company_label;
    public $billing_email_label;
    public $billing_phone_label;
    public $accept_label;

    public $user_password_label;

    public $confirm_user_password_label;


public function register()
{

    $this->first_name_label = 'Dit navn';

    $this->billing_company_label = 'Firmanavn';

    $this->billing_email_label = 'Fakturerings E-mail';

    $this->billing_phone_label = 'Telefonnummer';

    $this->accept_label = 'JA TAK - jeg vil gerne kontaktes med henblik på at blive erhvervspartner (koster 2000 kr. ekskl. moms årligt)';

    $this->user_password_label = 'Password';

    $this->confirm_user_password_label = 'Bekræft Password';


   // add_action( 'register_form', array($this, 'crf_registration_form' ));



    add_action( 'user_register', array( $this, 'crf_user_register' ));

    add_action( 'edit_user_created_user', array($this,'crf_user_register' ));


    add_action( 'show_user_profile', array( $this, 'crf_show_extra_profile_fields' ));
    add_action( 'edit_user_profile', array( $this,  'crf_show_extra_profile_fields' ));

    
    add_action( 'user_new_form', array($this, 'crf_admin_registration_form' ));


  //  add_action('user_register', array($this, 'myplugin_set_pass'));

    add_filter( 'registration_errors', array( $this,  'myplugin_check_fields'), 10, 3 );
}






    
    public function crf_registration_form() {

        
    
        $first_name = ! empty( $_POST['firts_name'] ) ? sanitize_text_field( $_POST['firts_name'] ) : '';
        $billing_company = ! empty( $_POST['billing_company'] ) ? sanitize_text_field( $_POST['billing_company'] ) : '';
        $billing_phone = ! empty( $_POST['billing_phone'] ) ? intval( $_POST['billing_phone'] ) : '';
        $billing_email = ! empty( $_POST['billing_email'] ) ? intval( $_POST['billing_email'] ) : '';
        $accept_contact = ! empty( $_POST['accept_contact'] ) ? intval( $_POST['accept_contact'] ) : '';
        $user_password = ! empty( $_POST['user_password'] ) ? $_POST['user_password']  : '';
        $confirm_user_password = ! empty( $_POST['confirm_user_password'] ) ? $_POST['confirm_user_password']  : '';
        ?>

        <!--navn start-->
        <p>

            <label for="first_name"><?php esc_html_e( $this->first_name_label, 'crf' ) ?><br/>
                <input type="text"
                       
                       id="first_name"
                       name="first_name"
                       value="<?php echo esc_attr( $first_name ); ?>"
                       class="input"
                />
            </label>
        </p>
        <!--navn slut-->

        <!--firma navn start-->
        <p>

            <label for="billing_company"><?php esc_html_e( $this->billing_company_label, 'crf' ) ?><br/>
                <input type="text"
                       
                       id="billing_company"
                       name="billing_company"
                       value="<?php echo esc_attr( $billing_company ); ?>"
                       class="input"
                />
            </label>
        </p>
        <!--firma navn slut-->

        <!--firma telefonnummer start-->
        <p>
            <label for="billing_phone"><?php esc_html_e( $this->billing_phone_label, 'crf' ) ?><br/>
                <input type="text"
                    
                    id="billing_phone"
                    name="billing_phone"
                    value="<?php echo esc_attr( $billing_phone ); ?>"
                    class="input"
                />
            </label>
        </p>
        <!--firma telefonnummer slut-->


        <!--firma fakturerings-email start-->
        <p>
            <label for="billing_email"><?php esc_html_e( $this->billing_email_label, 'crf' ) ?><br/>
                <input type="text"
                    
                    id="billing_email"
                    name="billing_email"
                    value="<?php echo esc_attr( $billing_email ); ?>"
                    class="input"
                />
            </label>
        </p>
        <!--firma fakturerings-email slut-->

        <!--firma password start-->
        <p>
            <label for="user_password"><?php esc_html_e( $this->user_password_label, 'crf' ) ?><br/>
                <input type="password"
                    
                    id="user_password"
                    name="user_password"
                    value="<?php echo esc_attr( $user_password ); ?>"
                    class="input"
                />
            </label>
        </p>
        <!--firma password slut-->


        <!--firma confirm password start-->
        <p>
            <label for="confirm_user_password"><?php esc_html_e( $this->confirm_user_password_label, 'crf' ) ?><br/>
                <input type="password"
                    
                    id="confirm_user_password"
                    name="confirm_user_password"
                    value="<?php echo esc_attr( $confirm_user_password ); ?>"
                    class="input"
                />
            </label>
        </p>
        <!--firma confirm password slut-->



        <!--firma accept af kontakt start-->
        <p>
            <label for="accept_contact"><br/>
                <input type="checkbox"
                    
                    id="accept_contact"
                    name="accept_contact"
                    value="<?php echo esc_attr( $accept_contact ); ?>"
                    class="checkbox"
                />
                <?php esc_html_e( $this->accept_label, 'crf' ) ?>
            </label><br><br>
        </p>
        <!--firma accept af kontakt slut-->

        <?php
    }


    
   public function crf_user_register( $user_id ) {

        if (  empty( $_POST['first_name'] ) ) {

        return new WP_Error( 'empty_first_name', __( 'Dit navn er påkrævet' ) );
        
        }else{
            update_user_meta( $user_id, 'first_name', sanitize_text_field( $_POST['first_name'] ) );
   
        }
           

        if ( ! empty( $_POST['billing_company'] ) ) {
            update_user_meta( $user_id, 'billing_company', sanitize_text_field( $_POST['billing_company'] ) );
        }

        if ( ! empty( $_POST['billing_phone'] ) ) {
            update_user_meta( $user_id, 'billing_phone', intval( $_POST['billing_phone'] ) );
        }

        if ( ! empty( $_POST['billing_email'] ) ) {
            update_user_meta( $user_id, 'billing_email', sanitize_email( $_POST['billing_email'] ) );
        }


        update_user_meta( $user_id, 'accept_contact', intval( $_POST['accept_contact'] ) );
 
    }


   public function crf_show_extra_profile_fields( $user ) {

        $accepted = get_the_author_meta( 'accept_contact', $user->ID ) == 0 ? 'Har ikke accepteret kontakt' : 'Har accepteret kontakt'; 
        ?>
        <h3><?php esc_html_e( 'Personal Information', 'crf' ); ?></h3>
    
        <table class="form-table">

            <tr>
                <th><label for="first_name"><?php esc_html_e( $this->first_name_label, 'crf' ); ?></label></th>
                <td><?php echo esc_html( get_the_author_meta( 'first_name', $user->ID ) ); ?></td>
            </tr>

            <tr>
                <th><label for="billing_company"><?php esc_html_e( $this->billing_company_label, 'crf' ); ?></label></th>
                <td><?php echo esc_html( get_the_author_meta( 'billing_company', $user->ID ) ); ?></td>
            </tr>

            <tr>
                <th><label for="billing_phone"><?php esc_html_e( $this->billing_phone_label , 'crf' ); ?></label></th>
                <td><?php echo esc_html( get_the_author_meta( 'billing_phone', $user->ID ) ); ?></td>
            </tr>

            <tr>
                <th><label for="billing_email"><?php esc_html_e( $this->billing_email_label, 'crf' ); ?></label></th>
                <td><?php echo esc_html( get_the_author_meta( 'billing_email', $user->ID ) ); ?></td>
            </tr>



            <tr>
                <th><label for="accept_contact"><?php esc_html_e( $this->accept_label, 'crf' ); ?></label></th>
                <td><?php echo $accepted; ?></td>
            </tr>
        </table>
        <?php
    }


/**
 * Back end registration
 */

public function crf_admin_registration_form( $operation ) {
	if ( 'add-new-user' !== $operation ) {
		// $operation may also be 'add-existing-user'
		return;
	}
 
    $first_name = ! empty( $_POST['firts_name'] ) ? sanitize_text_field( $_POST['firts_name'] ) : '';
	$billing_company = ! empty( $_POST['billing_company'] ) ? sanitize_text_field( $_POST['billing_company'] ) : '';
	$billing_phone = ! empty( $_POST['billing_phone'] ) ? intval( $_POST['billing_phone'] ) : '';
	$billing_email = ! empty( $_POST['billing_email'] ) ? sanitize_email( $_POST['billing_email'] ) : '';
	$accept_contact = ! empty( $_POST['accept_contact'] ) ? intval( $_POST['accept_contact'] ) : '';

	?>
	<h3><?php esc_html_e( 'Personal Information', 'crf' ); ?></h3>

	<table class="form-table">

        <tr>
			<th><label for="first_name"><?php esc_html_e( $this->first_name_label, 'crf' ); ?></label> <span class="description"><?php esc_html_e( '(påkrævet)', 'crf' ); ?></span></th>
			<td>
				<input type="text"

			       id="first_name"
			       name="first_name"
			       value="<?php echo  $first_name ; ?>"
			       class="regular-text"
				/>
            </td>
        </tr>


		<tr>
			<th><label for="billing_company"><?php esc_html_e( $this->billing_company_label, 'crf' ); ?></label> <span class="description"><?php esc_html_e( '(påkrævet)', 'crf' ); ?></span></th>
			<td>
				<input type="text"

			       id="billing_company"
			       name="billing_company"
			       value="<?php echo  $billing_company ; ?>"
			       class="regular-text"
				/>
            </td>
        </tr>
        

        <tr>
			<th><label for="billing_phone"><?php esc_html_e( $this->billing_phone_label , 'crf' ); ?></label> <span class="description"><?php esc_html_e( '(påkrævet)', 'crf' ); ?></span></th>
			<td>
				<input type="text"

			       id="billing_phone"
			       name="billing_phone"
			       value="<?php echo  $billing_phone ; ?>"
			       class="regular-text"
				/>
            </td>  
        </tr>
        
        <tr>
			<th><label for="billing_email"><?php esc_html_e( $this->billing_email_label, 'crf' ); ?></label> <span class="description"><?php esc_html_e( '(påkrævet)', 'crf' ); ?></span></th>
			<td>
				<input type="text"

			       id="billing_email"
			       name="billing_email"
			       value="<?php echo  $billing_email ; ?>"
			       class="regular-text"
				/>
            </td>  
        </tr>

        <tr>
			<th><label for="accept_contact"><?php esc_html_e( $this->accept_label, 'crf' ); ?></label> <span class="description"><?php esc_html_e( '(påkrævet)', 'crf' ); ?></span></th>
			<td>
				<input type="text"

			       id="accept_contact"
			       name="accept_contact"
			       value="<?php echo  $accept_contact ; ?>"
			       class="regular-text"
				/>
            </td>  
        </tr>
        
	</table>
	<?php
}


function myplugin_check_fields( $errors, $sanitized_user_login, $user_email ) {
 
    if ( ! empty($_POST['first_name'] ) ) {
        $errors->add( 'first_name_error', __( '<strong>ERROR</strong>: Navn mangler.', 'my_textdomain' ) );
    }
 
    return $errors;
}
 



function myplugin_set_pass( $user_id ) 
{
    
    if ( isset( $_POST['user_password']) && ! empty( $_POST['confirm_user_password'] ) && $_POST['user_password'] === $_POST['confirm_user_password'] )
    wp_set_password( $_POST['user_password'], $user_id );

}



}
