<?php
/**
 * @file
 * Default theme implementation to display a region.
 */
?>

<?php if ($content): ?>
  <section id="<?php print $region; ?>" class="clearfix <?php print $classes; ?>">
    <div class="content">
    <?php print $content; ?>
    </div>
  </section>
<?php endif; ?>
