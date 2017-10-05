<?php 
	global $pmpro_msg, $pmpro_msgt, $pmpro_confirm;

	if($pmpro_msg) 
	{
?>
	<div class="pmpro_message <?php echo $pmpro_msgt?>"><?php echo $pmpro_msg?></div>
<?php
	}
?>

<?php if(!$pmpro_confirm) { ?>           

<p class="strong"><?php _e('Are you sure you want to cancel your membership?', 'kleo_framework');?></p>

<p>
	<a class="pmpro_yeslink yeslink small radius button secondary" href="<?php echo pmpro_url("cancel", "?confirm=true")?>"><?php _e('Yes, cancel my membership', 'kleo_framework');?></a>
	-
	<a class="pmpro_nolink nolink small radius button" href="<?php echo pmpro_url("account")?>"><?php _e('No, keep my membership', 'pmpro');?></a>
</p>
<?php } else { ?>
	<p><a href="<?php echo get_home_url()?>" class="small radius button bordered"><?php _e('Click here to go to the home page.', 'pmpro');?></a></p>
<?php } ?>