<?php
/**
 * @file
 * Page callback for Ubercart product importer settings form.
 */

/**
 * Ubercart product importer settings form.
 */
function uc_importer_margaritaandco_settings_form($form, &$form_state) {
  $form['uc_importer_margaritaandco'] = array(
    '#type' => 'fieldset',
    '#title' => t('Ubercart product importer settings'),
    '#group' => 'product-settings',
    '#weight' => -3,
  );
  $form['uc_importer_margaritaandco']['uc_importer_margaritaandco_enable'] = array(
    '#type' => 'checkbox',
    '#title' => t('Enable import on cron'),
    '#default_value' => variable_get('uc_importer_margaritaandco_enable', TRUE),
    '#description' => t('Uncheck this to deactivate the execution during cron.'),
  );
  $form['uc_importer_margaritaandco']['uc_importer_margaritaandco_import_folder'] = array(
    '#type' => 'textfield',
    '#title' => t('File import folder'),
    '#required' => TRUE,
    '#default_value' => variable_get('uc_importer_margaritaandco_import_folder', 'public://uc-importer-margaritaandco/import'),
    '#description' => t('Select the folder where the import files will be located.'),
  );
  $form['uc_importer_margaritaandco']['uc_importer_margaritaandco_processed_folder'] = array(
    '#type' => 'textfield',
    '#title' => t('Processed files folder'),
    '#required' => TRUE,
    '#default_value' => variable_get('uc_importer_margaritaandco_processed_folder', 'public://uc-importer-margaritaandco/processed'),
    '#description' => t('Select the folder where the processed files will be located.'),
  );
  $product_node_type = variable_get('uc_importer_margaritaandco_content_type', '');
  $form['uc_importer_margaritaandco']['uc_importer_margaritaandco_content_type'] = array(
    '#type' => 'select',
    '#title' => t('Product'),
    '#default_value' => $product_node_type,
    '#disabled' => ($product_node_type == '') ? FALSE : TRUE,
    '#options' => node_type_get_names(),
    '#description' => t('Select the product content type.'),
  );
  $aid = variable_get('uc_importer_margaritaandco_attribute', '');
  $form['uc_importer_margaritaandco']['uc_importer_margaritaandco_attribute'] = array(
    '#type' => 'select',
    '#title' => t('Attribute'),
    '#default_value' => $aid,
    '#required' => TRUE,
    '#disabled' => ($aid == '') ? FALSE : TRUE,
    '#options' => uc_importer_margaritaandco_attributes_select(),
    '#description' => t('Select the attribute to use.'),
  );
  if (!empty($aid)) {
    $attribute = uc_attribute_load($aid);
    $form['uc_importer_margaritaandco']['uc_importer_margaritaandco_attribute_mapping'] = array(
      '#type' => 'fieldset',
      '#title' => t('Map options id with external reference'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
      '#tree' => TRUE,
    );
    foreach ($attribute->options as $oid => $option) {
      $form['uc_importer_margaritaandco']['uc_importer_margaritaandco_attribute_mapping']["uc_importer_margaritaandco_option_$oid"] = array(
        '#type' => 'textfield',
        '#title' => $option->name,
        '#default_value' => variable_get("uc_importer_margaritaandco_option_$oid", ''),
        '#description' => t('External reference corresponding to this option.'),
      );
    }
  }
  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Submit'),
  );
  return $form;
}

/**
 * Handle form validation.
 */
function uc_importer_margaritaandco_settings_form_validate($form, &$form_state) {
  if (!file_prepare_directory($form_state['values']['uc_importer_margaritaandco_import_folder'])) {
    form_set_error('uc_importer_margaritaandco_import_folder', t('The folder does not exists or is not writable by the web server.'));
  }
  if (!file_prepare_directory($form_state['values']['uc_importer_margaritaandco_processed_folder'])) {
    form_set_error('uc_importer_margaritaandco_processed_folder', t('The folder does not exists or is not writable by the web server.'));
  }
}

/**
 * Handle form submission.
 */
function uc_importer_margaritaandco_settings_form_submit($form, &$form_state) {
  //Guardar valores en las variables
  variable_set('uc_importer_margaritaandco_enable', $form_state['values']['uc_importer_margaritaandco_enable']);
  variable_set('uc_importer_margaritaandco_import_folder', $form_state['values']['uc_importer_margaritaandco_import_folder']);
  variable_set('uc_importer_margaritaandco_processed_folder', $form_state['values']['uc_importer_margaritaandco_processed_folder']);
  variable_set('uc_importer_margaritaandco_content_type', $form_state['values']['uc_importer_margaritaandco_content_type']);
  variable_set('uc_importer_margaritaandco_attribute', $form_state['values']['uc_importer_margaritaandco_attribute']);
  foreach ($form_state['values']['uc_importer_margaritaandco_attribute_mapping'] as $variable_name => $option_value) {
    variable_set($variable_name, $option_value);
  }
  $form_state['redirect'] = 'admin/store/settings/importer';
}