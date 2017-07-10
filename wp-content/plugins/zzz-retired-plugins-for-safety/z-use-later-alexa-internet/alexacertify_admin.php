<?php
//must check that the user has the required capability 
if (!current_user_can('manage_options')) {
    wp_die( __('You do not have sufficient permissions to access this page.') );
}
$formAction = str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);
?>

<?php if (!empty($this->updated)): ?>
    <div class="updated"><?= implode("<br />\n", $this->updated) ?></div>
<?php endif;?>   

<?php if (!empty($this->errors)): ?>
    <div class="error"><?= implode("<br />\n", $this->errors) ?></div>
<?php endif; ?>

<div class="wrap">
    <h2> The Official Alexa WordPress Plugin </h2>

  <form name="alexacertify_form" method="post" action="<?php echo $formAction ?>">
    <input type="hidden" name="alexacertify_submit" value="Y"/>
    <h3>
        Certify Your Site's Metrics
    </h3>
    <p>
      Alexa Pro subscribers can have their website traffic
      Certified by Alexa. To learn more about Alexa Pro, visit Alexa's 
      <a href="http://www.alexa.com/plans" target="_blank">plans</a> page.
      <br/>
      If you are already an Alexa Pro subscriber, follow these steps to Certify your site's metrics:
    </p>
    <ol>
      <li>Visit <a href="http://www.alexa.com/account/sitesettings">your Site Settings</a>.</li>
      <li>Click the 'Certification Status' link for the site you wish to certify. This will take you to the 'Certification Status' page for your site.</li>
      <!--li>Copy the Certify Code and paste it here:<br/>
        <textarea name="alexacertify_certify" style="width: 80%; height: 8em;"><?= htmlspecialchars($this->certify_snippet) ?></textarea>
      </li-->
      <img src="http://tbar.alexa.com/wordpress/snippet_screen_shot.png"></img>
      <li>Copy the atrk_acct and paste it here: <br/>
        <input type="text" name="alexacerify_atrk_id" style="width: 100%;" value="<?=htmlspecialchars($this->certify_atrk_id)?>"></input>
      </li>
      <li>Copy the domain and paste it here: <br/>
        <input type="text" name="alexacerify_atrk_dn" style="width: 100%;" value="<?=htmlspecialchars($this->certify_domainn)?>"></input>
      </li>
      <?php wp_nonce_field( 'configure-alexa-internet-plugin' );?>
      <li>Press this <input type="submit" name="Submit" value="Add Certify Code" /> button to insert the code into your site.</li>
      <li>Go back to the 'Certification Status' page on alexa.com and press the 'Scan My Site' button.</li>
    </ol>
    <br/>
  </form>
</div>
