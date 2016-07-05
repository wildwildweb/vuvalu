<?php
/**
 * @file
 * Default theme implementation to display the basic html structure of a single
 * Drupal page.
 */
?>
<!DOCTYPE html>
<html>

<head>
  <?php print $head; ?>
  <title><?php print $head_title; ?></title>
  <?php print $styles; ?>
  <?php print $scripts; ?>
 <meta content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">   
</head>
<body class="<?php print $classes; ?>" <?php print $attributes;?>>
  <?php print $page_top; ?>
  <?php print $page; ?>
  <?php print $page_bottom; ?>
</body>
</html>
