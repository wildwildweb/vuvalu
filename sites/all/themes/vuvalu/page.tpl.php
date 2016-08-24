<?php
/**
 * @file
 * Default theme implementation to display a single Drupal page.
 */
?>

    <header role="banner" class="clearfix">

      <?php print render($page['toolbar']); ?>
      
      
      <div class="logo-menu" class="clearfix">
        <a href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo"><?php print t('Home'); ?></a>
      </div>
	  
      <a class="menu-trigger" href="#menubar"><span></span></a>
	  <div id="main-nav">
	    <?php print render($page['menubar']); ?>
	    <a href="#0" class="close-menu">Close<span></span></a> 
      </div>
      
    </header>   

    

	<?php if ($page['precontent']): ?>
	  <?php print render($page['precontent']); ?>
	<?php endif; ?>
	
	 <?php if ($page['bloquesportada']): ?>
	    <?php print render($page['bloquesportada']); ?>
      <?php endif; ?>
    
           
    <section id="container" class="clearfix">
    
      <?php print $messages; ?>  
         
      <?php if ($action_links = render($action_links)): ?>
        <ul class="action-links"><?php print $action_links; ?></ul>
      <?php endif; ?>          

      <section id="main" class="clearfix">
      
        <?php print render($page['content']); ?>

	    <?php if ($page['sidebar']): ?>
	        <?php print render($page['sidebar']); ?>
	    <?php endif; ?> 

      </section>
  
  	  <?php if ($tabs = render($tabs)): ?>
  	    <div class="tabs"><?php print $tabs; ?></div>
  	  <?php endif; ?>
      

      <?php if ($page['prefooter']): ?>
        <?php print render($page['prefooter']); ?>
      <?php endif; ?>
	  
	   </section>

  <footer role="banner" class="clearfix">
    <?php print render($page['footer']); ?>
  </footer>	
	    
      <?php if ($page['postfooter']): ?>
	    <?php print render($page['postfooter']); ?>
      <?php endif; ?> 
