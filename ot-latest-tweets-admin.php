<?php

/**
 * OT Ltaest Tweets Admin screen templates
 *
 * Functions for tempating the admin screens
 *
 * @author Jason Ackerman
 */

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}

// create custom plugin settings menu
add_action('admin_menu', 'ot_latest_tweets_create_menu');

function ot_latest_tweets_create_menu() {

	//create new top-level menu
	add_menu_page('OT Latest Tweets Settings', 'OT Latest Tweets Settings', 'administrator', __FILE__, 'ot_latest_tweets_settings_page' , plugins_url('/images/icon.png', __FILE__) );

	//call register settings function
	add_action( 'admin_init', 'register_ot_latest_tweets_settings' );
}


function register_ot_latest_tweets_settings() {
	//register our settings
	register_setting( 'ot_latest_tweets-settings-group', 'twitter_token' );
	register_setting( 'ot_latest_tweets-settings-group', 'twitter_token_secret' );
	register_setting( 'ot_latest_tweets-settings-group', 'consumer_key' );
	register_setting( 'ot_latest_tweets-settings-group', 'consumer_secret' );
}

function ot_latest_tweets_settings_page() {
?>
<div class="wrap">
<h1>OT Latest Tweets</h1>
<p>You have to get these values from Twitter. Log into your Twitter account, then visit the Twitter Developer center and follow the instructions to set up a new app.</p>

<ol>
	<li>Go to https://dev.twitter.com/apps/new and log in, if necessary</li>
	<li>Enter your Application Name, Description and your website address. You can leave the callback URL empty.</li>
	<li>Accept the TOS, and solve the CAPTCHA.</li>
	<li>Submit the form by clicking the Create your Twitter Application</li>
	<li>Copy the consumer key (API key) and consumer secret from the screen into your application</li>
</ol>

<form method="post" action="options.php">
    <?php settings_fields( 'ot_latest_tweets-settings-group' ); ?>
    <?php do_settings_sections( 'ot_latest_tweets-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Token</th>
        <td><input type="text" name="twitter_token" value="<?php echo esc_attr( get_option('twitter_token') ); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Token Secret</th>
        <td><input type="text" name="twitter_token_secret" value="<?php echo esc_attr( get_option('twitter_token_secret') ); ?>" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Consumer Key</th>
        <td><input type="text" name="consumer_key" value="<?php echo esc_attr( get_option('consumer_key') ); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Consumer Secret</th>
        <td><input type="text" name="consumer_secret" value="<?php echo esc_attr( get_option('consumer_secret') ); ?>" /></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } ?>