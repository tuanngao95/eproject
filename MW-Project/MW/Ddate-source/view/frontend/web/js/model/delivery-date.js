/*
 * *
 *  Copyright © 2016 MW. All rights reserved.
 *  See COPYING.txt for license details.
 *
 */
define(
    [
        'ko'
    ],
    function (ko) {
        'use strict';
        var isShowDelivery = ko.observable(window.checkoutConfig.deliveryDateModule.enable);
        var isShowSecurityCode = ko.observable(window.checkoutConfig.deliveryDateModule.isSercuryCode);
        return {
            listTime: ko.observableArray(),

            isShowDelivery: isShowDelivery,
            isShowSecurityCode: isShowSecurityCode,
            deliveryDate: ko.observable(),
            deliveryTime: ko.observable(),

            getEnableDeliveryDate: function() {
                if(this.isShowDelivery() == '1'){
                    return true;
                }
                return false;
            },

            getIsShowSecurityCode: function () {
                if(this.isShowSecurityCode() == '1'){
                    return true;
                }
                return false;
            },

            setDeliveryDate: function (date) {
                this.deliveryDate(date);
            },

            setDeliveryTime: function (timeId) {
                return this.deliveryTime(timeId);
            }
        };
    }
);
