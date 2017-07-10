<?php
/*
Plugin Name: Web Talkies Custom Profile field as a Registration Field
Plugin URI: http://www.webtalkies.in
Description: Plugin for adding a custom profile field to WordPress usermeta and on the registration page
Author: Virendra
Version: 0.1
Author URI: http://www.virendrashahaney.com/
*/

/**
 * Add additional custom field
 */

add_action ( 'show_user_profile', 'wtvs_show_extra_profile_fields' );
add_action ( 'edit_user_profile', 'wtvs_show_extra_profile_fields' );

function wtvs_show_extra_profile_fields ( $user )
{
?>
	<h3>Mobile users information</h3>
	<table class="form-table">
		             
                <tr>
			<th><label for="Mobile">Mobile No.</label></th>
			<td>
				<input type="text" name="mobile" id="mobile" value="<?php echo esc_attr( get_the_author_meta( 'mobile', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description">Please enter your Mobile No.</span>
			</td>
		</tr>
	</table>
<?php
}

add_action ( 'personal_options_update', 'wtvs_save_extra_profile_fields' );
add_action ( 'edit_user_profile_update', 'wtvs_save_extra_profile_fields' );

function wtvs_save_extra_profile_fields( $user_id )
{
	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;
	/* Copy and paste this line for additional fields. Make sure to change 'mobile' to the field ID. */
	
        update_usermeta( $user_id, 'mobile', $_POST['mobile'] );
}

/**
 * Add custom field to registration form
 */

add_action('register_form','wtvs_show_first_name_field');
add_action('register_post','wtvs_check_fields',10,3);
add_action('user_register', 'wtvs_register_extra_fields');

function wtvs_show_first_name_field()
{
?>
	<p>
	<label>Mobile No.<br/>
        <input id="mobile" type="text" tabindex="30" value="<?php echo $_POST['mobile']; ?>" name="mobile" />
	</label>
	</p>
<?php
}

function wtvs_check_fields ( $login, $email, $errors )
{
	global $mobile;
	
        /*adding mobile code -virendra*/
         if ( $_POST['mobile'] == '' )
	{
		$errors->add( 'empty_realname', "<strong>ERROR</strong>: Please Enter your mobile number" );
	}
	elseif (wtvs_validate_mobile_number() == False)
	{
		$errors->add( 'empty_realname', "<strong>ERROR</strong>: Mobile No. is not valid" );
	}
        elseif (wtvs_mobile_exists($_POST['mobile']) == True)
	{
		$errors->add( 'empty_realname', "<strong>ERROR</strong>: Mobile No. already registered." );
	}
        else
	{
		$mobile = $_POST['mobile'];
	}
        
}

function wtvs_register_extra_fields ( $user_id, $password = "", $meta = array() )
{
	
        global $wpdb;
        
        update_user_meta( $user_id, 'mobile', $_POST['mobile'] );
        
        // Giving new user Free membership of pmpro. VIRENDRA
        $wpdb->insert($wpdb->pmpro_memberships_users, array(
        "user_id" => $user_id,
        "membership_id" => 1,
        "status" => 'active',
        "startdate" => current_time('mysql')
           
));
       
}

/**
 * BY VIRENDRA. Checks whether the given mobile exists.
 *.
 * Returns true if mobile number already exists. False if not. 
 */
function wtvs_mobile_exists( $mobile ) {
	
    global $wpdb;
    
    $user = $wpdb->get_var("SELECT user_id FROM $wpdb->usermeta WHERE meta_value = '" . $mobile . "' LIMIT 1" );
        
    if ( $user) 					
       {return True;}
                
       return false;
}

/* By Virendra : Function to check if Entry in mobile number field is valid 10 digit numeric.
 * 
 */
function wtvs_validate_mobile_number()
	{
		if(is_numeric($_POST['mobile']))
		{
			if(strlen($_POST['mobile']) > 9 && strlen($token) < 11)
				return true;
			else
				return false;
		}
                
                else
			return false;
	}