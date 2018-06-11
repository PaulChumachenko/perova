<?php
class ModelCSVPriceProLibProductFilter extends Model {
	
	private $LanguageID;
	
	//-------------------------------------------------------------------------
	// Add Product Filters
	//-------------------------------------------------------------------------
	public function addProductFilters($product_id, $filters, $language_id) {
		$this->LanguageID = $language_id;

		// Delete old product filters
		//-------------------------------------------------------------------------
		$this->db->query('DELETE FROM `' . DB_PREFIX . 'product_filter` WHERE  product_id = \'' . (int)$product_id . '\'');

		if (empty($filters)) {
			return;
		}
		$data_a = explode("\n", $filters);
		unset($filters);

		if (!empty($data_a)) {
			foreach ($data_a as $filters_string) {
				$filter = explode('|', $filters_string);

				if (empty($filter) || count($filter) < 2) {
					continue;
				}

				$filter_id = $this->getFilterIdByName($filter[1], $filter[0]);
				$this->db->query('INSERT INTO `' . DB_PREFIX . 'product_filter` SET  product_id = \'' . (int)$product_id . '\', filter_id = \'' . (int)$filter_id . '\'');

			}
		}

	}
	
	//-------------------------------------------------------------------------
	// Add Category Filters
	//-------------------------------------------------------------------------
	public function addCategoryFilters($category_id, $filters, $language_id) {
		$this->LanguageID = $language_id;

		// Delete old category filters
		//-------------------------------------------------------------------------
		$this->db->query('DELETE FROM `' . DB_PREFIX . 'category_filter` WHERE  category_id = \'' . (int)$category_id . '\'');

		if (empty($filters)) {
			return;
		}
		$data = explode("\n", $filters);
		unset($filters);

		if (!empty($data)) {
			foreach ($data as $filters_string) {
				$filter = explode('|', $filters_string);

				if (empty($filter) || count($filter) < 2) {
					continue;
				}

				$filter_id = $this->getFilterIdByName($filter[1], $filter[0]);
				$this->db->query('INSERT INTO `' . DB_PREFIX . 'category_filter` SET  category_id = \'' . (int)$category_id . '\', filter_id = \'' . (int)$filter_id . '\'');

			}
		}
	}
	
	//-------------------------------------------------------------------------
	// Get Filter ID By Name
	//-------------------------------------------------------------------------
	private function getFilterIdByName($filter_name, $group_name) {

		$filter_group_id = $this->getFilterGroupIdByName($group_name);

		$result = $this->db->query('SELECT filter_id FROM `' . DB_PREFIX . 'filter_description` WHERE LOWER(name) = LOWER(\'' . $this->db->escape(trim($filter_name)) . '\') AND language_id = \'' . (int)$this->LanguageID . '\' AND filter_group_id = \'' . $filter_group_id . '\' LIMIT 1');

		if ($result->num_rows > 0 && isset($result->row['filter_id'])) {
			return $result->row['filter_id'];
		} else {
			return $this->addFilter($filter_group_id, $filter_name);
		}
	}

	//-------------------------------------------------------------------------
	// Get Filter Group ID By Name
	//-------------------------------------------------------------------------
	private function getFilterGroupIdByName($group_name) {
		$result = $this->db->query('SELECT filter_group_id FROM `' . DB_PREFIX . 'filter_group_description` WHERE LOWER(name) = LOWER(\'' . $this->db->escape(trim($group_name)) . '\') AND language_id = \'' . (int)$this->LanguageID . '\' LIMIT 1');
		if ($result->num_rows > 0) {
			return $result->row['filter_group_id'];
		} else {
			return $this->addFilterGroup($group_name);
		}
	}

	//-------------------------------------------------------------------------
	// Create Filter Group
	//-------------------------------------------------------------------------
	private function addFilterGroup($group_name, $sort_order = 0) {

		$this->db->query('INSERT INTO `' . DB_PREFIX . 'filter_group` SET sort_order = ' . $sort_order);

		$filter_group_id = $this->db->getLastId();

		$this->db->query('INSERT INTO `' . DB_PREFIX . 'filter_group_description` SET language_id = \'' . (int)$this->LanguageID . '\', filter_group_id = \'' . $filter_group_id . '\',  name = \'' . $this->db->escape($group_name) . '\'');

		return $filter_group_id;
	}

	//-------------------------------------------------------------------------
	// Create Filter
	//-------------------------------------------------------------------------
	private function addFilter($filter_group_id, $filter_name, $sort_order = 0) {
		$this->db->query('INSERT INTO `' . DB_PREFIX . 'filter` SET filter_group_id = ' . $filter_group_id . ',  sort_order = ' . $sort_order);

		$filter_id = $this->db->getLastId();

		$this->db->query('INSERT INTO `' . DB_PREFIX . 'filter_description` SET language_id = \'' . (int)$this->LanguageID . '\', filter_id = \'' . $filter_id . '\', filter_group_id = \'' . $filter_group_id . '\',  name = \'' . $this->db->escape($filter_name) . '\'');

		return $filter_id;
	}
}
?>