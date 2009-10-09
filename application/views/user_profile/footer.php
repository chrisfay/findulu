	<div class="clear"></div>
	<div id="footer">
		The footer | <a href="<?php echo base_url() ?>auth/logout">log out</a> <?php echo (($this->tank_auth->is_admin()) ? ' | <a href="'.base_url().'admin/general">Admin</a>' : '')?>
	</div>
</div><!--wrapper-->

<!-- load js -->

<script type='text/javascript' src='<?php echo base_url() ?>js/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='<?php echo base_url() ?>js/jquery.ajaxQueue.js'></script>
<script type='text/javascript' src='<?php echo base_url() ?>js/jquery.autocomplete.js'></script>
<script type="text/javascript">
		// to set WMD's options programatically, define a "wmd_options" object with whatever settings
		// you want to override.  Here are the defaults:
        wmd_options = {
			// format sent to the server.  Use "Markdown" to return the markdown source.
			output: "Markdown",			
			lineLength: 40,
			// line wrapping length for lists, blockquotes, etc.

			// toolbar buttons.  Undo and redo get appended automatically.
			buttons: "bold italic | link blockquote code image | ol ul heading hr",

			// option to automatically add WMD to the first textarea found.  
			autostart: true
		};
	</script>
<script type="text/javascript" src='<?php echo base_url() ?>wmd/wmd.js'></script>
</body>
</html>