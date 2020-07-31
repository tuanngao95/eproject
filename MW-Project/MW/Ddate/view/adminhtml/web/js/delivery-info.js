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
        'jquery',
        'ko',
        'uiComponent',
        'mage/url',
        'MW_Ddate/js/api'
    ], function($, ko, Component, Url, Api) {
        'use strict';
        return Component.extend({
            listTime: ko.observableArray(),
            defaults: {
                template: 'MW_Ddate/delivery-info'
            },

            initialize: function (config) {
                this._super();

                return this;
            },

            canShip: function () {
                return window.deliveryInfo.canShip;
            },

            isDisable: function () {
                return (!this.canShip() && window.deliveryInfo.incrementId);
            },

            isShowSecurityCode: function(){
                return false;
            },

            isEnableComment: function () {
                return window.deliveryInfo.config.isEnableComment;
            },

            initDate: function () {
                this.initDatetimePicker();
            },

            initDatetimePicker: function(){
                var currentDate = new Date();
                var year = currentDate.getFullYear();
                var month = currentDate.getMonth();
                var day = currentDate.getDate();

                var limitWeeks = window.deliveryInfo.config.limitWeeks;
                currentDate.setDate(day + limitWeeks*7);
                var maxDate = currentDate;
                var self = this;
                $("#mw_delivery_date").calendar({
                    showsTime: false,
                    controlType: 'select',
                    timeFormat: 'HH:mm TT',
                    dateFormat: 'yyyy-MM-dd',
                    showTime: false,
                    minDate: new Date(year, month, day, '00', '00', '00', '00'),
                    maxDate: new Date(maxDate.getFullYear(), maxDate.getMonth(), maxDate.getDate(), '00', '00', '00', '00'),
                    beforeShowDay: self.disableDate,
                    onSelect: function (dateText, evt) {
                        if(evt){
                            var param = {
                                ddate: evt.currentYear+"-"+("0" + (evt.currentMonth+1)).slice(-2)+"-"+("0" + (evt.currentDay)).slice(-2)
                            };
                            var url = window.deliveryInfo.loadTimeUrl;
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
                var currentDeliveryDate = window.deliveryInfo.mw_delivery_date;
                if(currentDeliveryDate){
                    var currentDate = currentDeliveryDate.split('-');
                    var param = {
                        ddate: currentDeliveryDate
                    };
                    var url = window.deliveryInfo.loadTimeUrl;
                    Api.post(url, param, function (res) {
                        var res = JSON.parse(res);
                        self.listTime(res);
                    });

                    return currentDate[0] + '-' + currentDate[1] + '-' + currentDate[2] ;
                }
            },

            disableDate: function (date) {
                var day = date.getDate();
                var month = date.getMonth() + 1;
                var year = date.getFullYear();
                var currentDateText = year+"-"+("0" + (month)).slice(-2)+"-"+("0" + (day)).slice(-2);

                var isDeliveryOnSat = window.deliveryInfo.config.deliverSaturdays;
                var isDeliveryOnSun = window.deliveryInfo.config.deliverSundays;
                var weekday = date.getDay();
                if((isDeliveryOnSat == '0' && weekday == 6) || (isDeliveryOnSun == '0' && weekday == 0)){
                    return [false];
                }

                var disableDate = window.deliveryInfo.config.disableDate;
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

            currentTime: function () {
                var currentDeliveryTime = window.deliveryInfo.mw_delivery_time_id;
                if(currentDeliveryTime){
                    return [currentDeliveryTime];
                }
            },

            // currentTimeText: ko.pureComputed(function(){
            //     var currentDeliveryTime = window.deliveryInfo.mw_delivery_time;
            //     if(currentDeliveryTime){
            //         return currentDeliveryTime;
            //     }
            // }),

            currentTimeText: ko.observable(window.deliveryInfo.mw_delivery_time),
            currentDateText: ko.observable(window.deliveryInfo.mw_delivery_date),

            currentComment: function () {
                var currentDeliveryComment = window.deliveryInfo.mw_delivery_comment;
                if(currentDeliveryComment){
                    return currentDeliveryComment;
                }
            },
            
            saveDelivery: function (obj) {
                var self = this;
                var ddate = $('#mw_delivery_date').val();
                var dtime = $('#mw_delivery_time').val();
                var dcomment = $('#mw_delivery_comment').val();
                var param = {
                    ddate: ddate,
                    dtime: dtime,
                    dcomment: dcomment,
                    increment_id: window.deliveryInfo.incrementId
                };
                var url = window.deliveryInfo.saveDeliveryUrl;
                Api.post(url, param, function (res) {
                    var res = JSON.parse(res);
                    if(res.error){
                        alert(res.error);
                    }
                    else if(res.isNotChange){

                    }
                    else{
                        window.deliveryInfo.mw_delivery_time = res.ddate.dtimetext;
                        window.deliveryInfo.mw_delivery_time_id = res.ddate.dtime;
                        window.deliveryInfo.mw_delivery_date = res.ddate.ddate;

                        self.currentTimeText(window.deliveryInfo.mw_delivery_time);
                        self.currentDateText(window.deliveryInfo.mw_delivery_date);
                    }
                });
            }
        });
    });