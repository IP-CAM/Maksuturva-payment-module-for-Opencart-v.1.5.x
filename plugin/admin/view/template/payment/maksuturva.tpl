<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
      <a target="_blank" href="<?php echo $verify_link; ?>"><?php echo $text_verify ?></a><br />
      <a target="_blank" href="https://www.maksuturva.fi/extranet/PaymentEventInformation.xtnt"><?php echo $text_kauppias ?></a><br />
	  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
	    <table class="form">
	      <tr>
	        <td><?php echo $entry_sandbox; ?></td>
	        <td>
			  <select name="maksuturva_sandbox">
	            <option value="1" <?php if ($maksuturva_sandbox) { ?>selected="selected"<?php } ?>><?php echo $text_yes; ?></option>
	            <option value="0" <?php if (!$maksuturva_sandbox) { ?>selected="selected"<?php } ?>><?php echo $text_no; ?></option>
	          </select>
			</td>
	      </tr>
	      <tr>
	        <td><?php echo $entry_emaksut; ?></td>
	        <td>
			  <select name="maksuturva_emaksut">
	            <option value="0" <?php if (!$maksuturva_emaksut) { ?>selected="selected"<?php } ?>><?php echo $text_no; ?></option>
				<option value="1" <?php if ($maksuturva_emaksut) { ?>selected="selected"<?php } ?>><?php echo $text_yes; ?></option>
	          </select>
			</td>
	      </tr>	      
	      <tr>
	        <td><?php echo $entry_encoding; ?></td>
	        <td>
			  <select name="maksuturva_encoding">
	            <option value="UTF-8" <?php if ($maksuturva_encoding == "UTF-8") { ?>selected="selected"<?php } ?>>UTF-8</option>
				<option value="ISO-8859-1" <?php if ($maksuturva_encoding == "ISO-8859-1") { ?>selected="selected"<?php } ?>>ISO-8859-1</option>
	          </select>
			</td>
	      </tr>	     
	      <tr>
	        <td><?php echo $entry_sellerid; ?></td>
	        <td><input type="text" name="maksuturva_sellerid" value="<?php echo $maksuturva_sellerid; ?>" size="50%" />
	          <?php if ($error_sellerid) { ?>
	          <span class="error"><?php echo $error_sellerid; ?></span>
	          <?php } ?></td>
	      </tr>
	      
	      <tr>
	        <td></span> <?php echo $entry_secretkey; ?></td>
	        <td><input type="text" name="maksuturva_secretkey" value="<?php echo $maksuturva_secretkey; ?>" size="50%" />
	          <?php if ($error_secretkey) { ?>
	          <span class="error"><?php echo $error_secretkey; ?></span>
	          <?php } ?></td>
	      </tr>
	      
	      <tr>
	        <td><span class="required">*</span> <?php echo $entry_keyversion; ?></td>
	        <td><input type="text" name="maksuturva_keyversion" value="<?php echo ($maksuturva_keyversion ? $maksuturva_keyversion : '001'); ?>" size="50%" /></td>
	      </tr>

	      <tr>
	        <td><span class="required">*</span> <?php echo $entry_url; ?></td>
	        <td><input type="text" name="maksuturva_url" value="<?php echo ($maksuturva_url ? $maksuturva_url : 'https://www.maksuturva.fi'); ?>" size="50%" /></td>
	      </tr>

          <tr>
            <td><?php echo $entry_created; ?></td>
            <td><select name="maksuturva_created">
                <?php foreach ($order_statuses as $order_status) { ?>
	                <?php if ($order_status['order_status_id'] == $maksuturva_created) { ?>
	                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
	                <?php } else { ?>
	                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
	                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_completed; ?></td>
            <td><select name="maksuturva_completed">
                <?php foreach ($order_statuses as $order_status) { ?>
	                <?php if ($order_status['order_status_id'] == $maksuturva_completed) { ?>
	                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
	                <?php } else { ?>
	                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
	                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_cancelled; ?></td>
            <td><select name="maksuturva_cancelled">
                <?php foreach ($order_statuses as $order_status) { ?>
	                <?php if ($order_status['order_status_id'] == $maksuturva_cancelled) { ?>
	                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
	                <?php } else { ?>
	                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
	                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_error; ?></td>
            <td><select name="maksuturva_error">
                <?php foreach ($order_statuses as $order_status) { ?>
	                <?php if ($order_status['order_status_id'] == $maksuturva_error) { ?>
	                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
	                <?php } else { ?>
	                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
	                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_delayed; ?></td>
            <td><select name="maksuturva_delayed">
                <?php foreach ($order_statuses as $order_status) { ?>
	                <?php if ($order_status['order_status_id'] == $maksuturva_delayed) { ?>
	                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
	                <?php } else { ?>
	                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
	                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>



	      <tr>
	        <td><?php echo $entry_status; ?></td>
	        <td>
			  <select name="maksuturva_status">
	            <?php if ($maksuturva_status) { ?>
	            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
	            <option value="0"><?php echo $text_disabled; ?></option>
	            <?php } else { ?>
	            <option value="1"><?php echo $text_enabled; ?></option>
	            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
	            <?php } ?>
	          </select>
			</td>
	      </tr>
	      <tr>
	        <td><?php echo $entry_sort_order; ?></td>
	        <td><input type="text" name="maksuturva_sort_order" value="<?php echo $maksuturva_sort_order; ?>" size="1" /></td>
	      </tr>
	      
	    </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 