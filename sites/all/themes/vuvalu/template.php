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

/**
 * Returns HTML for an Colorbox image field formatter.
 *
 * @param array $variables
 *   An associative array containing:
 *   - item: An array of image data.
 *   - image_style: An optional image style.
 *   - path: An array containing the link 'path' and link 'options'.
 *
 * @return string
 *   An HTML string representing the themed output.
 *
 * @ingroup themeable
 */
function vuvalu_colorbox_image_formatter($variables) {
  static $gallery_token = NULL;
  $item = $variables['item'];
  $entity_type = $variables['entity_type'];
  $entity = $variables['entity'];
  $field = $variables['field'];
  $settings = $variables['display_settings'];

  $image = array(
    'path' => $item['uri'],
    'alt' => isset($item['alt']) ? $item['alt'] : '',
    'title' => isset($item['title']) ? $item['title'] : '',
    'style_name' => $settings['colorbox_node_style'],
  );
  if ($entity->type == 'product' && $field['field_name'] == 'field_guia_tallas' && $settings['colorbox_node_style'] == 'hide') {
    $image['size_guide'] = TRUE;
  }
  else {
    $image['size_guide'] = FALSE;
  }
  if ($variables['delta'] == 0 && !empty($settings['colorbox_node_style_first'])) {
    $image['style_name'] = $settings['colorbox_node_style_first'];
  }

  if (isset($item['width']) && isset($item['height'])) {
    $image['width'] = $item['width'];
    $image['height'] = $item['height'];
  }

  if (isset($item['attributes'])) {
    $image['attributes'] = $item['attributes'];
  }

  // Allow image attributes to be overridden.
  if (isset($variables['item']['override']['attributes'])) {
    foreach (array('width', 'height', 'alt', 'title') as $key) {
      if (isset($variables['item']['override']['attributes'][$key])) {
        $image[$key] = $variables['item']['override']['attributes'][$key];
        unset($variables['item']['override']['attributes'][$key]);
      }
    }
    if (isset($image['attributes'])) {
      $image['attributes'] = $variables['item']['override']['attributes'] + $image['attributes'];
    }
    else {
      $image['attributes'] = $variables['item']['override']['attributes'];
    }
  }

  $entity_title = entity_label($entity_type, $entity);

  switch ($settings['colorbox_caption']) {
    case 'auto':
      // If the title is empty use alt or the entity title in that order.
      if (!empty($image['title'])) {
        $caption = $image['title'];
      }
      elseif (!empty($image['alt'])) {
        $caption = $image['alt'];
      }
      elseif (!empty($entity_title)) {
        $caption = $entity_title;
      }
      else {
        $caption = '';
      }
      break;
    case 'title':
      $caption = $image['title'];
      break;
    case 'alt':
      $caption = $image['alt'];
      break;
    case 'node_title':
      $caption = $entity_title;
      break;
    case 'custom':
      $caption = token_replace($settings['colorbox_caption_custom'], array($entity_type => $entity, 'file' => (object) $item), array('clear' => TRUE));
      break;
    default:
      $caption = '';
  }

  // Shorten the caption for the example styles or when caption shortening is active.
  $colorbox_style = variable_get('colorbox_style', 'default');
  $trim_length = variable_get('colorbox_caption_trim_length', 75);
  if (((strpos($colorbox_style, 'colorbox/example') !== FALSE) || variable_get('colorbox_caption_trim', 0)) && (drupal_strlen($caption) > $trim_length)) {
    $caption = drupal_substr($caption, 0, $trim_length - 5) . '...';
  }

  // Build the gallery id.
  list($id, $vid, $bundle) = entity_extract_ids($entity_type, $entity);
  $entity_id = !empty($id) ? $entity_type . '-' . $id : 'entity-id';
  switch ($settings['colorbox_gallery']) {
    case 'post':
      $gallery_id = 'gallery-' . $entity_id;
      break;
    case 'page':
      $gallery_id = 'gallery-all';
      break;
    case 'field_post':
      $gallery_id = 'gallery-' . $entity_id . '-' . $field['field_name'];
      break;
    case 'field_page':
      $gallery_id = 'gallery-' . $field['field_name'];
      break;
    case 'custom':
      $gallery_id = $settings['colorbox_gallery_custom'];
      break;
    default:
      $gallery_id = '';
  }

  // If gallery id is not empty add unique per-request token to avoid images being added manually to galleries.
  if (!empty($gallery_id) && variable_get('colorbox_unique_token', 1)) {
    // Check if gallery token has already been set, we need to reuse the token for the whole request.
    if (is_null($gallery_token)) {
      // We use a short token since randomness is not critical.
      $gallery_token = drupal_random_key(8);
    }
    $gallery_id = $gallery_id . '-' . $gallery_token;
  }

  if ($style_name = $settings['colorbox_image_style']) {
    $path = image_style_url($style_name, $image['path']);
  }
  else {
    $path = file_create_url($image['path']);
  }

  return theme('colorbox_imagefield', array('image' => $image, 'path' => $path, 'title' => $caption, 'gid' => $gallery_id));
}

/**
 * Returns HTML for an image using a specific Colorbox image style.
 *
 * @param array $variables
 *   An associative array containing:
 *   - image: image item as array.
 *   - path: The path of the image that should be displayed in the Colorbox.
 *   - title: The title text that will be used as a caption in the Colorbox.
 *   - gid: Gallery id for Colorbox image grouping.
 *
 * @return string
 *   An HTML string containing a link to the given path.
 *
 * @ingroup themeable
 */
function vuvalu_colorbox_imagefield($variables) {

  $class = array('colorbox');

  if ($variables['image']['style_name'] == 'hide') {
    if ($variables['image']['size_guide']) {
      $image = t('Size guide');
    }
    else {
      $image = '';
      $class[] = 'js-hide';
    }
  }
  elseif (!empty($variables['image']['style_name'])) {
    $image = theme('image_style', $variables['image']);
  }
  else {
    $image = theme('image', $variables['image']);
  }

  $options = drupal_parse_url($variables['path']);
  $options += array(
    'html' => TRUE,
    'attributes' => array(
      'title' => $variables['title'],
      'class' => $class,
      'data-colorbox-gallery' => $variables['gid'],
    ),
  );

  return l($image, $options['path'], $options);
}