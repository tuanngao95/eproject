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
        'MW_Ddate/js/model/api'
    ],
    function(
        $,
        ko,
        Component,
        Url,
        Api
    ) {
        'use strict';
        var currentSlide = 1;
        var contentSlides = "";
        var totalWidth = 0;
        var totalSlides = 0;

        function updateContentHolder()
        {
            var scrollAmount = 0;
            var scrollAmountH = 0;
            contentSlides.each(function(i){
                if(currentSlide - 1 > i) {
                    scrollAmount += this.clientWidth;
                    scrollAmountH += this.clientHeight;
                }
            });
            if ($('.option_slot_select').is(':visible')) {
                $(".slideshow-content").animate({
                    bottom: scrollAmountH
                }, 500);
            } else {
                $("#slideshow-scroller").animate({
                    scrollLeft: scrollAmount
                }, 500);
            }
        }

        function updateButtons()
        {
            if(currentSlide < totalSlides) {
                $("#slideshow-next").show();
            } else {
                $("#slideshow-next").hide();
            }
            if(currentSlide > 1) {
                $("#slideshow-previous").show();
            } else {
                $("#slideshow-previous").hide();
            }
        }

        function updateCssSelectedTime(dtimeId, ddate) {
            var ddatei = 'dd'+dtimeId+ddate;
            $('.slideshow-content a').removeClass('ddate_day_active');
            $('.deli_date').find('.ddate_day_selected').text('Select');
            var option_responsive = $('#slideshow-holder').find('.ddate_day_option').find("a[ddate='"+ddatei+"']");
            option_responsive.addClass('ddate_day_active');
            $('#slideshow-holder').find("a[ddate='"+ddatei+"']").addClass('ddate_day_active');
            $('.ddate_selected').show();
            $(option_responsive).parent().hide();
            $(option_responsive).closest('.option_slot_select').find('.ddate_day_selected').text($(option_responsive).text());

            var showdtimetext = $(option_responsive).text();
            var currentLiTag = $(option_responsive).closest('li');
            var showddate = $(currentLiTag).find('strong').text()+", "+$(currentLiTag).find('span').text();

            document.getElementById('showddate:date').innerHTML = showddate;
            document.getElementById('showddate:dtime').innerHTML = showdtimetext;
        }

        return Component.extend({
            defaults: {
                template: 'MW_Ddate/type/calender-grid'
            },
            listTime: ko.observableArray(),
            listWeekday: ko.observableArray(),

            initialize: function () {
                var self = this;

                if(Object.keys(window.checkoutConfig.deliveryDateModule.calenderTime).length){
                    self.listTime(window.checkoutConfig.deliveryDateModule.calenderTime);
                }

                if(Object.keys(window.checkoutConfig.deliveryDateModule.calenderWeekdays).length){
                    self.listWeekday(window.checkoutConfig.deliveryDateModule.calenderWeekdays);
                }

                this._super();
            },

            getAvaiable: function (dtimeId, date) {
                var avaiableArray = window.checkoutConfig.deliveryDateModule.avaiableDays;
                var avaiableTime = avaiableArray.filter((item) => {
                    // return item.dtime_id == dtimeId && item.date == date && (new Date(date).getMonth() > currentClientDate.getMonth() || (new Date(date).getDate()>=currentClientDate.getDate()));
                    return item.dtime_id == dtimeId && item.date == date;
                });

                if(avaiableTime.length){
                    return avaiableTime[0].avaiable;
                }
                return 0;
            },

            getFirstColumnHeader: function () {
                return window.checkoutConfig.deliveryDateModule.firstColumnHeader;
            },

            renderSlideContent: function () {
                contentSlides = $(".slideshow-content");
                totalSlides++;
                totalWidth += this.clientWidth;
                $("#slideshow-holder").width(500*totalSlides);
                $("#slideshow-scroller").attr({
                    scrollLeft: 0
                });
            },

            nextSlide: function () {
                currentSlide++;
                updateContentHolder();
                updateButtons();
            },

            preSlide: function () {
                currentSlide--;
                updateContentHolder();
                updateButtons();
            },

            mobileSelect: function (obj, evt) {
                $('#slideshow-holder').find('.ddate_day_option').hide();
                $(evt.currentTarget).find('.ddate_day_option').show();
                if ($(evt.currentTarget).is(':last-child') || $(evt.currentTarget).next().is(':last-child'))
                {
                    $(evt.currentTarget).find('.ddate_day_option').css('bottom', '10px');
                }
            },

            mobileSelectTime: function (obj, evt) {
                evt.stopPropagation();
                var dtimeId = obj.dtime_id;
                var ddate = $(evt.currentTarget).attr('value');
                var param = {
                    dtime_id: dtimeId,
                    ddate: ddate
                };
                var url = Url.build('mw_ddate/checkout/selectTime');
                Api.post(url, param, function (res) {
                    updateCssSelectedTime(dtimeId, ddate);
                });

            },

            selectTime: function (obj, evt) {
                var dtimeId = $(evt.currentTarget).attr('value');
                var ddate = obj.date;
                var param = {
                    dtime_id: dtimeId,
                    ddate: ddate
                };
                var url = Url.build('mw_ddate/checkout/selectTime');
                Api.post(url, param, function (res) {
                    updateCssSelectedTime(dtimeId, ddate);
                });
            },

            currentSelect: function () {
                if(
                    window.checkoutConfig.deliveryDateModule.currentDeliveryDate
                    && window.checkoutConfig.deliveryDateModule.currentDeliveryTime
                ){
                    var ddatei = 'dd'+window.checkoutConfig.deliveryDateModule.currentDeliveryTime+window.checkoutConfig.deliveryDateModule.currentDeliveryDate;
                    var elm = $('.slideshow-content .select_one').find("a[ddate='"+ddatei+"']");
                    if(elm){
                        if(!window.isSetCurrentSelect){
                            window.isSetCurrentSelect = 1;
                            $(elm).click();
                        }
                    }
                }
            }
        });
    }
);