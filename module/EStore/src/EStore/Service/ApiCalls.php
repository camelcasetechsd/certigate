<?php

namespace EStore\Service;

/**
 * ApiCalls routes constants
 * 
 * 
 * @package estore
 * @subpackage service
 */
class ApiCalls
{

    /**
     * Login route
     */
    const LOGIN = "/estore/index.php?route=api/login";

    /**
     * Cart products route
     */
    const CART_PRODUCTS = "/estore/index.php?route=api/cart/products";

    /**
     * Product products route
     */
    const PRODUCT_PRODUCTS = "/estore/index.php?route=api/product/products";

    /**
     * Product add route
     */
    const PRODUCT_ADD = "/estore/index.php?route=api/product/add";

    /**
     * Product edit route
     */
    const PRODUCT_EDIT = "/estore/index.php?route=api/product/edit";

}
