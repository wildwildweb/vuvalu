<?php

/**
 * Process variables for search-result.tpl.php.
 *
 * @see search-result.tpl.php
 */
function vuvalu_preprocess_search_result(&$variables) {
  $result = $variables['result'];
  if (isset($result['node'])) {
    $variables['snippet'] = $result['node']->rendered;
  }
}

/**
 * Theming totals.
 */
function vuvalu_uc_payment_totals($variables) {

  $order = $variables['order'];
  $line_items = uc_order_load_line_items_display($order);

  $output = '<table id="uc-order-total-preview">';

  $datos = array();
  foreach ($line_items as $line) {
    if ($line['type'] == 'total') {
      $datos['total'] = $line['amount'];
    }
    if ($line['type'] == 'shipping') {
      $datos['shipping'] = $line['amount'];
    }
  }

  foreach ($line_items as $line) {
    if (!empty($line['title'])) {
      if (in_array($line['type'], array('subtotal', 'shipping', 'total'))) {
        if ($line['type'] == 'subtotal') {
          $line['amount'] = (isset($datos['total']) ? $datos['total'] : 0) - (isset($datos['shipping']) ? $datos['shipping'] : 0);
        }
        $attributes = drupal_attributes(array('class' => array('line-item-' . $line['type'])));
        $output .= '<tr' . $attributes . '>';
        $output .= '<td class="title">' . filter_xss($line['title']) . ':</td>';
        $output .= '<td class="price">' . theme('uc_price', array('price' => $line['amount'])) . '</td>';
        $output .= '</tr>';
      }
    }
  }

  $output .= '</table>';

  return $output;
}

/**
 * Theming checkout review page.
 */
function vuvalu_uc_cart_checkout_review($variables) {

  $panes = $variables['panes'];
  $form = $variables['form'];

  $order = uc_order_load($_SESSION['cart_order']);
  $line_items = uc_order_load_line_items_display($order);
  $datos = array();
  foreach ($line_items as $line) {
    if ($line['type'] == 'total') {
      $datos['total'] = $line['amount'];
    }
    if ($line['type'] == 'shipping') {
      $datos['shipping'] = $line['amount'];
    }
  }
  foreach ($line_items as $line) {
    if (!empty($line['title'])) {
      if ($line['type'] == 'subtotal') {
        $line['amount'] = (isset($datos['total']) ? $datos['total'] : 0) - (isset($datos['shipping']) ? $datos['shipping'] : 0);
        $subtotal_price = theme('uc_price', array('price' => $line['amount']));
      }
    }
  }

  drupal_add_css(drupal_get_path('module', 'uc_cart') . '/uc_cart.css');

  $output = '<div id="review-instructions">' . filter_xss_admin(variable_get('uc_checkout_review_instructions', uc_get_message('review_instructions'))) . '</div>';

  $output .= '<table class="order-review-table">';

  foreach ($panes as $title => $data) {
    if ($title != 'Términos y Condiciones') {
      if ($title == 'Forma de pago') {
        $data[0]['data'] = $subtotal_price;
        unset($data[2]);
        unset($data[3]);
      }
      $output .= '<tr class="pane-title-row">';
      $output .= '<td colspan="2">' . $title . '</td>';
      $output .= '</tr>';
      if (is_array($data)) {
        foreach ($data as $row) {
          if (is_array($row)) {
            if (isset($row['border'])) {
              $border = ' class="row-border-' . $row['border'] . '"';
            }
            else {
              $border = '';
            }
            $output .= '<tr' . $border . '>';
            $output .= '<td class="title-col">' . $row['title'] . ':</td>';
            $output .= '<td class="data-col">' . $row['data'] . '</td>';
            $output .= '</tr>';
          }
          else {
            $output .= '<tr><td colspan="2">' . $row . '</td></tr>';
          }
        }
      }
      else {
        $output .= '<tr><td colspan="2">' . $data . '</td></tr>';
      }
    }
  }

  $output .= '<tr class="review-button-row">';
  $output .= '<td colspan="2">' . drupal_render($form) . '</td>';
  $output .= '</tr>';

  $output .= '</table>';

  return $output;
}

/**
 * Modificación para hacer que el list price salga vacío cuando es cero.
 *
 * Formats a product's price.
 *
 * @param $variables
 *   An associative array containing:
 *   - element: An associative array render element containing:
 *     - #value: Price to be formatted.
 *     - #attributes: (optional) Array of attributes to apply to enclosing DIV.
 *     - #title: (optional) Title to be used as label.
 *
 * @ingroup themeable
 */
function vuvalu_uc_product_price($variables) {
  $element = $variables['element'];
  $price = $element['#value'];
  $attributes = isset($element['#attributes']) ? $element['#attributes'] : array();
  $label = isset($element['#title']) ? $element['#title'] : '';

  if (isset($attributes['class'])) {
    array_unshift($attributes['class'], 'product-info');
  }
  else {
    $attributes['class'] = array('product-info');
  }

  if (($price == 0) and (in_array("list-price", $attributes['class']))) {
    $output = '';
  }
  else {
    $output = '<div ' . drupal_attributes($attributes) . '>';
    if ($label) {
      $output .= '<span class="uc-price-label">' . $label . '</span> ';
    }
    $vars = array('price' => $price);
    if (!empty($element['#suffixes'])) {
      $vars['suffixes'] = $element['#suffixes'];
    }
    $output .= theme('uc_price', $vars);
    $output .= drupal_render_children($element);
    $output .= '</div>';
  }

  return $output;
}

/**
 * Placeholder para Mailchimp
 */
function vuvalu_form_alter( &$form, &$form_state, $form_id )
{
    if (in_array( $form_id, array( 'mailchimp_signup_subscribe_block_suscripcion_al_newsletter_form')))
    {
		$form['mergevars']['EMAIL']['#attributes'] = array(
		 'placeholder'=> t('Escribe tu email'),
		);
    }
}

/**
 * Magnific Popup para nodo producto
 */
function vuvalu_preprocess_node(&$variables) {
  if ($variables['type'] == 'product') {
    drupal_add_js(drupal_get_path('theme', 'vuvalu') . '/js/jquery.magnific-popup.min.js');
    drupal_add_js(drupal_get_path('theme', 'vuvalu') . '/js/product-node-popup.js');
    drupal_add_css(drupal_get_path('theme', 'vuvalu') . '/magnific-popup.css');
  }
}