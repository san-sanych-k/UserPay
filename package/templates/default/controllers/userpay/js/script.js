var icms = icms || {};

icms.userpay = (function ($) {

    this.onDocumentReady = function() {

    	vex.defaultOptions.className = 'vex-theme-plain';

	    icms.userpay.bindModal('.ajax-pay-modal');
		
    	$(document).on('click', '#userpay_payment_form .userpay_payments_buttons .userpay_payment_button, #userpay_payment_form .userpay_payment_button_puy', function(){
            var system = $(this).attr('data-system');
            var amount = $(this).closest('#userpay_payment_form').find('input').val().replace(',', '.');
            var order_id = $(this).closest('#userpay_payment_form').attr('data-order-id');
            var order_name = $(this).closest('#userpay_payment_form').attr('data-order-name');
            if (!amount || amount<=0) {
            	vex.dialog.alert({message:'Введите сумму платежа.',callback: function(data){
            		$('#userpay_payment_form').find('input').val('').focus();
            	}});
                return false;
            }
            icms.userpay.getForm(system, amount, order_id, order_name);
        });

        $(document).on('click', '#userpay_payment_form .userpay_payments_panel_wrapper .userpay_payment_button', function(){
            
            $('.userpay_payments_panel_wrapper').addClass('is_hidden');
            
            var system = $(this).attr('data-system');
            var title = $(this).attr('title');
            var hint = $('#'+system+'_hint').html();
            
            $('#userpay_payment_form .userpay_payment_button_puy').data('system',system).attr('class','userpay_payment_button_puy '+system);
            $('#userpay_payment_form .userpay_payments_change_wrapper .userpay_payment_button').attr('class','userpay_payment_button '+system).attr('title',title);
            $('#userpay_payment_form .userpay_payments_change_hint').html(hint);
            
            return false;
           
        });

        $(document).on('click', '#userpay_payment_form .userpay_payments_change', function(){
            
            $('#userpay_payment_form .userpay_payments_panel_wrapper').removeClass('is_hidden');
            
            return false;
           
        });

    };

    //====================================================================//
	
	
	
	this.bindModal = function(selector) {		
	
		$(selector).on('click', function(){
		ths = $(this);
		title = ths.data('title') ? ths.data('title') : '';
        icms.modal.openAjax(ths.data('url'), false, function() { $('.nyroModalCont').addClass('userpay_payment_modal'); }, title);
		return false;
		});
		
	};

    this.getForm = function(system, amount, order_id, order_name) {

        $.post('/userpay/payment', {
            
            system: system,
            amount: amount,
            order_id, order_id,
            order_name: order_name
            
        }, function(result){
            
            if (!result.error){
                if (result.data['error']) {
                    vex.dialog.alert(result.data['error']);
                    return false;
                } else {
	                vex.dialog.confirm({
					    message: 'К оплате: '+result.data['result_amount']+' '+result.data['curr_short']+' Продолжить?',
					    buttons: [
					        $.extend({}, vex.dialog.buttons.YES, { text: 'Оплатить' }),
					        $.extend({}, vex.dialog.buttons.NO, { text: 'Отмена' })
					    ],
					    callback: function (value) {
					        if(value == true){
					        	$.redirect(result.data['link'], result.data['data'], result.data['method']);
					        } else {
					        	return;
					        }
					    }
					})
            	}
                return false;
            } else {
                return;
            }

        }, "json");

    }

    //====================================================================//

	return this;

}).call(icms.userpay || {},jQuery);