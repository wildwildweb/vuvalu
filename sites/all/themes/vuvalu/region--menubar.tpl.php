<?php
/**
 * @file
 * Default theme implementation to display a region.
 */
?>

<?php if ($content): ?>
  <nav id="<?php print $region; ?>" class="clearfix <?php print $classes; ?>">
    <?php print $content; ?>
	<a href="#0" class="close-menu">Close<span></span></a> 
  </nav>
<?php endif; ?>
