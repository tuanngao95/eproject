define(
    [
        'jquery',
        'ko',
        'uiComponent',
        'MW_Ddate/js/model/delivery-date',
        'mage/url',
        'MW_Ddate/js/model/api'
    ],
    function(
        $,
        ko,
        Component,
        DeliveryDateModel,
        Url,
        Api
    ) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'MW_Ddate/delivery-date'
            },
            listTime: ko.observableArray(),

            initialize: function () {
                var self = this;
                this._super();
            },

            isShowDelivery: function () {
                return DeliveryDateModel.getEnableDeliveryDate();
            },

            isShowSecurityCode: function(){
                return DeliveryDateModel.getIsShowSecurityCode();
            },

            isEnableComment: function () {
                return window.checkoutConfig.deliveryDateModule.enableComment == '1';
            },

            initDate: function () {
                this.initDatetimePicker();
            },

            isDatetimePicker: function () {
                return window.checkoutConfig.deliveryDateModule.calenderDisplay == '1';
            },

            initDatetimePicker: function(){
                var currentDate = new Date();
                var year = currentDate.getFullYear();
                var month = currentDate.getMonth();
                var day = currentDate.getDate();

                var limitWeeks = window.checkoutConfig.deliveryDateModule.limitWeeks;
                var delayDay = parseInt(window.checkoutConfig.deliveryDateModule.delayDay);
                currentDate.setDate(day + limitWeeks*7);
                var maxDate = currentDate;
                var self = this;
                var dateFormat = window.checkoutConfig.deliveryDateModule.dateFormat;
                $("#delivery_date").calendar({
                    showsTime: false,
                    controlType: 'select',
                    timeFormat: 'HH:mm TT',
                    showTime: false,
                    dateFormat: dateFormat,
                    minDate: new Date(year, month, (day+delayDay), '00', '00', '00', '00'),
                    maxDate: new Date(maxDate.getFullYear(), maxDate.getMonth(), maxDate.getDate(), '00', '00', '00', '00'),
                    beforeShowDay: self.disableDate,
                    onSelect: function (dateText, evt) {
                        if(evt){
                            var param = {
                                ddate: evt.currentYear+"-"+("0" + (evt.currentMonth+1)).slice(-2)+"-"+("0" + (evt.currentDay)).slice(-2)
                            };
                            var url = Url.build('mw_ddate/checkout/loadTime');
                            Api.post(url, param, function (res) {
                                var res = JSON.parse(res);
                                self.listTime(res);
                            });
                        }
                    }
                });
            },

            currentDate: function () {
                var self = this;
                var currentDeliveryDate = window.checkoutConfig.deliveryDateModule.currentDeliveryDate;
                if(currentDeliveryDate){
                    var currentDate = currentDeliveryDate.split('-');
                    var param = {
                        ddate: currentDeliveryDate
                    };
                    var url = Url.build('mw_ddate/checkout/loadTime');
                    Api.post(url, param, function (res) {
                        var res = JSON.parse(res);
                        self.listTime(res);
                    });

                    var dateFormat = window.checkoutConfig.deliveryDateModule.dateFormat;
                    if (dateFormat == 'mm-dd-yyyy') {
                        return currentDate[1] + '-' + currentDate[2] + '-' + currentDate[0];
                    } else if (dateFormat == 'dd-mm-yyyy') {
                        return currentDate[2] + '-' + currentDate[1] + '-' + currentDate[0];
                    }
                    else {
                        return currentDate[0] + '-' + currentDate[1] + '-' + currentDate[2];
                    }
                }
            },

            disableDate: function (date) {
                var day = date.getDate();
                var month = date.getMonth() + 1;
                var year = date.getFullYear();
                var currentDateText = year+"-"+("0" + (month)).slice(-2)+"-"+("0" + (day)).slice(-2);

                var isDeliveryOnSat = window.checkoutConfig.deliveryDateModule.deliverSaturdays;
                var isDeliveryOnSun = window.checkoutConfig.deliveryDateModule.deliverSundays;
                var weekday = date.getDay();
                if((isDeliveryOnSat == '0' && weekday == 6) || (isDeliveryOnSun == '0' && weekday == 0)){
                    return [false];
                }

                var disableDate = window.checkoutConfig.deliveryDateModule.disableDate;
                if (disableDate) {
                    var disableDateArray = disableDate.split(';');
                    // Now check if the current date is in disabled dates array.

                    if ($.inArray(currentDateText, disableDateArray) != -1 ) {
                        return [false];
                    } else {
                        return [true];
                    }
                } else {
                    return [true];
                }

            },

            selectTime: function (obj, evt) {
                var param = {
                    dtime_id: evt.target.value
                };
                var url = Url.build('mw_ddate/checkout/selectTime');
                Api.post(url, param, function (res) {

                });
            },
            
            currentTime: function () {
                var currentDeliveryTime = window.checkoutConfig.deliveryDateModule.currentDeliveryTime;
                if(currentDeliveryTime){
                    var param = {
                        dtime_id: currentDeliveryTime,
                        ddate: window.checkoutConfig.deliveryDateModule.currentDeliveryDate
                    };
                    var url = Url.build('mw_ddate/checkout/selectTime');
                    Api.post(url, param, function (res) {

                    });
                    return [currentDeliveryTime];
                }
            },

            currentComment: function () {
                var currentDeliveryComment = window.checkoutConfig.deliveryDateModule.currentDeliveryComment;
                if(currentDeliveryComment){
                    return currentDeliveryComment;
                }
            },

            updateComment: function (obj, evt) {
                var param = {
                    dcomment: evt.target.value
                };
                var url = Url.build('mw_ddate/checkout/updateComment');
                Api.post(url, param, function (res) {

                });
            },

            getDescription: function () {
                return window.checkoutConfig.deliveryDateModule.description;
            }
        });
    }
);