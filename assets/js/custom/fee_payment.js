$(document).ready(function(){
	var baseUrl = $('#base_url').val();
	var userUrl = $('#user_url').val(); 
	var ses_id = $('#ses_id').val();
	var sch_id = $('#sch_id').val();
	var med_id = $('#med_id').val();
	var adm_no = $('#adm_no').val();
	var current_month = $('#current_month').val();
	
	feeDetailList();
	function feeDetailList(){
		$.ajax({
			type:'POST',
			url:baseUrl+'Student_fee_ctrl/feeDetailList',
			data:{
				'ses_id':ses_id,
				'sch_id':sch_id,
				'med_id':med_id,
				'adm_no':adm_no
				},
			dataType:'json',
			beforeSend:function(){},
			success:function(response){
				var x='';
				if(response.status == 200){
					$('#student_name').val(response.data.student[0].name);
					$('#contact_no').val(response.data.student[0].contact_no);
	//-----------------------------------student details----------------------
					x=x+'<table class="table">'+
						'<tbody>'+
						'<tr><td><b>Student Name</b></td><td>: '+response.data.student[0].name+'</td>'+
						'<td><b>Father Name</b></td><td>: '+response.data.student[0].f_name+'</td>'+
						'</tr><tr>'+
						'<td><b>Admission No.</b></td><td>: '+response.data.student[0].adm_no+'</td>'+
						'<td><b>Roll No.</b></td><td>: '+response.data.student[0].roll_no+'</td>'+
						'</tr><tr>'+
						'<td><b>Class</b></td><td>: '+response.data.student[0].class_name+'</td>'+
						'<td><b>Student Fee Category</b></td><td>: '+response.data.student[0].fc_name+'</td>'+
						'</tr><tr>'+
						'<td><b>Medium</b></td><td>: '+response.data.student[0].med_name+'</td>'+
						'<td><b>Contact No.</b></td><td>'+response.data.student[0].contact_no+'</td>'+
					'</tr>'+
					'</tbody>'+
				'</table>';
				
				$('#student_details').html(x);
					
	//------------------------session fee--------------------------------
					var f = '';
					var flag = 1;
					$.each(response.data.session_fee,function(key,value){
						if(value.fee_status == 'Paid'){
							var check_box = 'disabled';
							var fee_status = '<b style="color:green;">Paid</b>';
						}else{
							var check_box = '';
							var fee_status = '<b style="color:red;">Pending</b>';
						}
						f=f+'<tr>'+
						'<td class="pm-box"><input '+check_box+' type="checkbox" data-late_fee="0" value="'+value.amount+'" data-ft_id="'+value.ft_id+'" class="ones_in_session fee_total"> <span class="checkmark"></span></td>'+
						'<td><b>'+value.name+'</b></td>'+
						'<td>'+value.amount+'</td>'+
						'<td>'+fee_status+'</td>'+
					'<tr>';
			
					});
					$('#one_time_fee').html(f);

	//--------------month fee-----------------------------------
					var m ='';
					if(response.data.fee_month){
						$.each(response.data.fee_month,function(key,value){
							
							var tution_and_bus_fee = parseFloat(parseFloat(value.fee)+parseFloat(value.bus_fee));
							var total_fee = parseFloat(parseFloat(value.fee)+parseFloat(value.bus_fee) + parseFloat(value.late_fee));
							console.log(total_fee);
							m=m+'<tr>'+
								'<td class="pm-box"><input type="checkbox" data-month_id="'+value.fm_id+'" data-late_fee="'+value.late_fee+'" value="'+tution_and_bus_fee+'" class="tution_fee month_fee_count fee_total" /> <span class="checkmark"></span></td>'+
								'<td>'+value.name+'</td>'+
								'<td>'+value.fee.toFixed(2)+'</td>'+
								'<td>'+value.bus_fee.toFixed(2)+'</td>'+
								'<td>'+value.due_date+'</td>'+
								'<td>'+value.late_fee.toFixed(2)+'</td>'+
								'<td>'+total_fee.toFixed(2)+'</td>'+
							'</tr>';
						});
						$('#month_fee').html(m);	
					}else{
						$('#month_fee').html('<tr><td colspan="7" style="text-align:center;">Paid all month fee</td></tr>');
					}
					
								
				}else{
						alert(response.msg);
				    }
			},
			complete:function(){
			},
		});
	}	
	
//-----------apply fee waiver--------------------------

    $(document).on('click','#fee_waiver_apply',function(){
    	var fee_waiver_amount = $('#fee_waiver_amount').val();
    	var fee_total = $('#fee_total').val();
    	var formvalidate = true;
    	
    	if(fee_waiver_amount == ''){
    		$('#fee_waiver_amount_err').html('This is Required.').css('display','block');
    		formvalidate = false;
    	}else if( parseFloat(fee_total) < parseFloat(fee_waiver_amount) ){
    		$('#fee_waiver_amount_err').html('This amount is greater then of total amount.').css('display','block');
    		formvalidate = false;
        }else{
       		$('#fee_waiver_amount_err').css('display','none');
      	}
    	
    	if(formvalidate){
		   $.ajax({
				type:'POST',
				url:baseUrl+'Student_fee_ctrl/fee_waiver_apply',
				data:{
					'ses_id' : ses_id,
					'sch_id' : sch_id,
					'med_id' : med_id, 
					'adm_no' : adm_no,
					'month_id' : current_month,
					'amount' : $('#fee_waiver_amount').val(),
				},
				dataType:'json',
				beforeSend:function(){},
				success:function(response){
					if(response.status == 200){
						alert('OTP Send in Director Mobile number');
						$('#fee_waiver_amount').prop('disabled',true);
						$('#fee_waiver_apply').prop('disabled',true);
						$('#fee_waiver_otp_row').css('display','block');
					}else{
						alert('something went wrong.');
					}
				}
			});
    	}
    });
    
	
	//-----------fee waiver otp resnd---------------------
    $(document).on('click','#resend_otp',function(){
    	 $.ajax({
				type:'POST',
				url:baseUrl+'Student_fee_ctrl/fee_waiver_apply',
				data:{
					'ses_id' : ses_id,
					'sch_id' : sch_id,
					'med_id' : med_id, 
					'adm_no' : adm_no,
					'month_id' : current_month,
					'amount' : $('#fee_waiver_amount').val(),
				},
				dataType:'json',
				beforeSend:function(){},
				success:function(response){
					if(response.status == 200){
						alert('OTP Resend Successfully.');
					}else{
						alert('Something went wrong.');
					}
				}
			});
    });
	
	//-----------OTP submit-------------------------------
    $(document).on('click','#otp_submit',function(){
    	var fee_waiver_otp = $('#fee_waiver_otp').val();
    	var fee_waiver_amount = $('#fee_waiver_amount').val();
    	var formvalid = true;
    	console.log(fee_waiver_otp);
    	console.log(fee_waiver_amount);
    	if(fee_waiver_amount == ''){
    		$('#fee_waiver_amount_err').html('This is Required.').css('display','block');
    		formvalid = false;
    	}else{
    		$('#fee_waiver_amount_err').css('display','none');
    	}
    	
    	if(fee_waiver_otp == ''){
    		$('#fee_waiver_otp_err').html('This is Required.').css('display','block');
    		formvalid = false;
    	}else{
    		$('#fee_waiver_otp_err').css('display','none');
    	}
    	
    	if(formvalid){
    		$.ajax({
        		type:'POST',
        		url:baseUrl+'Student_fee_ctrl/check_otp',
        		data:{
        			'ses_id' : ses_id,
        			'sch_id' : sch_id,
        			'med_id' : med_id,
        			'adm_no' : adm_no,
        			'month_id' : current_month,
        			'otp' : $('#fee_waiver_otp').val()
        		},
        		dataType:'json',
        		beforeSend:function(){
        			$('#loader').modal('show');
        		},
        		success:function(response){
        			if(response.status == 200){
        				$('#loader').modal('hide');	
        				$('#fee_waiver_apply').html('Applied');
        				$('#fee_waiver_apply').removeClass('btn-info',true);
        				$('#fee_waiver_apply').addClass('btn-success',true);
        				$('#fee_waiver_otp_row').css('display','none');
        				
        				var fee_waiver_amount = $('#fee_waiver_amount').val();
        				var fee_total = $('#fee_total').val();
        				var waiver_amount = parseFloat(parseFloat(fee_total) - parseFloat(fee_waiver_amount));
        				$('#fee_total').val(waiver_amount.toFixed(2)); 
        			}else{
        				$('#loader').modal('hide');
        				$('#fee_waiver_otp_err').html(response.msg).css('display','block');
        			}
        		}
        	});
    	}
    });
	
    //-------------calculate fee-----------------------------
	$(document).on('click','.fee_total',function(){
		if($(this).prop("checked") == true){
			var main_fee = $(this).val();
			var late_fee = $(this).data('late_fee');
			
			$('#all_late_fee').val(parseFloat(parseFloat($('#all_late_fee').val()) + parseFloat(late_fee)).toFixed(2));
			
	        var actual_total = parseFloat(main_fee) + parseFloat(late_fee);
	     	var box_amount = $('#fee_total').val();
			var total = parseFloat(box_amount) +  parseFloat(actual_total);
			
			var fee_waiver_amount = $('#fee_waiver_amount').val();
			if(fee_waiver_amount == ''){
				fee_waiver_amount = 0;
			}
			var total = total - fee_waiver_amount;
			$('#fee_total').val(total.toFixed(2));
			
			//----------grande total------------------
			var card_charge = $('#trns_charge').val();
			if(card_charge == ''){
				card_charge = 0;
			}
			var grand_total = parseFloat(parseFloat(total) + parseFloat(card_charge));
			$('#grand_total').val(grand_total.toFixed(2));
			//-----------*********---------------------
	    }
	    else if($(this).prop("checked") == false){
	    	var main_fee = $(this).val();
			var late_fee = $(this).data('late_fee');
			
			$('#all_late_fee').val(parseFloat(parseFloat($('#all_late_fee').val()) - parseFloat(late_fee)).toFixed(2) );
			
	        var actual_total = parseFloat(main_fee) + parseFloat(late_fee);
	     	var box_amount = $('#fee_total').val();
			var total = parseFloat(box_amount) -  parseFloat(actual_total);
			
			var fee_waiver_amount = $('#fee_waiver_amount').val();
			if(fee_waiver_amount == ''){
				fee_waiver_amount = 0;
			}
			var total = total + fee_waiver_amount;
			$('#fee_total').val(total.toFixed(2));
			
			//----------grande total------------------
			var card_charge = $('#trns_charge').val();
			if(card_charge == ''){
				card_charge = 0;
			}
			var grand_total = parseFloat(parseFloat(total) + parseFloat(card_charge));
			$('#grand_total').val(grand_total.toFixed(2));
			//-----------*********---------------------
	    }
	});

	//-------------select all month----------------------
	$(document).on('click','#all_month',function(){
		if($(this).prop("checked") == true){
			$('.month_fee_count').prop('checked',true);
			 $('#fee_total').val('0.00');
			$('.month_fee_count').each(function(){
				var main_fee = $(this).val();
				var late_fee = $(this).data('late_fee');
				
				$('#all_late_fee').val(parseFloat(parseFloat($('#all_late_fee').val()) + parseFloat(late_fee)).toFixed(2));
				
		        var actual_total = parseFloat(main_fee) + parseFloat(late_fee);
		     	var box_amount = $('#fee_total').val();
				var total = parseFloat(box_amount) +  parseFloat(actual_total);
				
				var fee_waiver_amount = $('#fee_waiver_amount').val();
				if(fee_waiver_amount == ''){
					fee_waiver_amount = 0;
				}
				var total = total - fee_waiver_amount;
				$('#fee_total').val(total.toFixed(2));
			});
			
		}else{
			
			$('.month_fee_count').prop('checked',false);
			$('.month_fee_count').each(function(){
				var main_fee = $(this).val();
				var late_fee = $(this).data('late_fee');
				
				$('#all_late_fee').val(parseFloat(parseFloat($('#all_late_fee').val()) - parseFloat(late_fee)).toFixed(2));
				
		        var actual_total = parseFloat(main_fee) + parseFloat(late_fee);
		     	var box_amount = $('#fee_total').val();
				var total = parseFloat(box_amount) -  parseFloat(actual_total);
				
				var fee_waiver_amount = $('#fee_waiver_amount').val();
				if(fee_waiver_amount == ''){
					fee_waiver_amount = 0;
				}
				var total = total + fee_waiver_amount;
				$('#fee_total').val(total.toFixed(2));
			});
		} 
	});

	//----------paymethod section---------------------
	$(document).on('click','#cash',function(){
		if($(this).prop('checked') == true){
			$('#cash_amount').prop('disabled',false);
		}else{
			$('#cash_amount').val('');
			$('#cash_amount').prop('disabled',true);
		}
	});
	
	$(document).on('click','#cheque',function(){
		if($(this).prop('checked') == true){
			$('#cheque_amount').prop('disabled',false);
			$('#cheque_no').prop('disabled',false);
			$('#cheque_date').prop('disabled',false);
			$('#cheque_bank').prop('disabled',false);
		}else{
			$('#cheque_amount').val('');
			$('#cheque_no').val('');
			$('#cheque_date').val('');
			$('#cheque_bank').val('');
			
			$('#cheque_amount').prop('disabled',true);
			$('#cheque_no').prop('disabled',true);
			$('#cheque_date').prop('disabled',true);
			$('#cheque_bank').prop('disabled',true);
		}
	});
	
	$(document).on('click','#dd',function(){
		if($(this).prop('checked') == true){
			$('#dd_amount').prop('disabled',false);
			$('#dd_no').prop('disabled',false);
			$('#dd_date').prop('disabled',false);
			$('#dd_bank').prop('disabled',false);
		}else{
			$('#dd_amount').val('');
			$('#dd_no').val('');
			$('#dd_date').val('');
			$('#dd_bank').val('');
			
			$('#dd_amount').prop('disabled',true);
			$('#dd_no').prop('disabled',true);
			$('#dd_date').prop('disabled',true);
			$('#dd_bank').prop('disabled',true);
		}
	});
	
	$(document).on('click','#pos',function(){
		if($(this).prop('checked') == true){
			$('#pos_card').prop('disabled',false);
			$('#pos_amount').prop('disabled',false);
		}else{
			$('#pos_card').prop('selectedIndex','');
			$('#pos_amount').val('');
			$('#trns_charge').val('');
			
			$('#pos_card').prop('disabled',true);
			$('#pos_amount').prop('disabled',true);
		}
	});
	
//-------------card transaction amount---------------
	$(document).on('keyup','#pos_amount',function(){
		var pos_amount = $(this).val();
		if(pos_amount == ''){
			pos_amount = 0;
		}
		var pos_card = $('#pos_card').val();
		var fieldvalidate = true;
		if(pos_card == ''){
			$('#pos_card_err').html('This is Required.').css('display','block');
			fieldvalidate = false;
			$('#submit').prop('disabled',true);
		}else{
			$('#pos_card_err').css('display','none');
			$('#submit').prop('disabled',false);
		}
		
		if(pos_card == 'credit_card'){
			var card_charge = 2;
		}else{
			var card_charge = 0.75;
		}
		
		if(fieldvalidate){
			var trns_charge = parseFloat((parseFloat(pos_amount) * parseFloat(card_charge)) / parseFloat(100));
			$('#trns_charge').val(trns_charge.toFixed(2));
			
			$('#grand_total').val(parseFloat(parseFloat(trns_charge) + parseFloat($('#fee_total').val())).toFixed(2) );
		}
	});
	
	//-----------on change update amount--------------
	
	$(document).on('change','#pos_card',function(){
		var pos_amount = $('#pos_amount').val();
		var pos_card = $(this).val();
		
		if(pos_amount != '' && pos_card != ''){
			if(pos_card == 'credit_card'){
				var card_charge = 2;
			}else{
				var card_charge = 0.75;
			}
			var trns_charge = parseFloat((parseFloat(pos_amount) * parseFloat(card_charge)) / parseFloat(100));
			$('#trns_charge').val(trns_charge.toFixed(2));
			$('#grand_total').val(parseFloat(parseFloat(trns_charge) + parseFloat($('#fee_total').val())).toFixed(2) );
			
			$('#pos_card_err').css('diplay','none');
			$('#submit').prop('disabled',false);
		}else{
			$('#pos_amount').val('');
			$('#trns_charge').val('');
		}
	});
	
	
	//-------------- focus on text box--------------------------------
	$(document).on('keyup','.max_type',function(){
		var max = $('#fee_total').val();
	  	var val = $(this).val();
	  	
	  	if (parseFloat(val) < parseFloat(0)){
	  	  	$(this).val(0.00);
	  	 }
	  	if(parseFloat(val) > parseFloat(max)){
	  	  	$(this).css('box-shadow','0px 0px 10px red');
		  	$(this).focus();
		    //$(this).val(max);
	  	}	
	  	else{
	  	  	$(this).css('box-shadow','none');
	  	}
	});
//-----------------submit-----------------------------
	$(document).on('click','#submit',function(){
		var formvalidate = true;
		var ses_id = $('#ses_id').val();
		var sch_id = $('#sch_id').val();
		var med_id = $('#med_id').val();
		var adm_no = $('#adm_no').val();
		var late_fee = $('#all_late_fee').val();
		var fee_total = $('#fee_total').val();
		var student_name = $('#student_name').val();
		var contact_no = $('#contact_no').val();
		var session_fee = [];
		var month_ids = [];
		var pay_option = [];
		
		$('.ones_in_session').each(function(){
			if($(this).prop("checked") == true){
				session_fee.push($(this).data('ft_id'));
			}
		});

		$('.tution_fee').each(function(){
			if($(this).prop("checked") == true){
				month_ids.push($(this).data('month_id'));
			}
		});
		
		if(month_ids == ''){
			alert('Please select which month payment. You want to pay.');
			formvalidate = false;
		}
		
		
		var cal_pay_method_amount = 0;
		$('.pay_method').each(function(){
			var temp = [];
			if($(this).prop('checked') == true){
				
				if($(this).attr('id') == 'cash'){
					cal_pay_method_amount  = parseFloat(parseFloat(cal_pay_method_amount) + parseFloat($('#cash_amount').val()) );
					var pay_method = 'cash';
					var amount = $('#cash_amount').val();
					var method_no = '';
					var date = '';
					var bank_name = '';
					var card = '';
					var trns_amount = '';
				}
				if($(this).attr('id') == 'cheque'){
					cal_pay_method_amount  = parseFloat(parseFloat(cal_pay_method_amount) + parseFloat($('#cheque_amount').val()) );
					var pay_method = 'cheque';
					var amount = $('#cheque_amount').val();
					var method_no = $('#cheque_no').val();
					var date = $('#cheque_date').val();
					var bank_name = $('#cheque_bank').val();
					var card = '';
					var trns_amount = '';
				}
				if($(this).attr('id') == 'dd'){
					cal_pay_method_amount  = parseFloat(parseFloat(cal_pay_method_amount) + parseFloat($('#dd_amount').val()) );
					var pay_method = 'dd';
					var amount = $('#dd_amount').val();
					var method_no = $('#dd_no').val();
					var date = $('#dd_date').val();
					var bank_name = $('#dd_bank').val();
					var card = '';
					var trns_amount = '';
				}
				if($(this).attr('id') == 'pos'){
					cal_pay_method_amount  = parseFloat(parseFloat(cal_pay_method_amount) + parseFloat($('#pos_amount').val()) );
					var pay_method = 'pos';
					var amount = $('#pos_amount').val();
					var card = $('#pos_card').val();
					var trns_amount = $('#trns_charge').val();
					var method_no = '';
					var date = '';
					var bank_name = '';
				}
				temp.push({pay_method : pay_method});
				temp.push({amount : amount});
				temp.push({card : card});
				temp.push({trns_amount : trns_amount});
				temp.push({method_no : method_no});
				temp.push({date : date});
				temp.push({bank_name : bank_name});
				pay_option.push(temp);
			}
		});

		if(parseFloat(cal_pay_method_amount) != parseFloat(fee_total)){
			alert('Amount not match.');
			formvalidate = false;
		}
		
		if(formvalidate){
			var formdata = new FormData();
			formdata.append('student_name',student_name);
			formdata.append('contact_no',contact_no);
			
			formdata.append('ses_id',ses_id);
			formdata.append('sch_id',sch_id);
			formdata.append('med_id',med_id);
			formdata.append('adm_no',adm_no);
			formdata.append('session_fee',session_fee);
			formdata.append('month_ids',month_ids);
			formdata.append('late_fee',late_fee);
			formdata.append('paid_amount',fee_total);
			formdata.append('pay_option',JSON.stringify(pay_option));
			$.ajax({
				type:'POST',
				url:baseUrl+'Student_fee_ctrl/fee_payment',
				data:formdata,
				dataType:'json',
				beforeSend:function(){},
				success:function(response){
					if(response.status == 200){
						alert(response.msg);
						window.location.href = baseUrl+userUrl+'/student-fee/receipt/'+response.receipt_no;
					}else{
						alert(response.msg);
					}
				},
				cache:false,
				contentType:false,
				processData:false
			});
		}
	});
});