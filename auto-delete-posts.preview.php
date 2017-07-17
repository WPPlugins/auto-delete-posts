<?php
			echo "<h2>Preview Of Post Deletion/Move</h2>\r\n";
			if (SITE_WIDE == $this->settings['PerPostOrSite']) {
				$expiration = $this->settings['PostAge'];
				$category = $this->settings['DeleteCategory'];
		
				if ('0' != $category) {
					$getpost_args = 'numberposts=-1&category='.$category;
				} else {
					$getpost_args = 'numberposts=-1';
				}
				$posts = get_posts($getpost_args);
				if ($posts) {
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
					$query .= ") ORDER BY ID ASC";
				}

				$ids = $wpdb->get_results($query);
				if (!$ids) {
					echo "<p align='center'><strong>No Posts Found</strong></p>";
					return;
				}
				echo '<table width="100%" cellpadding="3" cellspacing="3" style="text-align: left; vertical-align: 	top;">';
				echo '<tr class="alternate"><th width="25%">Post ID</th><th width="25%">Post Title</th><th 	width="25%">Post Category</th><th width="25%">No. of Comments</th></tr>';
				foreach($ids as $id) {
					if ('' == $id->ID) {
						continue;
					}
					$query = "SELECT DISTINCT post_title FROM $wpdb->posts WHERE ID=".
					$id->ID;
					$title = $wpdb->get_var($query);
					$commentCount = get_comments_number($id->ID);
					$catnames = get_the_category($id->ID);
					echo "<tr>";
					echo "<td>$id->ID</td> <td>$title</td>";
					echo "<td>";
					$count = 0;
					foreach($catnames as $catname) {
						if ($count > 0) {
							echo ", ";
						}	
						echo "$catname->cat_name";
						$count++;
					}
					echo "</td>";
					if ($commentCount) {
						echo "<td>$commentCount</td>\r\n";
					} else {
						echo "<td>0</td>\r\n";
					}
					echo "</tr>";
				}
			} else if (PER_POST == $this->settings['PerPostOrSide']) {
				
			}
			?>
			<tr>
			<td>
			<?php

			$adp_categoryid = $this->settings['MoveCategory'];
			$adp_category = get_the_category_by_ID($adp_categoryid);
			?>
			
			<form method="post" />
			<input name="performdelete" class="button" type="submit" id="performdelete" tabindex="10" value="Delete These Posts Now!" onclick="return confirm('You are about to delete all these posts  'Cancel' to stop, 'OK' to delete.')" />
			<?php
			$blog_version = get_bloginfo('version');
			$major = (int) substr($blog_version, 0, 1);
			$minor = (int) substr($blog_version, 2, 3);
			if ((2 == $major)  && ( 7 > $minor)) {
				if (!is_wp_error($adp_category) && ("" != $adp_category)) {
				echo "<input name=\"performmove\" class=\"button\" type=\"submit\" id=\"performmove\" tabindex=\"10\" value=\"Move These Posts to '".$adp_category."' Now!\" onclick=\"return confirm('You are about to move all these posts  \'Cancel\' to stop, \'OK\' to move.') \" />\r\n";
				} 
			} else { 
					if (!is_wp_error($adp_category) ) {
					echo "<input name=\"performmove\" class=\"button\" type=\"submit\" id=\"performmove\" tabindex=\"10\" value=\"Move These Posts to '".$adp_category."' Now!\" onclick=\"return confirm('You are about to move all these posts  \'Cancel\' to stop, \'OK\' to move.') \" />\r\n";
					}
			}
			?>
			</form>
			</td>
			</tr>
			</table>
