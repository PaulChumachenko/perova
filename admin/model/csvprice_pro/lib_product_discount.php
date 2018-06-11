<?php
class ModelCSVPriceProLibProductDiscount extends Model {

	//-------------------------------------------------------------------------
	// PRODUCT EXPORT - Get Product Discount
	//-------------------------------------------------------------------------
	public function getProductDiscount($product_id) {

		$discount = array();
		$query = $this->db->query('SELECT CONCAT( customer_group_id, \',\', quantity, \',\', priority, \',\', TRUNCATE(price, 2), \',\', date_start, \',\', date_end) AS p_discount  FROM `' . DB_PREFIX . 'product_discount` WHERE product_id = ' . (int)$product_id);

		foreach ( $query->rows as $result ) {
			$discount[] = $result['p_discount'];
		}
		return implode("\n", $discount);
	}

	//-------------------------------------------------------------------------
	// PRODUCT IMPORT - Update Discount
	//-------------------------------------------------------------------------
	public function addProductDiscount($product_id, $discount) {
		// Delete Old Data
		$this->db->query('DELETE FROM `' . DB_PREFIX . 'product_discount` WHERE product_id = \'' . (int)$product_id . '\'');

		if ( empty($discount) ) {
			return;
		}

		$product_discount = explode("\n", $discount);

		$discount_data = array();

		foreach ( $product_discount as $str ) {
			$discount_data[] = explode(',', trim($str));
		}
		unset($product_discount);

		if ( !empty($discount_data) ) {
			foreach ( $discount_data as $product_discount ) {
				if ( count($product_discount) < 4 ) {
					continue;
				}
				$this->db->query('INSERT INTO `' . DB_PREFIX . 'product_discount`
					SET product_id = \'' . (int)$product_id . '\', 
					customer_group_id = \'' . (int)$product_discount[0] . '\', 
					quantity = \'' . (int)$product_discount[1] . '\', 
					priority = \'' . (int)$product_discount[2] . '\', 
					price = \'' . $this->model_csvprice_pro_app_product->validateNumberFloat($product_discount[3]) . '\', 
					date_start = \'' . ((isset($product_discount[4])) ? $this->db->escape($product_discount[4]) : '') . '\', 
					date_end = \'' . ((isset($product_discount[5])) ? $this->db->escape($product_discount[5]) : '') . '\'');
			}
		}
	}

}
?>