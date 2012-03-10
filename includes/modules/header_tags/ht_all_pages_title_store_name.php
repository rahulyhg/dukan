<?php
/*
  $Id: ht_all_pages_title_store_name.php v1.0 20101129 Kymation $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2010 osCommerce

  Released under the GNU General Public License
*/

  class ht_all_pages_title_store_name {
    var $code = 'ht_all_pages_title_store_name';
    var $group = 'header_tags';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function ht_all_pages_title_store_name() {
      $this->title = MODULE_HEADER_TAGS_ALL_PAGE_TITLE_STORE_NAME_TITLE;
      $this->description = MODULE_HEADER_TAGS_ALL_PAGE_TITLE_STORE_NAME_DESCRIPTION;

      if ( defined('MODULE_HEADER_TAGS_ALL_PAGE_TITLE_STORE_NAME_STATUS') ) {
        $this->sort_order = MODULE_HEADER_TAGS_ALL_PAGE_TITLE_STORE_NAME_SORT_ORDER;
        $this->enabled = (MODULE_HEADER_TAGS_ALL_PAGE_TITLE_STORE_NAME_STATUS == 'True');
      }
    }

    function execute() {
      global $oscTemplate;

      $head_title = STORE_NAME;

      if( strlen( $oscTemplate->getTitle() ) > 0 ) {
        $head_title = $oscTemplate->getTitle() . MODULE_HEADER_TAGS_ALL_PAGE_TITLE_STORE_NAME_SEPARATOR . ' ' . $head_title;
      }

      $oscTemplate->setTitle( $head_title );  // Save the new title string
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined( 'MODULE_HEADER_TAGS_ALL_PAGE_TITLE_STORE_NAME_STATUS' );
    }

    function install() {
      tep_db_query( "insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, set_function, date_added) values ('Enable Store Name in All Titles', 'MODULE_HEADER_TAGS_ALL_PAGE_TITLE_STORE_NAME_STATUS', 'True', 'Do you want to add the store name to all head titles?', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())" );
      tep_db_query( "insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Sort Order', 'MODULE_HEADER_TAGS_ALL_PAGE_TITLE_STORE_NAME_SORT_ORDER', '920000', 'Sort order of display. Lowest is displayed first.', '6', '2', now())" );
      tep_db_query( "insert into " . TABLE_CONFIGURATION . " (configuration_title, configuration_key, configuration_value, configuration_description, configuration_group_id, sort_order, date_added) values ('Separator', 'MODULE_HEADER_TAGS_ALL_PAGE_TITLE_STORE_NAME_SEPARATOR', '-', 'The separator to put between this element and the previous element.', '6', '3', now())" );
    }

    function remove() {
      tep_db_query( "delete from " . TABLE_CONFIGURATION . " where configuration_key in ('" . implode("', '", $this->keys()) . "')");
    }

    function keys() {
    	$keys_array = array();

      $keys_array[] = 'MODULE_HEADER_TAGS_ALL_PAGE_TITLE_STORE_NAME_STATUS';
      $keys_array[] = 'MODULE_HEADER_TAGS_ALL_PAGE_TITLE_STORE_NAME_SORT_ORDER';
      $keys_array[] = 'MODULE_HEADER_TAGS_ALL_PAGE_TITLE_STORE_NAME_SEPARATOR';

      return $keys_array;
    }
  }
?>
