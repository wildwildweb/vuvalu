<?php

/**
 * @file
 * Default theme implementation to display a node.
 */
?>
<article id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <div class="content"<?php print $content_attributes; ?>>
	 
	<div class="node-left">
		<?php print render($content['uc_product_image']); ?>
				
		<?php
			$models = uc_product_get_models($node->nid);
			$stock = 0;
			foreach ($models as $sku) {
			  $level = uc_stock_level($sku);
			  $stock += $level;
			}
    		if ($stock > 0) {
    		  if ($content['list_price']['#value'] > $content['display_price']['#value']) {
      			print "<span class=\"rebajado\">Rebajado</span>";  
    		  }
    		}
			else {
			  print "<span class=\"agotado\">Agotado</span>";
			}
    	?>
		
    </div>
	
	<div class="node-right">
		
    <h1<?php print $title_attributes; ?>><?php print $title; ?></h1>
    

    <?php print render($content['display_price']);?>
    <?php
    if ($content['list_price']['#value'] != '0.00000') {
      print render($content['list_price']);
    }
    ?>
    <?php print render($content['body']);?>
    


    <?php  //$block = module_invoke('quicktabs', 'block_view', 'informacion_adicional');?>
    <?php print render($block['content']);?>

    <?php print render($content['field_video']);?>
    <?php print render($content['add_to_cart']);?>
    <span class="envio">ENVÍO GRATUITO EN PEDIDOS SUPERIORES A 100€<br/>(a Península Ibérica y Baleares)</span>

    <div class="addthis">
	<h3>Compartir</h3>
    <?php print render($content['field_addthis']);?>
    </div>

    <?php
      // We hide the comments and links now so that we can render them later.
      hide($content['comments']);
      hide($content['links']);
	    print render($content);
    ?>
   </div>

    
  </div>

</article>
