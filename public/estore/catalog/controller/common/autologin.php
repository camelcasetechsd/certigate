<?php

class ControllerCommonAutologin extends Controller
{

    public function index()
    {
        $session = $_SESSION;
        $this->load->model('account/customer');
        
        if (is_array($session) && array_key_exists("Zend_Auth", $session)) {
            $zendAuthData = $session["Zend_Auth"]->getArrayCopy();
            if (is_array($zendAuthData) && array_key_exists("storage", $zendAuthData) 
                    && count($zendAuthData["storage"]) > 0 
                    && array_key_exists("customerId", $zendAuthData["storage"]) 
                    && (
                        !$this->customer->isLogged() ||
                            (
                                $this->customer->isLogged() &&
                                $this->customer->getId() != $zendAuthData["storage"]["customerId"]
                            )
                    )
            ) {
                $customerId = $zendAuthData["storage"]["customerId"];
                if ($this->customer->isLogged()) {
                    $this->event->trigger('pre.customer.logout');

                    $this->customer->logout();

                    unset($this->session->data['shipping_address']);
                    unset($this->session->data['shipping_method']);
                    unset($this->session->data['shipping_methods']);
                    unset($this->session->data['payment_address']);
                    unset($this->session->data['payment_method']);
                    unset($this->session->data['payment_methods']);
                    unset($this->session->data['comment']);
                    unset($this->session->data['order_id']);
                    unset($this->session->data['coupon']);
                    unset($this->session->data['reward']);
                    unset($this->session->data['voucher']);
                    unset($this->session->data['vouchers']);

                    $this->event->trigger('post.customer.logout');
                }
                // Trigger customer pre login event
                $this->event->trigger('pre.customer.login');
                // Check if customer has been approved.
                $customer_info = $this->model_account_customer->getCustomer($customerId);
                
                $this->customer->login($customer_info['email'], /*$password =*/'', /*$override =*/true);
                // Unset guest
                unset($this->session->data['guest']);
                // Default Shipping Address
                $this->load->model('account/address');
                if ($this->config->get('config_tax_customer') == 'payment') {
                    $this->session->data['payment_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
                }
                if ($this->config->get('config_tax_customer') == 'shipping') {
                    $this->session->data['shipping_address'] = $this->model_account_address->getAddress($this->customer->getAddressId());
                }

                // Wishlist
                if (isset($this->session->data['wishlist']) && is_array($this->session->data['wishlist'])) {
                    $this->load->model('account/wishlist');

                    foreach ($this->session->data['wishlist'] as $key => $product_id) {
                        $this->model_account_wishlist->addWishlist($product_id);

                        unset($this->session->data['wishlist'][$key]);
                    }
                }

                // Add to activity log
                $this->load->model('account/activity');

                $activity_data = array(
                    'customer_id' => $this->customer->getId(),
                    'name' => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
                );

                $this->model_account_activity->addActivity('login', $activity_data);

                // Trigger customer post login event
                $this->event->trigger('post.customer.login');
            }
        }
    }

}
