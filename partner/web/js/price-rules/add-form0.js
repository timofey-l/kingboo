(function () {
    'use strict';

    $(document).ready(function () {

        // iCheck init
        $('.iCheck').iCheck({
            checkboxClass: 'icheckbox_flat-blue'
        });
        $('.box-header .iCheck').on('ifChanged', function (event) {
            var box = $(event.target).parent().parent().parent().parent();
            if (!box.is('.box')) {
                return;
            }

            if (event.target.checked) {
                box.removeClass('box-default').addClass('box-primary');
                box.find('.box-body input:not(.iCheck)').prop('disabled', false);
                box.find('.box-body .iCheck').iCheck('enable');
            } else {
                box.addClass('box-default').removeClass('box-primary');
                box.find('.box-body .form-group').removeClass('has-success').removeClass('has-error');
                box.find('.box-body input:not(.iCheck)').prop('disabled', true);
                box.find('.box-body input:not(.iCheck)').val('');
                box.find('.box-body .iCheck').iCheck('disable');
            }
        });
        $('.radio input[type=radio]').iCheck({
            radioClass: 'iradio_flat-blue'
        });

        // dateTimePicker init
        $('[data-provide=datepicker]').datepicker({
            language: LANG,
        })
            .on('changeDate', function(e){
                var inp = $(e.target).data('input');
                $("[name='PriceRuleBasic[" + inp + "]']").val(e.format(0,'yyyy-mm-dd'));
            });

    });

    $('#PriceRuleForm').submit(function(e){
        var $form = $(e.target);
        var focusControl = false;
        var result = true;
        $('#myModal .modal-body > .message').hide();
        $('.box-body .form-group').removeClass('has-success').removeClass('has-error');

        // value
        var $container = $('.field-pricerulebasic-value');
        var $input = $('#pricerulebasic-value');
        $container.removeClass('has-success').removeClass('has-error');
        $input.val(parseFloat($input.val().replace(',', '.')) || 0);
        if (parseFloat($input.val()) > 0) {
            $container.addClass('has-success');
        } else {
            $container.addClass('has-error');
            result = false;
            $('.message-value-error').show();
            if (!focusControl) {
                focusControl = $input;
            }
        }

        // rooms
        var checkedRooms = $("input[type=checkbox][name='rooms[]']:checked").length;
        if (checkedRooms == 0) {
            $('.message-rooms').show();
            result = false;
            if (!focusControl) {
                focusControl = $("input[type=checkbox][name='rooms[]']")[0];
            }
        }

        // conditions
        var bookingRangeCheck = $('input[name=bookingRange]').is(':checked');
        var livingRangeCheck = $('input[name=livingRange]').is(':checked');
        var codeCheck = $('input[name=checkCode]').is(':checked');
        var minCheck = $('input[name=minSum]').is(':checked');
        var maxCheck = $('input[name=maxSum]').is(':checked');

        var oneCondition = bookingRangeCheck || livingRangeCheck || codeCheck;

        if (oneCondition) {
            if (bookingRangeCheck) {
                var dF = $('#pricerulebasic-datefromb').val();
                var dT = $('#pricerulebasic-datetob').val();
                if (dF == '' || dT == '') {
                    result = false;
                    $('.message-dates-b').show();
                    if (!focusControl) focusControl = $('[data-input=dateFromB]');
                }
                if ((new Date(dF)) > (new Date(dT))) {
                    result = false;
                    $('.message-dates-b-order').show();
                    if (!focusControl) focusControl = $('[data-input=dateFromB]');
                }
            }
            if (livingRangeCheck) {
                var dF = $('#pricerulebasic-datefrom').val();
                var dT = $('#pricerulebasic-dateto').val();
                if (dF == '' || dT == '') {
                    result = false;
                    $('.message-dates-living').show();
                    if (!focusControl) focusControl = $('[data-input=dateFrom]');
                }
                if ((new Date(dF)) > (new Date(dT))) {
                    result = false;
                    $('.message-dates-order').show();
                    if (!focusControl) focusControl = $('[data-input=dateFromB]');
                }
            }
            if (codeCheck) {
                var codeVal = $('#pricerulebasic-code').val();
                if (codeVal == '' || !(new RegExp('^[\\d\\w+!_-]+$','g')).test(codeVal)) {
                    result = false;
                    $('.message-code').show();
                    if (!focusControl) focusControl= $('#pricerulebasic-code');
                    $('.field-pricerulebasic-code').addClass('has-error');
                } else {
                    $('.field-pricerulebasic-code').addClass('has-success');
                }
            }
        } else {
            result = false;
            $('.message-one-condition').show();
        }

        if (minCheck) {
            var $container = $('.field-pricerulebasic-minsum');
            var $input = $('#pricerulebasic-minsum');
            $container.removeClass('has-success').removeClass('has-error');
            $input.val(parseFloat($input.val().replace(',', '.')) || 0);
            if (parseFloat($input.val()) > 0) {
                $container.addClass('has-success');
            } else {
                $container.addClass('has-error');
                result = false;
                $('.message-minsum').show();
                if (!focusControl) {
                    focusControl = $input;
                }
            }
        }

        if (maxCheck) {
            var $container = $('.field-pricerulebasic-maxsum');
            var $input = $('#pricerulebasic-maxsum');
            $container.removeClass('has-success').removeClass('has-error');
            $input.val(parseFloat($input.val().replace(',', '.')) || 0);
            if (parseFloat($input.val()) > 0) {
                $container.addClass('has-success');
            } else {
                $container.addClass('has-error');
                result = false;
                $('.message-maxsum').show();
                if (!focusControl) {
                    focusControl = $input;
                }
            }
        }

        if (!result) {
            $('#myModal').modal();
            $('#myModal').on('hidden.bs.modal', function (e) {
                $('#myModal').off('hidden.bs.modal');
                if (focusControl) {
                    focusControl.focus();
                }
            });
            e.preventDefault();
        }
    });

})();