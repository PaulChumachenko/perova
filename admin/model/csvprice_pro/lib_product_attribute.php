<?php
class ModelCSVPriceProLibProductAttribute extends Model {

	// Configure
	private $AttributeDelimiter = '|';
	private $MultiLanguages = false;

	// Static
	private static $LanguageID = NULL;
	private static $Languages = NULL;
	private static $CountLanguages = 1;
	private static $AttributeCache = array();

	//-------------------------------------------------------------------------
	// PRODUCT EXPORT - Get Product Attribute
	//-------------------------------------------------------------------------
	public function getProductAttribute($product_id) {
		$attribute = array();

		if ($this->MultiLanguages && $this->CountLanguages > 1) {
			$field = ', patt.attribute_id';
		} else {
			$field = '';
		}

		$sql = "SELECT CONCAT_WS('" . $this->AttributeDelimiter . "'" . $field . ", attgd.name, attd.name, patt.text) AS p_attribute FROM `" . DB_PREFIX . "product_attribute` patt
		  	LEFT JOIN `" . DB_PREFIX . "attribute_description` attd ON (attd.attribute_id = patt.attribute_id)
		  	LEFT JOIN `" . DB_PREFIX . "attribute` att ON (attd.attribute_id = att.attribute_id) 
		  	LEFT JOIN `" . DB_PREFIX . "attribute_group_description` attgd ON (attgd.attribute_group_id = att.attribute_group_id)
		  	WHERE patt.product_id = " . (int)$product_id . " AND attgd.language_id = '" . (int)$this->LanguageID . "' AND attd.language_id = '" . (int)$this->LanguageID . "' AND patt.language_id = '" . (int)$this->LanguageID . "'";
		$query = $this->db->query($sql);

		foreach ( $query->rows as $result ) {
			$attribute[] = htmlspecialchars_decode($result['p_attribute']);
		}

		return implode("\n", $attribute);
	}

	//-------------------------------------------------------------------------
	// Added in v.3.2.0beta
	//-------------------------------------------------------------------------
	public function updateProductAttribute($product_id, $attributes) {

		$this->db->query('DELETE FROM `' . DB_PREFIX . 'product_attribute` WHERE language_id = \'' . (int)$this->LanguageID . '\' AND product_id = \'' . (int)$product_id . '\'');

		// Added in v2.2.2a
		foreach ( $attributes as $attribute_date ) {
			// Added in v 4.1.12.3
			$attribute_date = trim($attribute_date, " \n\r\t");

			// Empty data
			if (empty($attribute_date)) {
				continue;
			}

			$attribute = explode($this->AttributeDelimiter, $attribute_date);

			if (count($attribute) == 3) {

				$attribute[0] = trim($attribute[0]);
				$attribute[1] = trim($attribute[1]);

				// Check isset Product Attribute
				if (!isset($this->AttributeCache[mb_strtolower($attribute[0] . $attribute[1])])) {
					$attribute_id = $this->addAttribute($attribute[0], $attribute[1]);
				} else {
					$attribute_id = $this->AttributeCache[mb_strtolower($attribute[0] . $attribute[1])];
				}

				// Add New Product Attribute
				$this->db->query('DELETE FROM  `' . DB_PREFIX . 'product_attribute` WHERE attribute_id = \'' . (int)$attribute_id . '\' AND language_id = \'' . (int)$this->LanguageID . '\' AND product_id = \'' . (int)$product_id . '\'');

				$this->db->query('INSERT INTO `' . DB_PREFIX . 'product_attribute` 
					SET	product_id = \'' . (int)$product_id . '\',
					attribute_id = \'' . (int)$attribute_id . '\',
					language_id = \'' . (int)$this->LanguageID . '\',
					text = \'' . $this->db->escape($attribute[2]) . '\'
				');

			} elseif (count($attribute) == 4) {// Added in v.3.2.0beta

				$attribute_id = trim($attribute[0], " \n\t\r");
				$attribute[1] = trim($attribute[1], " \n\t\r");
				$attribute[2] = trim($attribute[2], " \n\t\r");
				$this->updateAttribute($attribute_id, $attribute[1], $attribute[2]);

				// Add New Product Attribute
				$this->db->query('DELETE FROM  `' . DB_PREFIX . 'product_attribute` WHERE attribute_id = \'' . (int)$attribute_id . '\' AND language_id = \'' . (int)$this->LanguageID . '\' AND product_id = \'' . (int)$product_id . '\'');

				$this->db->query('INSERT INTO `' . DB_PREFIX . 'product_attribute` 
					SET	product_id = \'' . (int)$product_id . '\',
					attribute_id = \'' . (int)$attribute_id . '\',
					language_id = \'' . (int)$this->LanguageID . '\',
					text = \'' . $this->db->escape($attribute[3]) . '\'
				');
			}
		}
	}

	//-------------------------------------------------------------------------
	// PRODUCT IMPORT - Add Product Attribute
	//-------------------------------------------------------------------------
	private function addAttribute($group_name, $attribute_name) {

		// Fix special charrs
		//-------------------------------------------------------------------------
		$group_name = htmlspecialchars($group_name);
		$attribute_name = htmlspecialchars($attribute_name);

		$query = $this->db->query('SELECT attribute_group_id FROM `' . DB_PREFIX . 'attribute_group_description` WHERE LOWER(name) = LOWER(\'' . $this->db->escape($group_name) . '\') AND language_id = \'' . (int)$this->LanguageID . '\' LIMIT 1');

		if (isset($query->row['attribute_group_id'])) {
			$attribute_group_id = $query->row['attribute_group_id'];
		} else {
			$this->db->query('INSERT INTO `' . DB_PREFIX . 'attribute_group` SET sort_order = 1');
			$attribute_group_id = $this->db->getLastId();
			$this->db->query('INSERT INTO `' . DB_PREFIX . 'attribute_group_description` 
				SET attribute_group_id = ' . (int)$attribute_group_id . ',
				language_id = \'' . (int)$this->LanguageID . '\',
				name = \'' . $this->db->escape($group_name) . '\'
			');
		}

		$query = $this->db->query('SELECT ad.attribute_id FROM `' . DB_PREFIX . 'attribute_description` ad 
			LEFT JOIN `' . DB_PREFIX . 'attribute` a ON (ad.attribute_id = a.attribute_id)
			WHERE LOWER(ad.name) = LOWER(\'' . $this->db->escape($attribute_name) . '\') AND ad.language_id = \'' . (int)$this->LanguageID . '\' AND a.attribute_group_id = \'' . (int)$attribute_group_id . '\' LIMIT 1
			');

		if (isset($query->row['attribute_id'])) {
			$attribute_id = $query->row['attribute_id'];
		} else {
			$this->db->query('INSERT INTO `' . DB_PREFIX . 'attribute` SET sort_order = 1, attribute_group_id = ' . (int)$attribute_group_id);
			$attribute_id = $this->db->getLastId();
			$this->db->query('INSERT INTO `' . DB_PREFIX . 'attribute_description`
				SET attribute_id = ' . (int)$attribute_id . ',
				language_id = \'' . (int)$this->LanguageID . '\',
				name = \'' . $this->db->escape($attribute_name) . '\'
			');
		}

		return $attribute_id;
	}

	//-------------------------------------------------------------------------
	// PRODUCT IMPORT - UDPATE Product Attribute by ID
	//-------------------------------------------------------------------------
	private function updateAttribute($attribute_id, $group_name, $attribute_name) {
		$query = $this->db->query('SELECT attribute_group_id FROM `' . DB_PREFIX . 'attribute` WHERE attribute_id = \'' . (int)$attribute_id . '\' LIMIT 1');

		if (isset($query->row['attribute_group_id'])) {
			$attribute_group_id = $query->row['attribute_group_id'];
		} else {
			return;
		}

		// Fix special charrs
		$group_name = htmlspecialchars($group_name);
		$attribute_name = htmlspecialchars($attribute_name);

		$this->db->query('DELETE FROM `' . DB_PREFIX . 'attribute_description` WHERE attribute_id = \'' . (int)$attribute_id . '\' AND language_id = \'' . (int)$this->LanguageID . '\';');
		$this->db->query('INSERT INTO `' . DB_PREFIX . 'attribute_description` SET attribute_id = ' . (int)$attribute_id . ', language_id = \'' . (int)$this->LanguageID . '\', name = \'' . $this->db->escape($attribute_name) . '\';');

		$this->db->query('DELETE FROM `' . DB_PREFIX . 'attribute_group_description` WHERE attribute_group_id = \'' . (int)$attribute_group_id . '\' AND language_id = \'' . (int)$this->LanguageID . '\';');
		$this->db->query('INSERT INTO `' . DB_PREFIX . 'attribute_group_description` SET attribute_group_id = ' . (int)$attribute_group_id . ', language_id = \'' . (int)$this->LanguageID . '\', name = \'' . $this->db->escape($group_name) . '\';');

		return $attribute_id;
	}

	//-------------------------------------------------------------------------
	// Set Language Id
	//-------------------------------------------------------------------------
	public function setLanguageId($language_id) {
		$this->LanguageID = $language_id;
		$this->setCountLanguages();
	}

	//-------------------------------------------------------------------------
	// Get Language Count
	//-------------------------------------------------------------------------
	private function setCountLanguages() {
		$result = $this->db->query("SELECT COUNT(`language_id`) AS count_languages FROM `" . DB_PREFIX . "language` WHERE `status` = 1");

		if (isset($result->num_rows) AND $result->num_rows > 0) {
			$this->CountLanguages = $result->row['count_languages'];
		} else {
			$this->CountLanguages = 1;
		}

		return $this->CountLanguages;
	}

}
