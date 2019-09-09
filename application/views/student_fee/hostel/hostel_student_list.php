
<div class="content-wrapper">
<section class="content-header">
      <h1>Hostel <small>Student Hostel Fee</small></h1>
      <ol class="breadcrumb">
        <li><a href="<?=base_url();?>shakuntala/reception/dashbord"><i class="fa fa-dashboard"></i>Home</a></li>
        <li class="active">Student Hostel Fee</li>
      </ol>
    </section>
    <section class="content-header">
 </section>

<!-- main section -->
<div class="col-md-12">	
  	<div class="box box-danger no-print">
        <div class="box-header">
          <h3 class="box-title">Student Filter</h3>
        </div>
    	<div class="box-body form-horizontal">
    	<form id="class_wise_fee_details" action="javascript:void(0);" method="POST" role="form">
    	
    	<input type="hidden" id="user_url" value="<?php echo $this->uri->segment(1).'/'.$this->uri->segment(2);?>">
    	
      			<div class="form-group" style="margin-bottom:0px;">
      				<div class="col-sm-2 mb-3">
						<select name="session" id="session" class="form-control">
							<option value="">Select Session</option>
							<option value="3" selected>2019-20</option>
						</select>
						<div id="session_err" style="display:none; color:red;"></div>
					</div>
					<div class="col-sm-2 mb-3">
						<select name="school" id="school" class="form-control">
							<option value="">Select Board</option>
							<?php if($this->session->userdata('school_id') == 1){?>
							<option value="1" selected>Shakuntala CBSE</option>
							<option value="3">CG State Board</option>
							<?php }else{?>
							<option value="2" selected>Sharda</option>
							<?php } ?>
						</select>
						<div id="school_err" style="display:none; color:red;"></div>
					</div>
					
					<div class="col-sm-2 mb-3" >
						<select name="medium" id="medium" class="form-control">
							<option value="">Select Medium</option>
							<?php foreach($medium as $med){?>
						    <option value="<?php echo $med['med_id'];?>"><?php echo $med['med_name'];?></option>
						<?php }?>
						</select>
						<div id="medium_err" style="display:none; color:red;"></div>
					</div>
					<div class="col-sm-2 mb-3">
						<select class="form-control" id="class_name" name="class_name">
							<option value="">Select Class</option>
							<?php foreach($class as $classes){?>
								<option value="<?php echo $classes['c_id'];?>"><?php echo $classes['class_name'];?></option>
							<?php } ?>
						</select>
						<div id="class_name_err" style="display:none; color:red;"></div>
					</div>
					
					<div class="col-sm-2 mb-3">
						<select name="hostel" id="hostel" class="form-control">
							<option value="">Select Hostel</option>
								<option value="Yes" selected>Yes</option>
								<option value="No">No</option>
						</select>
						<div id="hostel_err" style="display:none; color:red;"></div>
					</div>
					
					<div class="col-sm-2 mb-3">
						<select name="section" id="student_status" class="form-control">
							<option value="">Student Status</option>
								<option value="new">New</option>
								<option value="old">Old</option>
						</select>
						<div id="student_status_err" style="display:none; color:red;"></div>
					</div>
					
					<div class="col-md-2 mb-3">
						<button type="button" id="search" class="btn btn-info pull-left">Search</button>	
					</div>
			    </div>
    		</form>	
		</div><!-- end box body -->
		</div>
		
		<div class="box box-primary no-print">
			<div class="box-body text-center" style="font-size:18px;color:#e24e08;">
				<div class="col-md-4">
					<b style="color:#5d5c5c;">Total Fee</b><br>
					<span id="total_fee"><b>0.00</b></span>
				</div>
				
				<div class="col-md-4" style="border-right:1px solid #ddd;">
					<b style="color:#5d5c5c;">Received Fee</b><br>
					<span id="paid_fee"><b>0.00</b></span>
				</div>
				
				<div class="col-md-4" style="border-right:1px solid #ddd;">
					<b style="color:#5d5c5c;">Pending Fee</b><br>
					<span id="pending_fee"><b>0.00</b></span>
				</div>
				
			</div>
		</div>
	
		<div class="box box-info">
            <div class="box-header">
              <h3 class="box-title"><b>Student List</b></h3>
            </div>
      		     <div class="box-body">
  					<table class="table table-responsive">
						<thead><tr>
						<th>S.No.</th>
						<th>Admission No.</th>
						<th>Hostel Status</th>
						<th>Student Name</th>
                          <th>Father's Name</th>
                          <th>Total Fee</th>
                          <th>Received Fee</th>
                          <th>Pending Fee</th>
                          <th>Hostel Details</th>
                          <th>Action</th>
                        </tr>
                    </thead>
					<tbody id="student_list"><tr><td colspan="9" style="text-align: center;">Record not found.</td></tr></tbody>
				</table>
      		</div>
 		</div>
	</div>
</div>

<script type="text/javascript">
var baseUrl = $('#base_url').val();
var userUrl = $('#user_url').val();
var session = $('#session').val();
var school = $('#school').val();
var hostel = $('#hostel').val();


$(document).on('click','#search',function(){
	var session = $('#session').val();
	var school = $('#school').val();
	var medium = $('#medium').val();
	var class_id = $('#class_name').val();
	var hostel = $('#hostel').val();
	var student_status = $('#student_status').val();

	studentList(session,school,medium,class_id,hostel,student_status);		
});
function studentList(session,school,medium,class_id,hostel,student_status){
		$.ajax({
			type:'POST',
			url:baseUrl+'hostel/Hostel_students_ctrl/studentList',
			data:{'session':session,'school':school,'medium':medium,'class_id':class_id,'hostel':hostel,'student_status':student_status},
			dataType:'json',
			beforeSend:function(){},
			success:function(response){
				if(response.status == 200){
					var x='';
					$.each(response.data,function(key,value){
						if(value.details_status == 'pending'){
							var details_status = '<b style="color:red;">Pending</b>';
						}else{
							var details_status = '<b style="color:green;">Updated</b>';
						}
						x=x+'<tr>'+
							'<td>'+parseInt(key+1)+'</td>'+
							'<td>'+value.adm_no+'</td>'+
							'<td>'+value.std_status+'</td>'+
							'<td>'+value.name+'<br/>'+value.contact_no+'</td>'+
							'<td>'+value.f_name+'<br/>'+value.f_contact_no+'</td>'+
							'<td>'+value.hostel_fee+'</td>'+
							'<td>'+value.paid_fee+'</td>'+
							'<td>'+parseFloat(parseFloat(value.hostel_fee) - parseFloat(value.paid_fee)).toFixed(2)+'</td>'+
							'<td>'+details_status+'</td>'+
							'<td><button data-ses_id="'+value.ses_id+'" data-sch_id="'+value.sch_id+'" data-adm_no="'+value.adm_no+'" class="btn btn-success" id="pay_now">Pay Now</button></td>'+
						  '</tr>';
					});
					$('#student_list').html(x);
					$('#total_fee').html('<b>'+response.all_total_fee+'</b>');
					$('#paid_fee').html('<b>'+response.all_paid_fee+'</b>');
					$('#pending_fee').html('<b>'+response.all_pending_fee+'</b>');
				}else{
					$('#student_list').html('<tr><td colspan="9" style="text-align:center">Record not found.</td></tr>');
					$('#total_fee').html('<b>0.00</b>');
					$('#paid_fee').html('<b>0.00</b>');
					$('#pending_fee').html('<b>0.00</b>');
				}
			},
		});
}

$(document).on('click','#pay_now',function(){
	var ses_id = $(this).data('ses_id');
	var sch_id = $(this).data('sch_id');
	var adm_no = $(this).data('adm_no');

	window.open(baseUrl+userUrl+'/hostel/fee-payment/'+ses_id+'/'+sch_id+'/'+adm_no);
	
});


</script>

