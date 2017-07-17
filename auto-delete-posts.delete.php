<?php
			echo "<h2>Post Delete Options</h2>\r\n";
			$cats = get_all_category_ids();
			if (!$cats) {
				echo "<p align='center'><strong>No Categories Found</strong></p>";
				return;
			}
			?>
			<fieldset class='delete_options'>
			<table width="100%" cellpadding="3" cellspacing="3" 	style="text-align: left; vertical-align: top;">
			<tr class="alternate"><th width="75%">Category</th><th width="25%">Selected</th></tr>
			<tr>
			<td>All Categories</td>
			<td><input type='radio' name='DeleteCategory' value='0'
			<?php
			if ('0' == $this->settings['DeleteCategory']) {
				echo " checked='checked'";
			}
			echo ">";?>
			</td>
			</tr>
			<tr><td colspan='2'><hr /></td></tr>
			<?php
			foreach($cats as $i => $value) {
				echo "<tr>";
				$catname = get_catname($cats[$i]);
				echo "<td>$catname</td>";
				echo "<td><input type='radio' name='DeleteCategory' value='$cats[$i]'";
				if ($cats[$i] == $this->settings['DeleteCategory']) {
					echo " checked='checked'";
				}
				echo "></td>";
				echo "</tr>";
			}
			?>
			<tr><td align='right' colspan='2'><input type='submit' 	name='submit' value='Update Options'></td></tr>
			</table></fieldset>