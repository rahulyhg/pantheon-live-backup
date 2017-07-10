<?php
/**
 * Plugin Name: Web Talkies User Profiles
 * Plugin URI: http://webtalkies.in
 * Description: This plugin adds multiple user profiles per site user .
 * Version: 1.0.0
 * Author: Bhushan S. Jawle
 * Author URI: http://www.sanganktechnologies.com
 * License: GPL2
 */


add_action( 'show_user_profile', 'add_extra_user_profiles' );
add_action( 'edit_user_profile', 'add_extra_user_profiles' );

function add_extra_user_profiles( $user )
{
    ?>
        <h3>Additional User Profiles</h3>

        <table class="form-table">
            <tr>
                <th><label for="main_profile">Main Profile</label></th>
                <td><input type="text" name="main_profile" value="<?php echo esc_attr(get_the_author_meta( 'main_profile', $user->ID )); ?>" class="regular-text" /></td>
                <th><label for="main_profile">Main Profile : (Maturity) </label></th>
                <td>
                      <select name="main_maturity_level">
                                <option <?php if(get_the_author_meta('main_maturity_level', $user->ID ) == "Mature A") echo 'selected'?>>Mature A</option>
                                <option <?php if(get_the_author_meta('main_maturity_level', $user->ID ) == "Mature B") echo 'selected'?>>Mature B</option>
                                <option <?php if(get_the_author_meta('main_maturity_level', $user->ID ) == "Mature C") echo 'selected'?>>Mature C</option>
                        </select>
                </td>
            </tr>

            <tr>
                <th><label for="additional_profile_1">Additional Profile 1</label></th>
                <td><input type="text" name="additional_profile_1" value="<?php echo esc_attr(get_the_author_meta( 'additional_profile_1', $user->ID )); ?>" class="regular-text" /></td>
                <th><label for="main_profile">Profile 1 : (Maturity) </label></th>
                <td>
                      <select name="additional_1_maturity_level">
                                <option <?php if(get_the_author_meta('additional_1_maturity_level', $user->ID ) == "Mature A") echo 'selected="selected"'?>>Mature A</option>
                                <option <?php if(get_the_author_meta('additional_1_maturity_level', $user->ID ) == "Mature B") echo 'selected="selected"'?>>Mature B</option>
                                <option <?php if(get_the_author_meta('additional_1_maturity_level', $user->ID ) == "Mature C") echo 'selected="selected"'?>>Mature C</option>
                        </select>
                </td>

            </tr>

            <tr>
                <th><label for="additional_profile_2">Additional Profile 2</label></th>
                <td><input type="text" name="additional_profile_2" value="<?php echo esc_attr(get_the_author_meta( 'additional_profile_2', $user->ID )); ?>" class="regular-text" /></td>
                <th><label for="main_profile">Profile 2 : (Maturity) </label></th>
                <td>
                      <select name="additional_2_maturity_level">
                                <option <?php if(get_the_author_meta('additional_2_maturity_level', $user->ID ) == "Mature A") echo 'selected'?>>Mature A</option>
                                <option <?php if(get_the_author_meta('additional_2_maturity_level', $user->ID ) == "Mature B") echo 'selected'?>>Mature B</option>
                                <option <?php if(get_the_author_meta('additional_2_maturity_level', $user->ID ) == "Mature C") echo 'selected'?>>Mature C</option>
                        </select>
                </td>
            </tr>
        </table>
    <?php
}

add_action( 'personal_options_update', 'save_extra_user_profiles' );
add_action( 'edit_user_profile_update', 'save_extra_user_profiles' );

function save_extra_user_profiles( $user_id )
{
    // Save profile names
    update_user_meta( $user_id,'main_profile', sanitize_text_field( $_POST['main_profile'] ) );
    update_user_meta( $user_id,'additional_profile_1', sanitize_text_field( $_POST['additional_profile_1'] ) );
    update_user_meta( $user_id,'additional_profile_2', sanitize_text_field( $_POST['additional_profile_2'] ) );

    // Save maturity levels
    update_user_meta( $user_id,'main_maturity_level', sanitize_text_field( $_POST['main_maturity_level'] ) );
    update_user_meta( $user_id,'additional_1_maturity_level', sanitize_text_field( $_POST['additional_1_maturity_level'] ) );
    update_user_meta( $user_id,'additional_2_maturity_level', sanitize_text_field( $_POST['additional_2_maturity_level'] ) );
}
