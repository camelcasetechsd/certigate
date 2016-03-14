<?php

class ControllerApiProduct extends Controller
{

    public function products()
    {
        $this->load->language('api/product');
        $this->load->model('catalog/product');
        $this->load->model('api/request');
        $json = array();

        $this->model_api_request->validateSession($json);
        if (!array_key_exists("error", $json)) {
            // Products
            $json = $this->getList();
        }

        $this->model_api_request->prepareResponse($json);
    }
    
    public function add()
    {
        $this->load->language('api/product');
        $this->load->model('catalog/product');
        $this->load->model('api/request');
        $json = array();
        
        $this->model_api_request->validateSession($json);
        $json["success"] = false;
        if (!array_key_exists("error", $json) && ($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateProduct($json)) {
            $json["productId"] = $this->model_catalog_product->addProduct($this->request->post);
            $json["success"] = true;
        }
        
        $this->model_api_request->prepareResponse($json);
    }
    
    public function edit()
    {
        $this->load->language('api/product');
        $this->load->model('catalog/product');
        $this->load->model('api/request');
        $json = array();

        $this->model_api_request->validateSession($json);
        $json["success"] = false;
        if (!array_key_exists("error", $json) && ($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateProduct($json)) {
            $json["productId"] = $this->model_catalog_product->editProduct($this->request->get['product_id'], $this->request->post);
            $json["success"] = true;
        }


        $this->model_api_request->prepareResponse($json);
    }

    protected function getList()
    {
        $filter_name = null;
        if (isset($this->request->get['filter_name'])) {
            $filter_name = $this->request->get['filter_name'];
        }
        $filter_model = null;
        if (isset($this->request->get['filter_model'])) {
            $filter_model = $this->request->get['filter_model'];
        }
        $filter_price = null;
        if (isset($this->request->get['filter_price'])) {
            $filter_price = $this->request->get['filter_price'];
        }
        $filter_quantity = null;
        if (isset($this->request->get['filter_quantity'])) {
            $filter_quantity = $this->request->get['filter_quantity'];
        }
        $filter_status = null;
        if (isset($this->request->get['filter_status'])) {
            $filter_status = $this->request->get['filter_status'];
        }

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        }
        else {
            $sort = 'pd.name';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        }
        else {
            $order = 'ASC';
        }

        $data['products'] = array();

        $filter_data = array(
            'filter_name' => $filter_name,
            'filter_model' => $filter_model,
            'filter_price' => $filter_price,
            'filter_quantity' => $filter_quantity,
            'filter_status' => $filter_status,
            'sort' => $sort,
            'order' => $order,
        );


        $results = $this->model_catalog_product->getAllProducts($filter_data);

        foreach ($results as $result) {

            $special = false;

            $product_specials = $this->model_catalog_product->getAllProductSpecials($result['product_id']);

            foreach ($product_specials as $product_special) {
                if (($product_special['date_start'] == '0000-00-00' || strtotime($product_special['date_start']) < time()) && ($product_special['date_end'] == '0000-00-00' || strtotime($product_special['date_end']) > time())) {
                    $special = $product_special['price'];

                    break;
                }
            }

            $data['products'][] = array(
                'product_id' => $result['product_id'],
                'name' => $result['name'],
                'model' => $result['model'],
                'price' => $result['price'],
                'special' => $special,
                'quantity' => $result['quantity'],
                'status' => $result['status'],
            );
        }
        return $data;
    }

    protected function validateProduct(&$json)
    {
        foreach ($this->request->post['product_description'] as $language_id => $value) {
            if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 255)) {
                $json['error']['name'][$language_id] = $this->language->get('error_name');
            }

            if ((utf8_strlen($value['meta_title']) < 3) || (utf8_strlen($value['meta_title']) > 255)) {
                $json['error']['meta_title'][$language_id] = $this->language->get('error_meta_title');
            }
        }
        if ((utf8_strlen($this->request->post['model']) < 1) || (utf8_strlen($this->request->post['model']) > 64)) {
            $json['error']['model'] = $this->language->get('error_model');
        }
        if (utf8_strlen($this->request->post['keyword']) > 0) {
            $this->load->model('catalog/url_alias');

            $url_alias_info = $this->model_catalog_url_alias->getUrlAlias($this->request->post['keyword']);

            if ($url_alias_info && isset($this->request->get['product_id']) && $url_alias_info['query'] != 'product_id=' . $this->request->get['product_id']) {
                $json['error']['keyword'] = sprintf($this->language->get('error_keyword'));
            }

            if ($url_alias_info && !isset($this->request->get['product_id'])) {
                $json['error']['keyword'] = sprintf($this->language->get('error_keyword'));
            }
        }
        $isValid = false;
        if (empty($json['error'])) {
            $isValid = true;
        }
        return $isValid;
    }

}
