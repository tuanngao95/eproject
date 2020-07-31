/*
 * Mage-World
 *
 * @category    Mage-World
 * @package     MW
 * @author      Mage-world Developer
 *
 * @copyright   Copyright (c) 2018 Mage-World (https://www.mage-world.com/)
 */

define(
    [
        'ko',
        'jquery',
        'Magento_Checkout/js/model/full-screen-loader'
    ],
    function (ko, $, Loader) {
        "use strict";
        var Api = {
            initialize: function () {
                var self = this;
                return self;
            },
            
            post: function (url, param, callFunction) {
                Loader.startLoader();
                return $.post(url, param, callFunction)
                    .always(function(){
                        Loader.stopLoader();
                    });
            }
        };
        return Api.initialize();
    }
);
