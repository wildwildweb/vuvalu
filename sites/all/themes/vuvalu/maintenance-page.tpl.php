<?php

/**
 * @file
 * Default theme implementation to display a single Drupal page while offline.
 *
 * All the available variables are mirrored in html.tpl.php and page.tpl.php.
 * Some may be blank but they are provided for consistency.
 *
 * @see template_preprocess()
 * @see template_preprocess_maintenance_page()
 */
?>
<!DOCTYPE html>
<html lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>">

<head>
  <?php print $head; ?>
  <title><?php print $head_title; ?></title>
  <?php print $styles; ?>
  <?php print $scripts; ?>
</head>

<body class="<?php print $classes; ?>" <?php print $attributes;?>>
  
  <center>
  <div id="maintenance">


<center>

<p>&nbsp;</p>
<p>&nbsp;</p>

<center>
<img src="/sites/all/themes/vuvalu/images/under.jpg" />
<br />
<br />
    <?php print $messages; ?>
    <?php print $content; ?>

</center>

</center>



  </center>
  
</body>
</html>
