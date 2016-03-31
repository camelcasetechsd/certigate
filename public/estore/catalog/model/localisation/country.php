<?php
class ModelLocalisationCountry extends Model {
	public function getCountry($country_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE country_id = '" . (int)$country_id . "' AND status = '1'");

		return $query->row;
	}

	public function getCountries() {
		$country_data = $this->cache->get('country.status');

		if (!$country_data) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE status = '1' ORDER BY name ASC");

			$country_data = $query->rows;

			$this->cache->set('country.status', $country_data);
		}

		return $country_data;
	}
    
    public function getCountryByIsoCode($iso_code_2 = null, $iso_code_3 = null) {
        if(is_null($iso_code_2) && is_null($iso_code_3)){
            throw new \Exception("no criteria provided");
        }
        if(!is_null($iso_code_2)){
            $filterCriteriaString = "iso_code_2 = '" . $iso_code_2 . "'";
        }else{
            $filterCriteriaString = "iso_code_3 = '" . $iso_code_3 . "'";
        }
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "country WHERE $filterCriteriaString AND status = '1'");

		return $query->row;
	}
}