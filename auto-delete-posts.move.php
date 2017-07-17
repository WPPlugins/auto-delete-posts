<?php
			echo "<h2>Post Move Options</h2>\r\n";
			$cats = get_all_category_ids();
			if (!$cats) {
				echo "<p align='center'><strong>No Categories Found</strong></p>";
				return;
			}
			?>
			<fieldset class='move_options'>
			<table width="100%" cellpadding="3" cellspacing="3" style="text-align: left; vertical-align: top;">
			<tr class="alternate"><th width="75%">Category</th><th width="25%">Destination</th></tr>
			<input type='hidden' name='wpadp_move_options' value='update'>
			<?php
			foreach($cats as $i => $value) {
				echo "<tr>";
				$catname = get_catname($cats[$i]);
				echo "<td>$catname</td>";
				echo "<td><input type='radio' name='MoveCategory' value='$cats[$i]'";
				if ($cats[$i] == $this->settings['MoveCategory']) {
					echo " checked='checked'";
				}
				echo "></td>";
				echo "</tr>";
			}
			?>
			</table></fieldset>