<?php

class ControllerApiOptionValue extends Controller
{

    public function options()
    {
        $this->load->language('api/option');
        $this->load->model('catalog/option');
        $this->load->model('api/request');
        $json = array();

        $this->model_api_request->validateSession($json);
        if (!array_key_exists("error", $json)) {
            // Options values
            if (isset($this->request->get['option_id'])) {
                $optionId = $this->request->get['option_id'];
            }

            $json = $this->model_catalog_option->getOptionValueDescriptions($optionId);
        }

        $this->model_api_request->prepareResponse($json);
    }

    public function add()
    {
        $this->load->language('api/option');
        $this->load->model('catalog/option');
        $this->load->model('api/request');
        $json = array();

        $this->model_api_request->validateSession($json);
        $json["success"] = false;
        if (!array_key_exists("error", $json) && ($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateOptionValue($json)) {
            $productOptionData = $this->model_catalog_option->addOptionValue($this->request->post);
            $json["optionId"] = $productOptionData["option_id"];
            $json["optionValueId"] = $productOptionData["option_value_id"];
            $json["success"] = true;
        }
        $this->model_api_request->prepareResponse($json);
    }

    public function edit()
    {
        $this->load->language('api/option');
        $this->load->model('catalog/option');
        $this->load->model('api/request');
        $json = array();

        $this->model_api_request->validateSession($json);
        $json["success"] = false;
        if (!array_key_exists("error", $json) && ($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateOptionValue($json)) {
            $this->model_catalog_option->editOptionValue($this->request->get['option_value_id'], $this->request->post);
            $json["success"] = true;
        }

        $this->model_api_request->prepareResponse($json);
    }

    protected function validateOptionValue(&$json)
    {
        if (isset($this->request->post['option_value'])) {
            foreach ($this->request->post['option_value']['option_value_description'] as $language_id => $option_value_description) {
                if ((utf8_strlen($option_value_description['name']) < 1) || (utf8_strlen($option_value_description['name']) > 128)) {
                    $json['error']['option_value'][$option_value_id][$language_id] = $this->language->get('error_option_value');
                }
            }
        }

        $isValid = false;
        if (empty($json['error'])) {
            $isValid = true;
        }
        return $isValid;
    }

}
