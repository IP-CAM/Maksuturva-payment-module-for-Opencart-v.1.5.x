<?php if ($error_information) { ?>
<div class="error"><?php echo $error_information; ?></div>
<?php } else { ?>
	<?php if ($text_information) { ?>
	<div class="information"><?php echo $text_information; ?></div>
	<?php } ?>
	<form action="<?php echo $action; ?>" method="post" id="maksuturva_payment">
		<?php
		echo $maksuturvaForm;
		?>
	
	</form>
	
	<div class="buttons">
	  <div class="right"><a id="button-confirm" class="button" onclick="$('#maksuturva_payment').submit();"><span><?php echo $button_confirm; ?></span></a></div>   
	</div>
<?php }; ?>