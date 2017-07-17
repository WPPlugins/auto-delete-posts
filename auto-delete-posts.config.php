		<div class='wrap'>
			<h2>Auto Delete Posts Options</h2>
			<form method='POST'>
			<fieldset class='options'>
			<table class='optiontable'>
				<tr valign="top">
					<th style='text-align: left;'>
						<label>Plugin Operation</label>
					</th>
					<td style='text-align: left;'>
						<input type='radio' name='PerPostOrSite' value='<?php echo PER_POST; ?>' disabled
						<?php
						if (PER_POST == $this->settings['PerPostOrSite']) {
							echo " checked='checked'";
						}
						echo ">";
						?>
						&nbsp; Per Post &nbsp;&nbsp;&nbsp; <i>&lt;Future use&gt;</i><br/>
						<input type='radio' name='PerPostOrSite' value='<?php echo SITE_WIDE; ?>'
						<?php
						if (SITE_WIDE == $this->settings['PerPostOrSite']) {
							echo " checked='checked'";
						}
						echo ">";
						?>
						&nbsp; Site Wide<br/>
					</td>
				</tr>
				<tr valign="top">
					<th style='text-align:left;'>
						<label>Plugin Action</label>
					</th>
					<td style='text-align:left;'>
						<input type='radio' name='DelMvPreview' value='<?php echo DELETE_POSTS; ?>'
						<?php
						if (DELETE_POSTS == $this->settings['DelMvPreview']) {
						echo " checked='checked'";
						}
						echo ">";
						?>
						&nbsp; Delete<br/>
						<input type='radio' name='DelMvPreview' value='<?php echo MOVE_POSTS; ?>'
						<?php
						if (MOVE_POSTS == $this->settings['DelMvPreview']) {
						echo " checked='checked'";
						}
						echo ">";
						?>
						&nbsp;  Move<br/>
						<input type='radio' name='DelMvPreview' value='<?php echo PREVIEW; ?>'
						<?php
						if (PREVIEW == $this->settings['DelMvPreview']) {
						echo " checked='checked'";
						}
						echo ">";
						?>
						&nbsp; Preview
					</td>
				</tr>
				<tr>
				<th>
					<label>How long (in days) to keep posts:</label>
				</th>
				<td>
					 <input type='text' size='10' name='PostAge' 
						value=" <?php echo $this->settings['PostAge']; ?>" />
					 </td>
				<tr>
				<td align='right' colspan='2'>
					<input type='submit' name='submit' value='Update Options'>
				</td>
				</td>
			</tr>
			</table>
			</fieldset>
		<div style='clear: both;'>&nbsp;</div>
	
		<?php
	  /* Choose which category to delete from */
		if (DELETE_POSTS == $this->settings['DelMvPreview']) {
			require_once(dirname(__FILE__) . '/auto-delete-posts.delete.php');		
		} 
	  /* Choose which category to move posts to */
		else if (MOVE_POSTS == $this->settings['DelMvPreview']) {
			require_once(dirname(__FILE__) . '/auto-delete-posts.move.php');		
		}
	  /* Preview what posts would be deleted. */
		else if (PREVIEW == $this->settings['DelMvPreview']) {
			require_once(dirname(__FILE__) . '/auto-delete-posts.preview.php');		
		}
		?>
		</div>