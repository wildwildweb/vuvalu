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
