<?php
class ModelCSVPriceProLibProductSpecial extends Model {

	//-------------------------------------------------------------------------
	// PRODUCT EXPORT - Get Product Special
	//-------------------------------------------------------------------------
	public function getProductSpecial($product_id) {

		$special = array();
		$query = $this->db->query('SELECT CONCAT( ps.customer_group_id, \',\', ps.priority, \',\', TRUNCATE(ps.price, 2), \',\', ps.date_start, \',\', ps.date_end) AS p_special FROM `' . DB_PREFIX . 'product_special` ps WHERE ps.product_id = ' . (int)$product_id);
		foreach ( $query->rows as $result ) {
			$special[] = $result['p_special'];
		}
		return implode("\n", $special);
	}

	//-------------------------------------------------------------------------
	// PRODUCT IMPORT - Add Product Special
	//-------------------------------------------------------------------------
	public function addProductSpecial($product_id, $special) {
		// Delete Old Data
		$this->db->query('DELETE FROM `' . DB_PREFIX . 'product_special` WHERE product_id = \'' . (int)$product_id . '\'');

		if ( empty($special) ) {
			return;
		}

		$product_special = explode("\n", $special);

		$special_data = array();

		foreach ( $product_special as $str ) {
			$special_data[] = explode(',', trim($str));
		}
		unset($product_special);

		if ( !empty($special_data) ) {
			foreach ( $special_data as $product_special ) {
				if ( count($product_special) < 3 ) {
					continue;
				}
				$this->db->query('INSERT INTO `' . DB_PREFIX . 'product_special`
					SET product_id = \'' . (int)$product_id . '\', 
					customer_group_id = \'' . (int)$product_special[0] . '\', 
					priority = \'' . (int)$product_special[1] . '\', 
					price = \'' . $this->model_csvprice_pro_app_product->validateNumberFloat($product_special[2]) . '\', 
					date_start = \'' . ((isset($product_special[3])) ? $this->db->escape($product_special[3]) : '') . '\', 
					date_end = \'' . ((isset($product_special[4])) ? $this->db->escape($product_special[4]) : '') . '\'');
			}
		}
	}

}
?>