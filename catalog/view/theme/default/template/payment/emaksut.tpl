<form action="<?php echo $action; ?>" method="post" id="payment">
<?
$vars = get_defined_vars();
foreach ($vars as $key => $value) {

?>
  <input type="hidden" name="<?php echo htmlspecialchars($key); ?>" value="<?php echo htmlspecialchars($value); ?>" />
<?
}
?>
</form>

<div class="buttons">
  <div class="pull-right"><a onclick="$('#payment').submit();" class="btn btn-primary"><span><?php echo $button_confirm; ?></span></a></div>
</div>

