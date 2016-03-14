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

    /**
     * OptionValue options route
     */
    const OPTION_VALUE_OPTIONS = "/estore/index.php?route=api/optionValue/options";

    /**
     * OptionValue add route
     */
    const OPTION_VALUE_ADD = "/estore/index.php?route=api/optionValue/add";

    /**
     * OptionValue edit route
     */
    const OPTION_VALUE_EDIT = "/estore/index.php?route=api/optionValue/edit";

    /**
     * Option options route
     */
    const OPTION_OPTIONS = "/estore/index.php?route=api/option/options";

    /**
     * Option add route
     */
    const OPTION_ADD = "/estore/index.php?route=api/option/add";

    /**
     * Option edit route
     */
    const OPTION_EDIT = "/estore/index.php?route=api/option/edit";

}
