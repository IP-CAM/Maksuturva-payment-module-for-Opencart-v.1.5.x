<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?></h1>
    </div>
    <div class="content">
    	<?php echo $text_verifylist ?>
	    <table class="list">
	    	<tr><td><?php echo $text_order ?></td><td><?php echo $text_message ?></td></tr>
	    	<?php foreach ($statuses as $id => $message){ ?>
	      <tr>
	        <td><a target="_blank" href="<?php echo $links[$id]?>">#<?php echo $id; ?></a></td>
	        <td><?php echo $message ?></td>
	      </tr>
	      <?php } ?>
	    </table>
    </div>
  </div>
</div>
<?php echo $footer; ?> 