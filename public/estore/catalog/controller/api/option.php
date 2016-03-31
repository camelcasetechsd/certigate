<?php

class ControllerApiOption extends Controller
{

    public function options()
    {
        $this->load->language('api/option');
        $this->load->model('catalog/option');
        $this->load->model('api/request');
        $json = array();

        $this->model_api_request->validateSession($json);
        if (!array_key_exists("error", $json)) {
            // Options
            $json = $this->getList();
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
        if (!array_key_exists("error", $json) && ($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateOption($json)) {
            $this->model_catalog_option->addOption($this->request->post);
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
        if (!array_key_exists("error", $json) && ($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateOption($json)) {
            $this->model_catalog_option->editOption($this->request->get['option_id'], $this->request->post);
        }

        $this->model_api_request->prepareResponse($json);
    }

    protected function getList()
    {
        $sort = 'od.name';
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        }
        $order = 'ASC';
        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        }

        $data['options'] = array();

        $filter_data = array(
            'sort' => $sort,
            'order' => $order,
        );

        $results = $this->model_catalog_option->getOptions($filter_data);

        foreach ($results as $result) {
            $data['options'][] = array(
                'option_id' => $result['option_id'],
                'name' => $result['name'],
                'sort_order' => $result['sort_order'],
            );
        }

        return $data;
    }

    protected function validateOption(&$json)
    {

        foreach ($this->request->post['option_description'] as $language_id => $value) {
            if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 128)) {
                $json['error']['name'][$language_id] = $this->language->get('error_name');
            }
        }

        if (($this->request->post['type'] == 'select' || $this->request->post['type'] == 'radio' || $this->request->post['type'] == 'checkbox') && !isset($this->request->post['option_value'])) {
            $json['error']['warning'] = $this->language->get('error_type');
        }

        if (isset($this->request->post['option_value'])) {
            foreach ($this->request->post['option_value'] as $option_value_id => $option_value) {
                foreach ($option_value['option_value_description'] as $language_id => $option_value_description) {
                    if ((utf8_strlen($option_value_description['name']) < 1) || (utf8_strlen($option_value_description['name']) > 128)) {
                        $json['error']['option_value'][$option_value_id][$language_id] = $this->language->get('error_option_value');
                    }
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
