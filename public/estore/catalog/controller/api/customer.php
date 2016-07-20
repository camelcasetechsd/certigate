<?php

class ControllerApiCustomer extends Controller
{

    public function index()
    {
        $json = array();

        $this->load->language('api/customer');

        // Delete past customer in case there is an error
        unset($this->session->data['customer']);

        

        if (!isset($this->session->data['api_id'])) {
            $json['error']['warning'] = $this->language->get('error_permission');
        }
        else {
            // Add keys for missing post vars
            $keys = array(
                'customer_id',
                'customer_group_id',
                'firstname',
                'lastname',
                'email',
                'telephone',
                'fax'
            );

            foreach ($keys as $key) {
                if (!isset($this->request->post[$key])) {
                    $this->request->post[$key] = '';
                }
            }

            // Customer
            if ($this->request->post['customer_id']) {
                $this->load->model('account/customer');

                $customer_info = $this->model_account_customer->getCustomer($this->request->post['customer_id']);

                if (!$customer_info || !$this->customer->login($customer_info['email'], '', true)) {
                    $json['error']['warning'] = $this->language->get('error_customer');
                }
            }

            if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
                $json['error']['firstname'] = $this->language->get('error_firstname');
            }

            if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
                $json['error']['lastname'] = $this->language->get('error_lastname');
            }

            if ((utf8_strlen($this->request->post['email']) > 96) || (!preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email']))) {
                $json['error']['email'] = $this->language->get('error_email');
            }

            if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
                $json['error']['telephone'] = $this->language->get('error_telephone');
            }

            // Customer Group
            if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
                $customer_group_id = $this->request->post['customer_group_id'];
            }
            else {
                $customer_group_id = $this->config->get('config_customer_group_id');
            }

            // Custom field validation
            $this->load->model('account/custom_field');

            $custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

            foreach ($custom_fields as $custom_field) {
                if (($custom_field['location'] == 'account') && $custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['custom_field_id']])) {
                    $json['error']['custom_field' . $custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
                }
            }

            if (!$json) {
                $this->session->data['customer'] = array(
                    'customer_id' => $this->request->post['customer_id'],
                    'customer_group_id' => $customer_group_id,
                    'firstname' => $this->request->post['firstname'],
                    'lastname' => $this->request->post['lastname'],
                    'email' => $this->request->post['email'],
                    'telephone' => $this->request->post['telephone'],
                    'fax' => $this->request->post['fax'],
                    'custom_field' => isset($this->request->post['custom_field']) ? $this->request->post['custom_field'] : array()
                );

                $json['success'] = $this->language->get('text_success');
            }
        }

        if (isset($this->request->server['HTTP_ORIGIN'])) {
            $this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
            $this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
            $this->response->addHeader('Access-Control-Max-Age: 1000');
            $this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    public function edit()
    {
        $this->load->language('account/register');
        $this->load->model('account/customer');
        $this->load->model('account/address');
        $this->load->model('account/activity');
        $this->load->model('api/request');
        $json = array();

        $this->model_api_request->validateSession($json);
        $json["success"] = false;
        if (!array_key_exists("error", $json) && ($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateCustomer($json)) {
            $this->model_account_customer->editCustomer($this->request->post, $this->request->get["customer_id"]);
            $customerInfo = $this->model_account_customer->getCustomer($this->request->get["customer_id"]);
            $this->model_account_address->editAddress($customerInfo["address_id"], $this->request->post, $this->request->get["customer_id"]);
            $activity_data = array(
                'customer_id' => $this->request->get["customer_id"],
                'name' => $this->request->post['firstname'] . ' ' . $this->request->post['lastname']
            );
            $this->model_account_activity->addActivity('edit', $activity_data);
            $json["success"] = true;
        }

        $this->model_api_request->prepareResponse($json);
    }

    public function add()
    {
        $this->load->language('account/register');
        $this->load->model('account/customer');
        $this->load->model('account/activity');
        $this->load->model('api/request');
        $json = array();

        $this->model_api_request->validateSession($json);
        $json["success"] = false;
        if (!array_key_exists("error", $json) && ($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateCustomer($json)) {
            $json["customerId"] = $this->model_account_customer->addCustomer($this->request->post);
            // Clear any previous login attempts for unregistered accounts.
            $this->model_account_customer->deleteLoginAttempts($this->request->post['email']);
            $activity_data = array(
                'customer_id' => $json["customerId"],
                'name' => $this->request->post['firstname'] . ' ' . $this->request->post['lastname']
            );

            $this->model_account_activity->addActivity('register', $activity_data);
            $json["success"] = true;
        }

        $this->model_api_request->prepareResponse($json);
    }

    protected function validateCustomer(&$json)
    {
        if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
            $json["error"]['firstname'] = $this->language->get('error_firstname');
        }

        if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
            $json["error"]['lastname'] = $this->language->get('error_lastname');
        }

        if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email'])) {
            $json["error"]['email'] = $this->language->get('error_email');
        }

        if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
            $json["error"]['telephone'] = $this->language->get('error_telephone');
        }

        if ((utf8_strlen(trim($this->request->post['address_1'])) < 3) || (utf8_strlen(trim($this->request->post['address_1'])) > 128)) {
            $json["error"]['address_1'] = $this->language->get('error_address_1');
        }

        if ((utf8_strlen(trim($this->request->post['city'])) < 2) || (utf8_strlen(trim($this->request->post['city'])) > 128)) {
            $json["error"]['city'] = $this->language->get('error_city');
        }

        $this->load->model('localisation/country');
        $this->load->model('localisation/zone');

        $country_info = $this->model_localisation_country->getCountryByIsoCode($this->request->post['country_iso_code_2']);

        if ($country_info && $country_info['postcode_required'] && (utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10)) {
            $json["error"]['postcode'] = $this->language->get('error_postcode');
        }

        if ($this->request->post['country_iso_code_2'] == '') {
            $json["error"]['country'] = $this->language->get('error_country');
        }
        if(is_array($country_info) && array_key_exists("country_id", $country_info)){
            $this->request->post["country_id"] = $country_info["country_id"];

            if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '') {
                $countryZones = $this->model_localisation_zone->getZonesByCountryId($country_info["country_id"]);
                $this->request->post["zone_id"] = reset($countryZones)["zone_id"];
            }
        }else{
            $this->request->post["country_id"] = null;
            $this->request->post["zone_id"] = null;
        }
        // Customer Group
        if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
            $customer_group_id = $this->request->post['customer_group_id'];
        }
        else {
            $customer_group_id = $this->config->get('config_customer_group_id');
        }

        // Custom field validation
        $this->load->model('account/custom_field');

        $custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

        foreach ($custom_fields as $custom_field) {
            if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
                $json["error"]['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
            }
        }

        if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
            $this->request->post["password"] = "P@\$w0rd".uniqid();
        }

        // Agree to terms
        if ($this->config->get('config_account_id')) {
            $this->load->model('catalog/information');

            $information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));

            if ($information_info && !isset($this->request->post['agree'])) {
                $json["error"]['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
            }
        }
        return empty($json["error"]) ? true : false;
    }

}
