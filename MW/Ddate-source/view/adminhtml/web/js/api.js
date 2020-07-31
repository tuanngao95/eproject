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
        'jquery'
    ],
    function (ko, $) {
        "use strict";
        var Api = {
            initialize: function () {
                var self = this;
                return self;
            },

            post: function (url, param, callFunction) {
                $('.loading-mask').show();
                return $.post(url, param, callFunction)
                    .always(function(){
                        $('.loading-mask').hide();
                    });
            }
        };
        return Api.initialize();
    }
);
