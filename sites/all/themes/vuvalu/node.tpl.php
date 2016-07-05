<?php

/**
 * @file
 * Default theme implementation to display a node.
 */
?>
<article id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>


  <h1<?php print $title_attributes; ?>><?php print $title; ?></h1>




  <div class="content"<?php print $content_attributes; ?>>
	
    <?php
      // We hide the comments and links now so that we can render them later.
      hide($content['comments']);
      hide($content['links']);
	  print render($content);
    ?>

    
  </div>

</article>
