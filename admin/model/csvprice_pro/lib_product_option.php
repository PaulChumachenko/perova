<?php
class ModelCSVPriceProLibProductOption extends Model {

	private static $LanguageID = NULL;
	private static $Languages = NULL;
	private static $ProfileSetting = NULL;
	
	//-------------------------------------------------------------------------
	// PRODUCT IMPORT - Set Profile Settings
	//-------------------------------------------------------------------------
	public function setProfileSettings($profile) {
		$this->ProfileSetting = $profile;
	}
	
	//-------------------------------------------------------------------------
	// Set Language Id
	//-------------------------------------------------------------------------
	public function setLanguageId($language_id) {
		$this->LanguageID = $language_id;
	}

	//-------------------------------------------------------------------------
	// PRODUCT EXPORT - Get Product Options
	//-------------------------------------------------------------------------
	public function getProductOptions($product_id) {

		$product_option_data = array();
		$product_option_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option po
                        LEFT JOIN `" . DB_PREFIX . "option` o ON (po.option_id = o.option_id)
                        LEFT JOIN " . DB_PREFIX . "option_description od ON (o.option_id = od.option_id)
                        WHERE po.product_id = '" . (int)$product_id . "'
                                AND od.language_id = '" . (int)$this->LanguageID . "'");

		if ( empty($product_option_query->row) ) {
			return false;
		}

		foreach ( $product_option_query->rows as $product_option ) {

			if ( $product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image' ) {
				$product_option_value_data = array();

				$product_option_value_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_option_value pov 
                                	LEFT JOIN " . DB_PREFIX . "option_value ov ON (pov.option_value_id = ov.option_value_id) 
                                	LEFT JOIN " . DB_PREFIX . "option_value_description ovd ON (ov.option_value_id = ovd.option_value_id) 
                                	WHERE pov.product_option_id = '" . (int)$product_option['product_option_id'] . "' AND ovd.language_id = '" . (int)$this->LanguageID . "' ORDER BY ov.sort_order");

				foreach ( $product_option_value_query->rows as $product_option_value ) {
					if ( !empty($product_option_value['image']) ) {
						$product_option_value['image'] = '|' . $product_option_value['image'];
					}

					$product_option_data[] = $product_option['type'] . '|' . $product_option['name'] . '|' . $product_option_value['name'] . '|' . $product_option['required'] . '|' . $product_option_value['quantity'] . '|' . $product_option_value['subtract'] . '|' . $product_option_value['price_prefix'] . '|' . $product_option_value['price'] . '|' . $product_option_value['points_prefix'] . '|' . $product_option_value['points'] . '|' . $product_option_value['weight_prefix'] . '|' . $product_option_value['weight'] . $product_option_value['image'];
				}

			} else {
				if ( isset($product_option['value']) && !empty($product_option['value']) ) {
					$po_value = '|' . $product_option['value'];
				} else {
					$po_value = '';
				}
				$product_option_data[] = $product_option['type'] . '|' . $product_option['name'] . '|' . $product_option['required'] . $po_value;
			}
		}

		$str = implode("\n", $product_option_data);
		return htmlspecialchars_decode($str);
	}

	//-------------------------------------------------------------------------
	// PRODUCT IMPORT - Add Product Options
	//-------------------------------------------------------------------------
	public function addProductOptions($product_id, $options) {

		if ( empty($this->Languages) ) {
			$this->Languages = $this->getLanguages();
		}

		// Delete old product option
		$this->db->query('DELETE FROM `' . DB_PREFIX . 'product_option` WHERE  product_id = \'' . (int)$product_id . '\'');
		$this->db->query('DELETE FROM `' . DB_PREFIX . 'product_option_value` WHERE  product_id = \'' . (int)$product_id . '\'');

		if ( empty($options) ) {
			return;
		}
		$data = explode("\n", $options);

		if ( empty($data) )
			return;

		// Options Type
		$option_type = array('select', 'radio', 'checkbox', 'image', 'date', 'time', 'datetime', 'text', 'textarea', 'file');
		$option_type_select = array('select', 'radio', 'checkbox', 'image');
		$option_type_datetime = array('date', 'time', 'datetime');
		$option_type_text = array('text', 'textarea');
		$option_type_file = array('file');

		foreach ( $data as $option_string ) {

			$option_string = trim($option_string, " \n\r\t");
			if ( empty($option_string) ) {
				continue;
			}

			$option = explode('|', $option_string);
			if ( empty($option) && count($option) < 2 ) {
				continue;
			}

			// Known Type
			//-------------------------------------------------------------------------
			if ( in_array($option[0], $option_type) ) {

				$id = $this->getOptionValueID($option);
				$option_id = $id[0];
				$option_value_id = $id[1];

				// Image
				if ( isset($option[12]) ) {
					$this->db->query('UPDATE `' . DB_PREFIX . 'option_value` SET image = \'' . $this->db->escape($option[12]) . '\' WHERE option_value_id = \'' . (int)$option_value_id . '\' AND option_id = \'' . $option_id . '\'');
				}

				if ( in_array($option[0], $option_type_select) ) {
					// Add new product option
					//-------------------------------------------------------------------------
					/* [0] = type
					 [1] = option_name
					 [2] = value_name
					 [3] = required
					 [4] = quantity
					 [5] = subtract
					 [6] = price_prefix
					 [7] = price
					 [8] = points_prefix
					 [9] = points
					 [10] = weight_prefix
					 [11] = weight
					 [12] = image */
					$product_option_id = $this->getProductOptionID($product_id, $option_id, $option[3]);

					$option = $this->validateProductOption($option);

					// Points
					if ( !isset($option[8]) ) {
						$option[8] = $this->ProfileSetting['option_points_prefix'];
						$option[9] = $this->ProfileSetting['option_points_default'];
					}

					// Weight
					if ( !isset($option[10]) ) {
						$option[10] = $this->ProfileSetting['option_weight_prefix'];
						$option[11] = $this->ProfileSetting['option_weight_default'];
					}

					$this->db->query('INSERT INTO `' . DB_PREFIX . 'product_option_value` SET
					      product_option_id = \'' . (int)$product_option_id . '\',
						     product_id = \'' . (int)$product_id . '\',
						      option_id = \'' . (int)$option_id . '\',
						option_value_id = \'' . (int)$option_value_id . '\',
						       quantity = \'' . (int)$option[4] . '\',
						       subtract = \'' . (int)$option[5] . '\',
						   price_prefix = \'' . trim($option[6]) . '\',
						  	  price = \'' . $option[7] . '\',
						  points_prefix = \'' . trim($option[8]) . '\',
						         points = \'' . $option[9] . '\',
						  weight_prefix = \'' . trim($option[10]) . '\',
						         weight = \'' . $option[11] . '\';');

				} elseif ( in_array($option[0], $option_type_datetime) || in_array($option[0], $option_type_text) || $option[0] == 'file' ) {

					if ( !isset($option[3]) ) {
						$option[3] = '';
					}

					$product_option_id = $this->getProductOptionID($product_id, $option_id, $option[2]);

					if ( $option[0] != 'file' ) {
						$this->db->query('UPDATE `' . DB_PREFIX . 'product_option` SET `value` = \'' . $this->db->escape($option[3]) . '\' WHERE product_option_id = \'' . (int)$product_option_id . '\';');
					}
				}
				// END Added By Type
				continue;

				// FORMAT: option_name|option_value_name|price|image (links)
			} else {
				// Global
				//-------------------------------------------------------------------------
				$id = $this->getOptionValueID(array($this->ProfileSetting['option_type'], $option[0], $option[1]));
				$option_id = $id[0];
				$option_value_id = $id[1];

				$product_option_id = $this->getProductOptionID($product_id, $option_id, $this->ProfileSetting['option_required']);

				// Type Select
				//-------------------------------------------------------------------------
				if ( in_array($this->ProfileSetting['option_type'], $option_type_select) ) {

					// price
					if ( !isset($option[2]) ) {
						$price = 0;
					} else {
						$price = $option[2];
					}

					// Image
					if ( isset($option[3]) ) {
						$this->db->query('UPDATE `' . DB_PREFIX . 'option_value` SET image = \'' . $this->db->escape($option[3]) . '\' WHERE option_value_id = \'' . (int)$option_value_id . '\' AND option_id = \'' . $option_id . '\'');
					}

					// Links
					if ( isset($option[4]) && $this->CoreType['PRODUCT_OPTION_LINKS'] ) {
						$this->db->query('UPDATE `' . DB_PREFIX . 'option_value_description` SET `links` = \'' . $this->db->escape($option[4]) . '\' WHERE option_value_id = \'' . (int)$option_value_id . '\' AND option_id = \'' . $option_id . '\'');
					}

					$this->db->query('INSERT INTO `' . DB_PREFIX . 'product_option_value` SET
					      product_option_id = \'' . (int)$product_option_id . '\',
						     product_id = \'' . (int)$product_id . '\',
						      option_id = \'' . (int)$option_id . '\',
						option_value_id = \'' . (int)$option_value_id . '\',
						       quantity = \'' . (int)$this->ProfileSetting['option_quantity'] . '\',
						       subtract = \'' . (int)$this->ProfileSetting['option_subtract_stock'] . '\',
						   price_prefix = \'' . $this->ProfileSetting['option_price_prefix'] . '\',
						   	  price = \'' . $price . '\',
						  points_prefix = \'' . $this->ProfileSetting['option_points_prefix'] . '\',
						         points = \'' . $this->ProfileSetting['option_points_default'] . '\',
						  weight_prefix = \'' . $this->ProfileSetting['option_weight_prefix'] . '\',
						         weight = \'' . $this->ProfileSetting['option_weight_default'] . '\';');
					// Type Date & Text
					//-------------------------------------------------------------------------
				} elseif ( in_array($this->ProfileSetting['option_type'], $option_type_datetime) || in_array($this->ProfileSetting['option_type'], $option_type_text) ) {
					// Option Value
					if ( isset($option[3]) ) {
						$this->db->query('UPDATE `' . DB_PREFIX . 'product_option` SET `value` = \'' . $this->db->escape($option[3]) . '\' WHERE product_option_id = \'' . (int)$product_option_id . '\'');
					}

					// Plugin Links Support
					if ( isset($option[4]) && $this->CoreType['PRODUCT_OPTION_LINKS'] ) {
						$this->db->query('UPDATE `' . DB_PREFIX . 'option_value_description` SET `links` = \'' . $this->db->escape($option[4]) . '\' WHERE option_value_id = \'' . (int)$option_value_id . '\' AND option_id = \'' . $option_id . '\'');
					}
				}
			}
		}
	}

	//-------------------------------------------------------------------------
	// PRODUCT IMPORT - Get Product Option ID
	//-------------------------------------------------------------------------
	private function getProductOptionID($product_id, $option_id, $required) {

		$query = $this->db->query('SELECT product_option_id FROM `' . DB_PREFIX . 'product_option` WHERE product_id = \'' . (int)$product_id . '\' AND option_id = \'' . (int)$option_id . '\' LIMIT 1');

		if ( isset($query->row['product_option_id']) ) {
			$product_option_id = $query->row['product_option_id'];
		} else {
			// Add new product option
			$this->db->query('INSERT INTO `' . DB_PREFIX . 'product_option` SET product_id = \'' . (int)$product_id . '\', option_id = \'' . (int)$option_id . '\', required = \'' . $required . '\'');
			$product_option_id = $this->db->getLastId();
		}

		return $product_option_id;
	}

	//-------------------------------------------------------------------------
	// PRODUCT IMPORT - Get Options Value ID
	//-------------------------------------------------------------------------
	private function getOptionValueID($option) {
		// fix HTML Special Chars
		$option[1] = htmlspecialchars($option[1]);
		$option[2] = htmlspecialchars($option[2]);

		$query = $this->db->query('SELECT option_id FROM `' . DB_PREFIX . 'option_description` WHERE LOWER(name) = LOWER(\'' . $this->db->escape($option[1]) . '\') AND language_id = \'' . (int)$this->LanguageID . '\' LIMIT 1');

		if ( $query->num_rows > 0 AND isset($query->row['option_id']) ) {
			$option_id = $query->row['option_id'];
		} else {
			// Add New Option Group
			//-------------------------------------------------------------------------
			$this->db->query('INSERT INTO `' . DB_PREFIX . 'option` SET type = \'' . $option[0] . '\', sort_order = 0');
			$option_id = $this->db->getLastId();

			// For All Languages
			foreach ( $this->Languages as $language_id ) {
				$this->db->query('INSERT INTO `' . DB_PREFIX . 'option_description` SET language_id = \'' . (int)$language_id . '\', option_id = \'' . $option_id . '\', name = \'' . $this->db->escape($option[1]) . '\'');
			}
		}

		$query = $this->db->query('SELECT option_value_id FROM `' . DB_PREFIX . 'option_value_description` WHERE LOWER(name) = LOWER(\'' . $this->db->escape($option[2]) . '\') AND option_id = \'' . $option_id . '\' AND language_id = \'' . (int)$this->LanguageID . '\' LIMIT 1');

		if ( isset($query->row['option_value_id']) ) {
			$option_value_id = $query->row['option_value_id'];
		} else {
			// Add new Option Value
			//-------------------------------------------------------------------------
			// Check Image
			if ( isset($option[12]) && !empty($option[12]) ) {
				$image = ', image= \'' . trim($option[12]) . '\'';
			} else {
				$image = '';
			}

			$this->db->query('INSERT INTO `' . DB_PREFIX . 'option_value` SET option_id = \'' . $option_id . '\'' . $image . ', sort_order = 0');
			$option_value_id = $this->db->getLastId();

			// For All Languages
			foreach ( $this->Languages as $language_id ) {
				$this->db->query('INSERT INTO `' . DB_PREFIX . 'option_value_description` SET language_id = \'' . (int)$language_id . '\', option_value_id = \'' . $option_value_id . '\', option_id = \'' . $option_id . '\', name = \'' . $this->db->escape($option[2]) . '\'');
			}
		}

		return array($option_id, $option_value_id);
	}

	//-------------------------------------------------------------------------
	// PRODUCT IMPORT - validate Product Option
	//-------------------------------------------------------------------------
	private function validateProductOption($option) {
		// quantity
		$option[4] = (isset($option[4])) ? (int)$option[4] : (int)$this->ProfileSetting['option_quantity'];

		// subtract
		$option[5] = (isset($option[5])) ? (int)$option[5] : (int)$this->ProfileSetting['option_subtract_stock'];

		// price_prefix
		$option[6] = (isset($option[6])) ? trim($option[6]) : $this->ProfileSetting['option_price_prefix'];

		// price
		if ( isset($option[7]) ) {
			$option[7] = $this->model_csvprice_pro_app_product->validateNumberFloat($option[7]);
		} else {
			$option[7] = 0;
		}

		//  points_prefix
		$option[8] = (isset($option[8])) ? trim($option[8]) : $this->ProfileSetting['option_points_prefix'];

		// points
		$option[9] = (isset($option[9])) ? (int)$option[9] : (int)$this->ProfileSetting['option_points_default'];

		// weight_prefix
		$option[10] = (isset($option[10])) ? trim($option[10]) : $this->ProfileSetting['option_weight_prefix'];

		// weight
		if ( isset($option[11]) ) {
			$option[11] = $this->model_csvprice_pro_app_product->validateNumberFloat($option[11]);
		} else {
			$option[11] = 0;
		}

		return $option;
	}

	//-------------------------------------------------------------------------
	// get Languages
	//-------------------------------------------------------------------------
	private function getLanguages() {
		$languages = array();

		$result = $this->db->query('SELECT language_id FROM `' . DB_PREFIX . 'language`');

		if ( $result->num_rows ) {
			foreach ( $result->rows as $language ) {
				$languages[] = $language['language_id'];
			}
		}

		return $languages;
	}

}