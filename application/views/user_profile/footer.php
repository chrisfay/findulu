	<div class="clear"></div>
	<div id="footer">
		The footer | <a href="<?php echo base_url() ?>auth/logout">log out</a> <?php echo (($this->tank_auth->is_admin()) ? ' | <a href="'.base_url().'admin/general">Admin</a>' : '')?>
	</div>
</div><!--wrapper-->

<!-- load js -->

<script type='text/javascript' src='<?php echo base_url() ?>js/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='<?php echo base_url() ?>js/jquery.ajaxQueue.js'></script>
<script type='text/javascript' src='<?php echo base_url() ?>js/jquery.autocomplete.js'></script>
<script type="text/javascript" src='<?php echo base_url() ?>wmd/wmd.js'></script>
</body>
</html>