<?php

/**
 * @file
 * Page callback for Uc custom product search.
 */

/**
 * Uc custom product search settings form.
 */
function uc_custom_product_search_settings_form($form, &$form_state) {
  $form['uc_custom_product_search'] = array(
    '#type' => 'fieldset',
    '#title' => t('Uc custom product search settings'),
    '#weight' => -3,
  );
  $vocabularies = taxonomy_get_vocabularies();
  $options = array(
    '0' => '- None -'
  );
  foreach ($vocabularies as $vid => $vocabulary) {
    $options[$vid] = check_plain($vocabulary->name);
  }
  $form['uc_custom_product_search']['uc_custom_product_search_vocabulary'] = array(
    '#type' => 'select',
    '#title' => t('Vocabulary'),
    '#default_value' => variable_get('uc_custom_product_search_vocabulary', ''),
    '#options' => $options,
    '#description' => t('Select the taxonomy vocabulary to use.'),
    '#ajax' => array(
      'callback' => 'uc_custom_product_search_taxonomy_callback',
      'wrapper' => 'product-search-taxonomy-options',
      'effect' => 'fade',
    ),
  );

  $form['uc_custom_product_search']['product_search_taxonomy_options'] = array(
    '#prefix' => '<div id="product-search-taxonomy-options">',
    '#suffix' => '</div>',
  );
  $render = FALSE;
  if (!isset($form_state['values']['uc_custom_product_search_vocabulary'])) {
    if (variable_get('uc_custom_product_search_vocabulary', '') != '') {
      $render = TRUE;
      $tree = taxonomy_get_tree(variable_get('uc_custom_product_search_vocabulary', ''));
    }
  }
  else {
    if ($form_state['values']['uc_custom_product_search_vocabulary'] != '0') {
      $render = TRUE;
      $tree = taxonomy_get_tree($form_state['values']['uc_custom_product_search_vocabulary']);
    }
  }
  if ($render) {
    $options = array();
    foreach ($tree as $term) {
      if ($term->parents[0] == '0') {
        $options[$term->tid] = $term->name;
      }
    }
    $form['uc_custom_product_search']['product_search_taxonomy_options']['uc_custom_product_search_parent_terms'] = array(
      '#type' => 'select',
      '#title' => t('Parent terms'),
      '#multiple' => TRUE,
      '#options' => $options,
      '#default_value' => variable_get('uc_custom_product_search_parent_terms', array()),
      '#description' => t('Select the terms whose children will show as options to filter search.'),
    );
  }
  $form['uc_custom_product_search']['attributes'] = array(
    '#type' => 'fieldset',
    '#title' => t('Attributes'),
    '#description' => t('Select the ubercart attributes to use.'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  if (variable_get('uc_custom_product_search_attribute_size_1', '') != '') {
    $size_1_disabled = TRUE;
  }
  $form['uc_custom_product_search']['attributes']['uc_custom_product_search_attribute_size_1'] = array(
    '#type' => 'select',
    '#title' => t('Size attribute 1'),
    '#options' => uc_custom_product_search_attributes_select(),
    '#default_value' => variable_get('uc_custom_product_search_attribute_size_1', ''),
    '#disabled' => $size_1_disabled,
  );
  if (variable_get('uc_custom_product_search_attribute_size_1', '') != '') {
    $form['uc_custom_product_search']['options'] = array(
      '#type' => 'fieldset',
      '#title' => t('Options'),
      '#description' => t('Select the ubercart options to use.'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
    );
    $attribute = uc_attribute_load(variable_get('uc_custom_product_search_attribute_size_1', ''));
    $form['uc_custom_product_search']['options']['uc_custom_product_search_attribute_size_1_options'] = array(
      '#type' => 'checkboxes',
      '#title' => t('Select options from attribute %attribute to use in filters', array('%attribute' => check_plain($attribute->name))),
      '#options' => uc_custom_product_search_attribute_options_checkboxes($attribute),
      '#default_value' => variable_get('uc_custom_product_search_attribute_size_1_options', array()),
    );
  }
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Submit'),
  );
  return $form;
}

/**
 * Ajax callback for taxonomy terms.
 */
function uc_custom_product_search_taxonomy_callback($form, $form_state) {
  if (!empty($form_state['values']['uc_custom_product_search_vocabulary']) && $form_state['values']['uc_custom_product_search_vocabulary'] != variable_get('uc_custom_product_search_vocabulary', '')) {
    $form['uc_custom_product_search']['product_search_taxonomy_options']['uc_custom_product_search_parent_terms']['#value'] = array();
  }
  else {
    $form['uc_custom_product_search']['product_search_taxonomy_options']['uc_custom_product_search_parent_terms']['#value'] = variable_get('uc_custom_product_search_parent_terms', array());
  }
  return $form['uc_custom_product_search']['product_search_taxonomy_options'];
}

/**
 * Handle form submission.
 */
function uc_custom_product_search_settings_form_submit($form, &$form_state) {
  if ($form_state['values']['uc_custom_product_search_vocabulary'] == '0') {
    variable_del('uc_custom_product_search_vocabulary');
    variable_del('uc_custom_product_search_parent_terms');
  }
  else {
    variable_set('uc_custom_product_search_vocabulary', $form_state['values']['uc_custom_product_search_vocabulary']);
    variable_set('uc_custom_product_search_parent_terms', $form_state['values']['uc_custom_product_search_parent_terms']);
  }
  variable_set('uc_custom_product_search_attribute_size_1', $form_state['values']['uc_custom_product_search_attribute_size_1']);
  variable_set('uc_custom_product_search_attribute_size_1_options', $form_state['values']['uc_custom_product_search_attribute_size_1_options']);
  $form_state['redirect'] = 'admin/store/settings/product-search';
}