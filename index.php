<?php
/*
Plugin Name: GOSU Chat
Plugin URI: https://gosuchat.io/
Description: With <strong>GOSU Chat</strong> you can now have your own page wide chat. But wait, there is more. When you are not in front of your computer, you can use the GOSU mobile apps to stay in contact with your visitors.
Version: 0.1.4
Author: Maple Apps
Author URI: https://gosuchat.io/
License: GPLv2 or later
*/

/**
 * This file defines global constants and defines GosuChat class.
 *
 * @package GOSU Chat
 * @author Maple Apps
 */


if (!defined('ABSPATH')) {
	echo 'This file cannot be directly accessed.';
	die();
}

class GosuChat {
	function __construct() {
		add_action('admin_menu', array($this, 'registerPreferencesMenu'));
		add_action('admin_init', array($this, 'registerPreferences'));
		add_action('wp_footer', array($this, 'renderChatClient'), 99999);
	}

	function renderChatClient() {
		$options = get_option('gosu_chat_options');
		if (isset($options['view_id'])) {
			$ecc_url = 'https://go.su/e/' . $options['view_id'];
			?>
				<script type="text/javascript" src="<?php echo esc_url($ecc_url) ?>"></script>
			<?php
		} else if (isset($options['community_ids'])) {
			$ecc_url = 'https://be.go.su/embed/' . implode(',', $options['community_ids']);
		}
	}

	function registerPreferencesMenu() {
		if (function_exists('add_options_page')) {
			add_options_page(
		        'GOSU CHAT',
		        'GOSU&hairsp;<strong>CHAT</strong>',
		        'manage_options',
		        'gosu-chat-settings',
		        array($this, 'createPreferencesPage')
		    );
		}
	}

	function createPreferencesPage() {
		?>
		<div class='wrap'>
			<h2>GOSU&hairsp;<strong>CHAT</strong> Preferences</h2>
			<p>GOSU&hairsp;<strong>CHAT</strong> is an embedded chat client for communities. To create and customize your own chat go to <a href='https://gosuchat.io/my-chat' target='_blank'> GOSU&hairsp;<strong>CHAT</strong> page</a>.</p>
			<p>After you created and customized your chat, come back here and enter the ID of your snippet (the 4 letter code at the end of the snippet URL) below</p>
			<form method="post" action="options.php">
				<?php settings_fields('gosu_chat_options'); ?>
	            <?php $options = get_option('gosu_chat_options'); ?>
				<table class="form-table">
                   <tr valign="top">
					   <th>Snippet ID</th>
					   <td>
						   <textarea class='large-text code' rows='1' id="view_id" name="gosu_chat_options[view_id]"><?php
							   echo isset($options['view_id']) ? esc_attr($options['view_id']) : '';
							?></textarea>
							<label class='description' for='gosu_chat_options[view_id]'>ID of your snippet from gosuchat.io (4 letters)</label>
						</td>
				   </tr>
				</table>

			    <?php submit_button(); ?>
			</form>
		</div>
		<?php
	}

	function registerPreferences() {

		register_setting(
            'gosu_chat_options', // Option group
            'gosu_chat_options', // Option name
            array( $this, 'sanitizeOptions' ) // Sanitize
        );
	}

    function sanitizeOptions( $input )
    {
		$newOptions = array();
        if( isset( $input['view_id'] ) ) {

			$newOptions['view_id'] = trim($input['view_id']);
		}
        return $newOptions;
    }

}

new GosuChat;
