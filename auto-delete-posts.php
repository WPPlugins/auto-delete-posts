<?php
/*
  Plugin Name: Auto Delete Posts
  Plugin URI: http://ashwinbihari.com/plugins
  Description: Auto delete or move posts after a pre-determined time.
  Author: Ashwin Bihari
  Author URI: http://ashwinbihari.com
  Version: 1.0.1
*/

/*
 * Copyright (c) 2007-2008 Ashwin Bihari
 * http://www.ashwinbihari.com/
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

define("PREVIEW", '0');
define("DELETE_POSTS", '1');
define("MOVE_POSTS", '2');
define("PER_POST", '3');
define("SITE_WIDE", '4');
 
// For compatibility with WP 2.0

if (!function_exists('wp_die')) {
	function wp_die($msg) {
		die($msg);
	}
}


class ab_AutoDeletePosts
{
	var $settings;

	/* Constructor */
	function ab_AutoDeletePosts()
	{
		// Config options
		add_action('admin_menu', array(&$this, 'wpadp_menu'));
		// Post-level settings
		/* AB: Future use
		add_action('dbx_post_sidebar', array(&$this, 'wpadp_post_options'));
		add_action('save_post', array(&$this, 'save_post'));
		*/
		
		$this->get_settings();
		
		if (DELETE_POSTS == $this->settings['DelMvPreview']) {
			// Delete  hooks
			add_action('edit_post', array(&$this, 'wpadp_check_and_delete_posts'));
			add_action('publish_post', 
				array(&$this, 'wpadp_check_and_delete_posts'));
		} else if (MOVE_POSTS == $this->settings['DelMvPreview']) {
			// Move hooks
			add_action('edit_post', array(&$this, 'wpadp_check_and_move_posts'));
			add_action('publish_post', array(&$this, 'wpadp_check_and_move_posts'));
		}
	}
	
	/* get_settings */
	function get_settings()
	{
		$this->defaultSettings = array(
			// Number of days for post to expire
			'PostAge' => 14,
			// Per post or Site Wide operation
			'PerPostOrSite' => SITE_WIDE,
			// Delete, Move or Preview
			'DelMvPreview' => PREVIEW,
			// Delete category
			'DeleteCategory' => 0,
			// Move category
			'MoveCategory' => 0
		);
		
		if (!isset($this->settings)) {
			$this->settings = get_option('wpadp_options');
			if (FALSE == $this->settings) {
				$this->settings = $this->defaultSettings;
			} else {
				$this->settings = array_merge($this->defaultSettings, $this->settings);
			}
			$this->sanitize_settings();
		}
		
		return $this->settings;
	}
	
	/* save_settings */
	function save_settings()
	{
		$this->get_settings();
		
		foreach($this->defaultSettings as $k=>$v) {
			$this->settings[$k] = $_POST[$k];
		}
		
		$this->sanitize_settings();
		update_option('wpadp_options', $this->settings);
	}
	
	/* sanitize_settings */
	function sanitize_settings()
	{
		foreach (array_keys($this->settings) as $k) {
			$v = $this->settings[$k];
			switch($k) {
				case 'PostAge':
				case 'DelMvPreview':
				case 'PerPostOrSite':
				case 'DeleteCategory':
				case 'MoveCategory':
					$this->settings[$k] = (int) $v;
					break;
				default:
					unset($this->settings[$k]);
			}
		}
	}
	
	/* Add menu page */
	function wpadp_menu()
	{
	add_options_page('Auto Delete Posts Options', 'Auto Delete Posts', 9,
		   basename(__FILE__), array(&$this, 'wpadp_manage'));
	}

	/* Manage options in admin menu */
	function wpadp_manage()
	{
		global $wpdb;

		if ('POST' == $_SERVER['REQUEST_METHOD']) {
			$this->save_settings();
		} else {
			$this->get_settings();
		}
		
		require_once(dirname(__FILE__) . '/auto-delete-posts.config.php');		
	}
	
	function save_post($postID)
	{
		$this->get_settings();
		if ($this->settings['PerPostOrSite'] == PER_POST)
		{
			$postAge = (int)$_POST['adpPostAge'];
			if ($postAge == 0)
			{
				$postAge = $this->settings['PostAge'];
			}
			
			if (!update_post_meta($postID, '_auto_delete_posts', $postAge)) 
			{
				add_post_meta($postID, '_auto_delete_posts', $postAge);
			}
		}
	}

	/* Check posts that need to be deleted. */
	function wpadp_check_and_delete_posts()
	{
		global $wpdb;
		
		$this->get_settings();

		$expiration = $this->settings['PostAge'];
		$category = $this->settings['DeleteCategory'];

		/* 
		* Create a query that will give us all the post ID's that predate the 
		* interval we are given, that is, NOW - expiration date.
		*/
		if ('0' == $category) {
			$query = "SELECT ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND post_date < NOW() - INTERVAL ".
			$expiration." DAY";
		} else {
			/* First grab the ID's from a particular category */
			$getpost_args = 'numberposts=-1&category='.$category;
			$posts = get_posts($getpost_args);
			if ($posts) {
				/* 
				* Create the new query with JUST the ID's from that given category. Why the wp_posts table has
				* an unused "category" field is beyond me!
				*/
				$query = "SELECT ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND post_date < NOW() - INTERVAL ".
				$expiration." DAY ";
				$start = 0;
				foreach($posts as $post) {
					if (!$start) {
						$query .= "AND ( ID='";
						$start = 1;
					} else {
						$query .= "OR ID='";
					}
					$query .= $post->ID;
					$query .= "' ";
				}
				$query .= ")";
			} else {
				echo "No posts!";
			}
		}
		$ids = $wpdb->get_results($query);

		if ($ids) {
			foreach($ids as $id) {
				if ('' == $id->ID) {
				continue;
			}
			wp_delete_post($id->ID);
			}
		}
	}

	/* Check posts that need to be moved. */
	function wpadp_check_and_move_posts()
	{
		global $wpdb;

		$this->get_settings();
		
		$expiration = $this->settings['PostAge'];
		$category = array($this->settings['MoveCategory']);

		/*
		* Create a query that will give us all the post ID's that predate the
		* interval we are given, that is, NOW - expiration date.
		*/
		$query = "SELECT ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_type = 'post' AND post_date < NOW() - INTERVAL ".
		$expiration." DAY";
		
		$ids = $wpdb->get_results($query);
		foreach($ids as $id) {
			if ('' == $id->ID) {
				continue;
			}
			wp_set_post_cats('', $id->ID, $category);
		}
	}

	function wpadp_post_options()
	{
		global $post_ID;
		$this->get_settings();
		$postAge = get_post_meta($post_ID, '_auto_delete_posts', true);
	?>
	<div id="auto-delete-posts-div" class="postbox">
		<div class="handlediv" title="Click to toggle"><br/></div>
		<h3 class="hndle"><span>Auto Delete Posts</span></h3>
		<div class="inside">
			<label class="selectit" for="adpPostAge">
				<input id="adpPostAge" type="text" size="3" name="adpPostAge" value="<?php echo @$postAge; ?>" />
				Expiration in days (choose Per Post for <a href="options-general.php?page=auto-delete-posts.php">Plugin Operation</a>)
			</label>
		</div>
	</div>
	<?
	}	
}

// Instantiate the object to make the magic occur
$myAutoDeletePosts = new ab_AutoDeletePosts();

/* Look for immediate action hooks and perform the actions */
if (isset($_POST['performdelete'])) {
  $myAutoDeletePosts->wpadp_check_and_delete_posts();
}
if (isset($_POST['performmove'])) {
  $myAutoDeletePosts->wpadp_check_and_move_posts();
}
?>
