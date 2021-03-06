<div class="content-wrapper">
	<section class="content-header">
      <h1>Hostel <small>Student Fee Receipt</small></h1>
      <ol class="breadcrumb">
        <li><a href="<?=base_url();?>shakuntala/reception/dashbord"><i class="fa fa-dashboard"></i>Home</a></li>
        <li class="active">Student Fee Receipt</li>
      </ol>
    </section>
	
	<div class="col-md-8 col-md-offset-2 mt-3">
		<div class="print-s-logo" style="float:left;padding:0px 0px 5px 0px;width:100%;margin-bottom:10px;">
				<div class="text-center" style="float:left;">
					<?php if($this->session->userdata('school_id') == 1){ $school = 'shakuntala';?>
		<img class="pull-left" alt="" src="<?php echo base_url()?>assets/images/shakuntala/shakuntala.png" height="40" />
		<div class="print-s-name" >
			<h4><b>SHAKUNTALA GURUKUL</b></h4>
			<p>Ram Nagar Bhilai(C.G.)</p>
		</div>
      	<?php } else if($this->session->userdata('school_id') == 2){ $school = 'sharda';?>
      	<img class="pull-left" alt="" src="<?php echo base_url()?>assets/images/sharda/sharda_logo.png" height="40" />
		<div class="print-s-name" >
			<h4><b>Sharda Vidyalaya</b></h4>
			<p>Risali Bhilai(C.G.)</p>
		</div>
		<?php }else{ $school = 'cg-board';?>
		<img class="pull-left" alt="" src="<?php echo base_url()?>assets/images/shakuntala/shakuntala.png" height="40" />
		<div class="print-s-name" >
			<h4><b>Shakuntala Vidyalaya No. 2</b></h4>
			<p>Ram Nagar Bhilai(C.G.)</p>
		</div>
		<?php }?>
				</div>
				<div class="text-right">
					<h4 style="margin-bottom:0px;"><b>Hostel Fee</b></h4>
				</div>
		</div>
		<div class="box box-primary" style="position: relative; left: 0px; top: 0px;">		
			<div class="box-header ui-sortable-handle text-center no-print" style="cursor: move;">
			  <h2 class="box-title "><b>SHAKUNTALA GURUKUL</b></h2>
			  <p>Ram Nagar Bhilai(C.G.) 490023</p>
			</div>
			<div class="box-body hostel-bil-table" style="padding-top:0px;" id="fee_receipt"></div>
			
		</div>
	</div>
	
</div>
<input type="hidden" id="receipt_no" value="<?php echo $this->uri->segment(5);?>" />

<script type="text/javascript" src="<?php echo base_url();?>assets/js/custom/number_to_word.js"></script>
<script type="text/javascript">
var baseUrl = $('#base_url').val();

getReceiptData();
function getReceiptData(){
	var receipt_no = $('#receipt_no').val();
	$.ajax({
		type:'POST',
		url:baseUrl+'hostel/Hostel_students_ctrl/fee_receipt',
		data:{'receipt_no':receipt_no},
		dataType:'json',
		beforeSend:function(){},
		success:function(response){
			if(response.status == 200){
				var hostel_fee = parseFloat(parseFloat(response.data[0].hostel_amount)-parseFloat(parseFloat(response.data[0].taxable_amount))).toFixed(2);
				var mess_fee = parseFloat(response.data[0].taxable_amount).toFixed(2);
				var cgst_charge = parseFloat(response.data[0].cgst).toFixed(2);
				var sgst_charge = parseFloat(response.data[0].sgst).toFixed(2);
				var sub_total = parseFloat(response.data[0].paid_amount).toFixed(2);
				var total_gst =parseFloat(parseFloat(cgst_charge) +  parseFloat(cgst_charge)).toFixed(2);
				var x='<table class="table h-hostel-bil-table">'+
					'<tbody>'+
				'<tr><td><b>Bill No.</b></td><td>: '+response.data[0].receipt_no+'</td><td><b>Bill Date</b></td><td>: '+response.data[0].pay_date+'</td></tr>'+
				'<tr><td><b>Student Name</b></td><td>: '+response.data[0].name+'</td><td><b>Class</b></td><td>: '+response.data[0].class_name+'/'+response.data[0].section_name+'</td></tr>'+
				'<tr><td><b>Board</b></td><td>: '+response.data[0].school_name+'</td><td><b>GST No.</b></td><td>: 22ADWFS8751K1Z9</td></tr>'+
			'</tbody>'+
		'</table>'+
		
		'<table class="table  " style="border:2px solid #000;">'+
			'<thead>'+
				'<tr><th>S.NO.</th><th>PARTICULARS</th><th>SAC CODE</th><th>QTY</th><th>RATE</th><th>AMOUNT</th></tr>'+
			'</thead>'+
			'<tbody>'+
				'<tr><td>1.</td><td>Hostel Fees(During the Year- 12M)</td><td>996322</td><td>1</td><td>'+hostel_fee+'</td><td>'+hostel_fee+'</td></tr>'+
				'<tr><td>2.</td><td>Mess/Canteen Charges</td><td>996333</td><td>1</td><td>'+mess_fee+'</td><td>'+mess_fee+'</td></tr>'+
				'<tr><td colspan="2"></td><td><b>SUB TOTAL</b></td><td colspan="2"></td><td><b>'+response.data[0].hostel_amount+'</b></td></tr>'+
				'<tr><td colspan="3"></td><td>CGST @2.5%</td><td></td><td>'+cgst_charge+'</td></tr>'+
				'<tr><td colspan="3"></td><td>SGST @2.5%</td><td></td><td>'+sgst_charge+'</td></tr>'+
				'<tr><td colspan="2"></td><td><b>TOTAL</b></td><td colspan="2"></td><td><b>'+sub_total+'</b></td></tr>'+
				'<tr><td colspan="6" style="text-align:right;"><b>'+toWords(parseFloat(sub_total))+'</b></td></tr>'+
				
				'<tr><td colspan="2"></td><td><b>GST RATE</b></td><td><b>CGST @2.5%</b></td><td><b>SGST@2.5%</b></td><td><b>TOTAL GST</b></td></tr>'+
				'<tr><td></td><td>Hostel Fees - 996322</td><td>Exempt</td><td>-</td><td>-</td><td>-</td></tr>'+
				'<tr><td></td><td>Mess/Canteen - 996333</td><td>'+mess_fee+'</td><td>'+cgst_charge+'</td><td>'+sgst_charge+'</td><td>'+total_gst+'</td></tr>'+
				'<tr><td colspan="2"></td><td><b>TOTAL</b></td><td><b>'+cgst_charge+'</b></td><td><b>'+sgst_charge+'</b></td><td><b>'+total_gst+'</b></td></tr>'+
			'</tbody>'+
		'</table>'+
		'<table class="table h-hostel-bil-table">'+
			'<tbody>'+
				'<tr><td></td></tr>'+
				'<tr><td colspan="" style="text-align:right;"><b>Authorised Sign</b></td></tr>'+
			'</tbody></table>';
			$('#fee_receipt').html(x);
			}else{
				alert(response.msg);
			}
		},
	
	});
}

</script>

