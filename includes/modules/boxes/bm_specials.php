<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class bm_specials {
    var $code = 'bm_specials';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function bm_specials() {
      $this->title = MODULE_BOXES_SPECIALS_TITLE;
      $this->description = MODULE_BOXES_SPECIALS_DESCRIPTION;

      if ( defined('MODULE_BOXES_SPECIALS_STATUS') ) {
        $this->sort_order = MODULE_BOXES_SPECIALS_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_SPECIALS_STATUS == 'True');

        $this->group = ((MODULE_BOXES_SPECIALS_CONTENT_PLACEMENT == 'Left Column') ? 'boxes_column_left' : 'boxes_column_right');
      }
    }

    function execute() {
      global $HTTP_GET_VARS, $languages_id, $currencies, $oscTemplate;

      $num_display = (MAX_RANDOM_SELECT_SPECIALS > 2) ? 2 : MAX_RANDOM_SELECT_SPECIALS;

      if (!isset($HTTP_GET_VARS['products_id'])) {
        $specials_products_query = tep_db_query ("select p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_status = '1' and p.products_id = s.products_id and pd.products_id = s.products_id and pd.language_id = '" . (int)$languages_id . "' and s.status = '1' order by s.specials_date_added desc limit " . $num_display);
        // if ($random_product = tep_random_select("select p.products_id, pd.products_name, p.products_price, p.products_tax_class_id, p.products_image, s.specials_new_products_price from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_SPECIALS . " s where p.products_status = '1' and p.products_id = s.products_id and pd.products_id = s.products_id and pd.language_id = '" . (int)$languages_id . "' and s.status = '1' order by s.specials_date_added desc limit " . MAX_RANDOM_SELECT_SPECIALS)) {
        $data = '<div class="grid_12 bottom_box box_with_image alpha">' .
                '  <div class="bottom_box_title">' . MODULE_BOXES_SPECIALS_BOX_TITLE . '</div>';
        $count = 1;
        while ($specials_product = tep_db_fetch_array($specials_products_query)) {
          if (tep_not_null($specials_product['specials_new_products_price'])) {
            $whats_new_price = '<del>' . $currencies->display_price($specials_product['products_price'], tep_get_tax_rate($specials_product['products_tax_class_id'])) . '</del><br />';
            $whats_new_price .= '<span class="productSpecialPrice">' . $currencies->display_price($specials_product['specials_new_products_price'], tep_get_tax_rate($specials_product['products_tax_class_id'])) . '</span>';
          } else {
            $whats_new_price = $currencies->display_price($specials_product['products_price'], tep_get_tax_rate($specials_product['products_tax_class_id']));
          }
          $div_cls = ($count == 1) ? "alpha" : (($count == $num_display) ? "omega" : "");
          $data .= '  <div class="bottom_box_items grid_6 ' . $div_cls . '" style="float: left"><ul><li><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials_product['products_id']) . '">' . tep_image(DIR_WS_IMAGES . $specials_product['products_image'], $specials_product['products_name'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT) . '</a><br /><a href="' . tep_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $specials_product['products_id']) . '">' . $specials_product['products_name'] . '</a><br />' . $whats_new_price . '</li></ul></div><!!-- ' . $count . ' - ' . $num_display . ' -->';
          $count ++;
        }
        $data .= '</div>';
        $this->html = $data;
        $oscTemplate->addBlock($data, $this->group);
      }
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_SPECIALS_STATUS');
    }

    function install() {
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Specials Module', 'MODULE_BOXES_SPECIALS_STATUS', 'True', 'Do you want to add the module to your shop?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Content Placement', 'MODULE_BOXES_SPECIALS_CONTENT_PLACEMENT', 'Right Column', 'Should the module be loaded in the left or right column?', '6', '1', 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\'), ', now())");
      tep_db_query("insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_BOXES_SPECIALS_SORT_ORDER', '0', 'Sort order of display. Lowest is displayed first.', '6', '0', now())");
    }

    function remove() {
      tep_db_query("delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
      return array('MODULE_BOXES_SPECIALS_STATUS', 'MODULE_BOXES_SPECIALS_CONTENT_PLACEMENT', 'MODULE_BOXES_SPECIALS_SORT_ORDER');
    }
  }
?>
