<?php

/**
 * @file
 * Views hooks for uc_custom_product_search.module.
 */

/**
 * Implements hook_views_query_alter().
 */
function uc_custom_product_search_views_query_alter(&$view, &$query) {
  if ($view->name == 'uc_custom_product_search') {
    foreach ($query->where as &$condition_group) {
      foreach ($condition_group['conditions'] as &$condition) {
        if (is_object($condition['value']) && (get_class($condition['value']) == 'SelectQuery')) {
          $condition_group['type'] = 'OR';
        }
      }
    }
  }
}

/**
 * Implements hook_views_post_execute().
 */
function uc_custom_product_search_views_post_execute(&$view) {
  if ($view->name == 'uc_custom_product_search') {
    $rows_count = count($view->result);
    foreach ($view->result as $key => $row) {
      $nid = $row->nid;
      $models = db_query("SELECT * FROM {uc_product_adjustments} WHERE nid = :nid", array(':nid' => $nid))->fetchAll();
      $exclude = TRUE;
      foreach ($view->exposed_raw_input['talla_1'] as $filter_oid) {
        foreach ($models as $comb) {
          $aid_oid = unserialize($comb->combination);
          $aid = key($aid_oid);
          $oid = $aid_oid[$aid];
          if ($aid == '4') {
            if ($oid == $filter_oid) {
              $stock_level = uc_stock_level($comb->model);
              if ($stock_level > 0) {
                $exclude = FALSE;
              }
            }
          }
        }
      }
      if ($exclude) {
        unset($view->result[$key]);
        $rows_count--;
      }
    }
    $view->total_rows = (string)$rows_count;
  }
}
