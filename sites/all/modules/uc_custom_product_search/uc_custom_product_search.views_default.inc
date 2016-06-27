<?php
/**
 * @file
 * Default views for uc_custom_product_search.module.
 */

/**
 * Implements hook_views_default_views().
 */
function uc_custom_product_search_views_default_views() {

  $view = new view();
  $view->name = 'uc_custom_product_search';
  $view->description = '';
  $view->tag = 'default';
  $view->base_table = 'node';
  $view->human_name = 'Uc custom Product Search';
  $view->core = 7;
  $view->api_version = '3.0';
  $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

  /* Display: Master */
  $handler = $view->new_display('default', 'Master', 'default');
  $handler->display->display_options['use_more_always'] = FALSE;
  $handler->display->display_options['use_more_text'] = 'más';
  $handler->display->display_options['access']['type'] = 'perm';
  $handler->display->display_options['cache']['type'] = 'none';
  $handler->display->display_options['query']['type'] = 'views_query';
  $handler->display->display_options['query']['options']['distinct'] = TRUE;
  $handler->display->display_options['exposed_form']['type'] = 'input_required';
  $handler->display->display_options['exposed_form']['options']['submit_button'] = 'Aplicar';
  $handler->display->display_options['exposed_form']['options']['reset_button_label'] = 'Reiniciar';
  $handler->display->display_options['exposed_form']['options']['exposed_sorts_label'] = 'Ordenar por';
  $handler->display->display_options['exposed_form']['options']['expose_sort_order'] = FALSE;
  $handler->display->display_options['exposed_form']['options']['text_input_required'] = '';
  $handler->display->display_options['exposed_form']['options']['text_input_required_format'] = 'filtered_html';
  $handler->display->display_options['pager']['type'] = 'full';
  $handler->display->display_options['pager']['options']['items_per_page'] = '25';
  $handler->display->display_options['pager']['options']['offset'] = '0';
  $handler->display->display_options['pager']['options']['id'] = '0';
  $handler->display->display_options['pager']['options']['quantity'] = '9';
  $handler->display->display_options['pager']['options']['expose']['items_per_page_label'] = 'Elementos por página';
  $handler->display->display_options['pager']['options']['expose']['items_per_page_options_all_label'] = '- Todos -';
  $handler->display->display_options['pager']['options']['expose']['offset_label'] = 'Desplazamiento';
  $handler->display->display_options['pager']['options']['tags']['first'] = '« primera';
  $handler->display->display_options['pager']['options']['tags']['previous'] = '‹ anterior';
  $handler->display->display_options['pager']['options']['tags']['next'] = 'siguiente ›';
  $handler->display->display_options['pager']['options']['tags']['last'] = 'última »';
  $handler->display->display_options['style_plugin'] = 'default';
  $handler->display->display_options['row_plugin'] = 'fields';
  /* Campo: Contenido: Imagen */
  $handler->display->display_options['fields']['uc_product_image']['id'] = 'uc_product_image';
  $handler->display->display_options['fields']['uc_product_image']['table'] = 'field_data_uc_product_image';
  $handler->display->display_options['fields']['uc_product_image']['field'] = 'uc_product_image';
  $handler->display->display_options['fields']['uc_product_image']['label'] = '';
  $handler->display->display_options['fields']['uc_product_image']['element_type'] = '0';
  $handler->display->display_options['fields']['uc_product_image']['element_label_colon'] = FALSE;
  $handler->display->display_options['fields']['uc_product_image']['element_wrapper_type'] = '0';
  $handler->display->display_options['fields']['uc_product_image']['element_default_classes'] = FALSE;
  $handler->display->display_options['fields']['uc_product_image']['click_sort_column'] = 'fid';
  $handler->display->display_options['fields']['uc_product_image']['settings'] = array(
    'image_style' => 'thumbnail-460x500',
    'image_link' => 'content',
  );
  $handler->display->display_options['fields']['uc_product_image']['delta_limit'] = '1';
  $handler->display->display_options['fields']['uc_product_image']['delta_offset'] = '0';
  $handler->display->display_options['fields']['uc_product_image']['field_api_classes'] = TRUE;
  /* Campo: Contenido: Título */
  $handler->display->display_options['fields']['title']['id'] = 'title';
  $handler->display->display_options['fields']['title']['table'] = 'node';
  $handler->display->display_options['fields']['title']['field'] = 'title';
  $handler->display->display_options['fields']['title']['label'] = '';
  $handler->display->display_options['fields']['title']['alter']['word_boundary'] = FALSE;
  $handler->display->display_options['fields']['title']['alter']['ellipsis'] = FALSE;
  /* Campo: Producto: Precio mostrado */
  $handler->display->display_options['fields']['display_price']['id'] = 'display_price';
  $handler->display->display_options['fields']['display_price']['table'] = 'uc_products';
  $handler->display->display_options['fields']['display_price']['field'] = 'display_price';
  $handler->display->display_options['fields']['display_price']['label'] = '';
  $handler->display->display_options['fields']['display_price']['element_label_colon'] = FALSE;
  /* Criterio de ordenación: Contenido: Fecha del envío */
  $handler->display->display_options['sorts']['created']['id'] = 'created';
  $handler->display->display_options['sorts']['created']['table'] = 'node';
  $handler->display->display_options['sorts']['created']['field'] = 'created';
  $handler->display->display_options['sorts']['created']['order'] = 'DESC';
  /* Criterios de filtrado: Contenido: Publicado */
  $handler->display->display_options['filters']['status']['id'] = 'status';
  $handler->display->display_options['filters']['status']['table'] = 'node';
  $handler->display->display_options['filters']['status']['field'] = 'status';
  $handler->display->display_options['filters']['status']['value'] = 1;
  $handler->display->display_options['filters']['status']['group'] = 1;
  $handler->display->display_options['filters']['status']['expose']['operator'] = FALSE;
  /* Criterios de filtrado: Contenido: Tipo */
  $handler->display->display_options['filters']['type']['id'] = 'type';
  $handler->display->display_options['filters']['type']['table'] = 'node';
  $handler->display->display_options['filters']['type']['field'] = 'type';
  $handler->display->display_options['filters']['type']['value'] = array(
    'product' => 'product',
  );
  $handler->display->display_options['filters']['type']['group'] = 1;
  /* Criterios de filtrado: Contenido: Tiene un término de taxonomía */
  $handler->display->display_options['filters']['tid']['id'] = 'tid';
  $handler->display->display_options['filters']['tid']['table'] = 'taxonomy_index';
  $handler->display->display_options['filters']['tid']['field'] = 'tid';
  $handler->display->display_options['filters']['tid']['group'] = 1;
  $handler->display->display_options['filters']['tid']['exposed'] = TRUE;
  $handler->display->display_options['filters']['tid']['expose']['operator_id'] = 'tid_op';
  $handler->display->display_options['filters']['tid']['expose']['label'] = 'taxonomía';
  $handler->display->display_options['filters']['tid']['expose']['operator'] = 'tid_op';
  $handler->display->display_options['filters']['tid']['expose']['identifier'] = 'catalogo';
  $handler->display->display_options['filters']['tid']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['tid']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    4 => 0,
    3 => 0,
    5 => 0,
  );
  $handler->display->display_options['filters']['tid']['type'] = 'select';
  $handler->display->display_options['filters']['tid']['vocabulary'] = 'catalog';
  /* Criterios de filtrado: Producto: Attribute: Tallas bebe */
  $handler->display->display_options['filters']['attr_1']['id'] = 'attr_1';
  $handler->display->display_options['filters']['attr_1']['table'] = 'uc_product_options';
  $handler->display->display_options['filters']['attr_1']['field'] = 'attr_1';
  $handler->display->display_options['filters']['attr_1']['group'] = 1;
  $handler->display->display_options['filters']['attr_1']['exposed'] = TRUE;
  $handler->display->display_options['filters']['attr_1']['expose']['operator_id'] = 'attr_1_op';
  $handler->display->display_options['filters']['attr_1']['expose']['label'] = 'Attribute: Tallas bebe';
  $handler->display->display_options['filters']['attr_1']['expose']['operator'] = 'attr_1_op';
  $handler->display->display_options['filters']['attr_1']['expose']['identifier'] = 'talla_1';
  $handler->display->display_options['filters']['attr_1']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['attr_1']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    4 => 0,
    3 => 0,
    5 => 0,
  );
  /* Criterios de filtrado: Producto: Attribute: Tallas niño */
  $handler->display->display_options['filters']['attr_2']['id'] = 'attr_2';
  $handler->display->display_options['filters']['attr_2']['table'] = 'uc_product_options';
  $handler->display->display_options['filters']['attr_2']['field'] = 'attr_2';
  $handler->display->display_options['filters']['attr_2']['group'] = 1;
  $handler->display->display_options['filters']['attr_2']['exposed'] = TRUE;
  $handler->display->display_options['filters']['attr_2']['expose']['operator_id'] = 'attr_2_op';
  $handler->display->display_options['filters']['attr_2']['expose']['label'] = 'Attribute: Tallas niño';
  $handler->display->display_options['filters']['attr_2']['expose']['operator'] = 'attr_2_op';
  $handler->display->display_options['filters']['attr_2']['expose']['identifier'] = 'talla_2';
  $handler->display->display_options['filters']['attr_2']['expose']['multiple'] = TRUE;
  $handler->display->display_options['filters']['attr_2']['expose']['remember_roles'] = array(
    2 => '2',
    1 => 0,
    4 => 0,
    3 => 0,
    5 => 0,
  );

  /* Display: Página */
  $handler = $view->new_display('page', 'Página', 'page_1');
  $handler->display->display_options['exposed_block'] = TRUE;
  $handler->display->display_options['path'] = 'buscador-productos';
  $translatables['uc_custom_product_search'] = array(
    t('Master'),
    t('más'),
    t('Aplicar'),
    t('Reiniciar'),
    t('Ordenar por'),
    t('Asc'),
    t('Desc'),
    t('Elementos por página'),
    t('- Todos -'),
    t('Desplazamiento'),
    t('« primera'),
    t('‹ anterior'),
    t('siguiente ›'),
    t('última »'),
    t('.'),
    t(','),
    t('taxonomía'),
    t('Attribute: Tallas bebe'),
    t('Attribute: Tallas niño'),
    t('Página'),
  );

  $views[$view->name] = $view;

  return $views;
}