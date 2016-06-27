<?php

/**
 * @file
 * Theme functions for uc_custom_product_search module.
 */

/**
 * Formats the custom product search form
 * @param $variables
 * @return string
 */
function theme_uc_custom_product_search($variables) {
  $form = $variables['form'];

  $output = '<div class="uc_custom-product-search">';
  $output .= drupal_render($form);
  $output .= '</div>';

  return $output;
}