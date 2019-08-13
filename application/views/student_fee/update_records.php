<style>
.skin-blue .main-header .navbar{padding:10px;}
.mb-3{margin-bottom:15px;}
</style>
<div class="content-wrapper">
<section class="content-header">
      <h1>Reception</h1>
      <ol class="breadcrumb">
        <li><a href="<?=base_url();?>shakuntala/reception/dashbord"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li class="active">Student Fee</li>
      </ol>
    </section>
<section class="content-header">
</section>
	<!-- main section -->
    <div class="col-md-12">	
      	<div class="box box-danger">
            <div class="box-header">
              <h3 class="box-title">Student Filter</h3>
            </div>
        	<div class="box-body form-horizontal">
        	<form id="update_details" action="javascript:void(0);" method="POST" role="form">
        	
        	<input type="hidden" id="user_url" value="<?php echo $this->uri->segment(1).'/'.$this->uri->segment(2);?>">
        	
          			<div class="form-group" style="margin-bottom:0px;">
						<div class="col-sm-2 mb-3">
							<select name="school" id="school" class="form-control">
								<option value="">Select Board</option>
								<option value="1" selected>CBSE</option>
								<option value="3">CG State Board</option>
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
							<select name="section" id="section" class="form-control">
								<option value="">Select Section</option>
								<?php foreach($section as $sec){?>
        							<option value="<?php echo $sec['sec_id'];?>"><?php echo $sec['section_name'];?></option>
        						<?php }?>
							</select>
							<div id="section_err" style="display:none; color:red;"></div>
						</div>
						<div class="col-md-2 mb-3" style="padding:0px 0px;">
							<button type="button" name="search" id="search" class="btn btn-info pull-left">Search</button>
						</div>
				    </div>
					 
				    <div class="col-sm-6 mb-3" style="padding-left:0px;">
						<input type="text" id="search_box" name="search_box" class="form-control" placeholder="Enter Admission Number">	
					</div>
				   
        		</form>	
    		</div><!-- end box body -->
 			</div>
			<div class="box box-info">
                <div class="box-header">
                  <h3 class="box-title">Search Results</h3>
                </div>
          		     <div class="box-body">
      					<table class="table table-responsive">
							<thead><tr>
							<th>S No.</th>
                              <th>Admission No.</th>
                              <th>Class/ Section</th>
                              <th>Student Name</th>
							  <th>Subject Group</th>
                              <th>Fee Criteria</th>
                              <th>Bus</th>
                              <th>Bus Route</th>
                              <th>Hostler</th>
                              <th>Action</th>
                            </tr>
                        </thead>
						<tbody id="student_list"></tbody>
					</table>
          		</div>
     		</div>
		</div>
	</div>


<!-- Modal -->
<div id="std_update_form" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        	<div class="box-body">
        	<form class="form-horizontal" action="javascript:void(0);">
            <input type="hidden" id="std_id" name="std_id">
            
            <div class="form-group">
            	<label class="col-sm-3 control-label">Fee Criteria</label>
            	<div class="col-sm-6">
            		<select class="form-control" name="fee_criteria" id="fee_criteria">
            			<option value="">Select Criteria</option>
            			<?php foreach($fee_criteria as $fee_crit){?>
            				<option value="<?php echo $fee_crit['fc_id'];?>"><?php echo $fee_crit['fc_name'];?></option>
            			<?php } ?>
            		</select>
            		<div id="fee_criteria_err" class="text-danger" style="display:none;"></div>
            	</div>
            </div>
            
            <div class="form-group" style="display: none;" id="staff_child_row">
            	<label class="col-sm-3 control-label">Staff Child</label>
            	<div class="col-sm-6">
            		<select class="form-control" name="staff_child" id="staff_child">
            			<option value="">Select Staff Child</option>
            			<?php foreach($staff_child as $staff_childs){?>
            				<option value="<?php echo $staff_childs['sc_id'];?>"><?php echo $staff_childs['name'];?></option>
            			<?php } ?>
            		</select>
            		<div id="staff_child_err" class="text-danger" style="display:none;"></div>
            	</div>
            </div>
            
            <div class="form-group">
            	<label class="col-sm-3 control-label">Class</label>
            	<div class="col-sm-6">
            		<select class="form-control" name="edit_class" id="edit_class">
            			<option value="">Select Class</option>
            			<?php foreach($class as $classes){?>
            				<option value="<?php echo $classes['c_id'];?>"><?php echo $classes['class_name'];?></option>
            			<?php } ?>
            		</select>
            		<div id="edit_class_err" class="text-danger" style="display:none;"></div>
            	</div>
            </div>
            
            <div class="form-group">
            	<label class="col-sm-3 control-label">Section</label>
            	<div class="col-sm-6">
            	<select class="form-control" name="edit_section" id="edit_section">
            		<option value="">Select Section</option>
            		<?php foreach($section as $sec){?>
            		<option value="<?php echo $sec['sec_id'];?>"><?php echo $sec['section_name'];?></option>
            		<?php }?>
            	</select>
            	<div id="edit_section_err" class="text-danger" style="display:none;"></div>
            	</div>
            </div>
            
            <div class="form-group">
            	<label class="col-sm-3 control-label">Hostler</label>
            	<div class="col-sm-6">
            	<select class="form-control" name="edit_hostler" id="edit_hostler">
            		<option value="">Select Hostler</option>
            		<option value="Yes">Yes</option>
            		<option value="No">No</option>
            	</select>
            	<div id="edit_hostler_err" class="text-danger" style="display:none;"></div>
            	</div>
            </div>
            
            <div class="form-group">
            	<label class="col-sm-3 control-label">Bus</label>
            	<div class="col-sm-6">
            	<select class="form-control" name="edit_bus" id="edit_bus">
            		<option value="">Select Bus</option>
            		<option value="Yes">Yes</option>
            		<option value="No">No</option>
            	</select>
            	<div id="edit_bus_err" class="text-danger" style="display:none;"></div>
            	</div>
            </div>
            
            <div class="form-group">
            	<label class="col-sm-3 control-label">Bus Stoppage</label>
            	<div class="col-sm-6">
            	<select class="form-control" name="bus_stoppage" id="bus_stoppage">
            		<option value="">Select Bus Stoppage</option>
            		<?php foreach($bus as $bus){?>
            			<option value="<?php echo $bus['bs_id'];?>"><?php echo $bus['bus_stoppage'];?></option>
            		<?php }?>
            	</select>
            	<div id="bus_stoppage_err" class="text-danger" style="display:none;"></div>
            	</div>
            </div>  
            
            <div class="form-group">
            	<div class="col-sm-6">
            		<button class="btn btn-sm btn-success" id="update">Update</button>
            	</div>
            </div>  
            
             
            </form>
         </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
var baseUrl = $('#base_url').val();


$(document).on('keyup','#search_box',function(){
	var school = $('#school').val();
	var medium = $('#medium').val();
	var class_name = $('#class_name').val();
	var section = $('#section').val();
	var search_box = $(this).val();
	list(school,medium,class_name,section,search_box);
});



$('#update_details').validate({
	rules:{
		school:{required:true},
		medium:{required:true},
		class_name:{required:true},
		section:{required:true},
	},
});

$(document).on('click','#search',function(){
	var formvalidate = $('#update_details').valid();
	if(formvalidate){
		var school = $('#school').val();
		var medium = $('#medium').val();
		var class_name = $('#class_name').val();
		var section = $('#section').val();
		var search_box = '';
		list(school,medium,class_name,section,search_box);
	}		
});

function list(school,medium,class_name,section,search_box){
	$.ajax({
		type:'POST',
		url:baseUrl+'Student_fee_ctrl/recordList',
		data:{
			'school':school,
			'medium':medium,
			'class_name':class_name,
			'section':section,
			'search_box':search_box
		},
		dataType:'json',
		beforeSend:function(){},
		success:function(response){
			if(response.status == 200){
				var x='';
				$.each(response.data,function(key,value){
					x=x+'<tr>'+
						'<td>'+parseInt(key+1)+'</td>'+
						'<td>'+value.adm_no+'</td>'+
						'<td>'+value.class_name+'/'+value.section_name+'</td>'+
						'<td>'+value.name+'</td>'+
						'<td>'+value.sg_name+'</td>'+
						'<td>'+value.fee_criteria+' '+value.staff_child+'</td>'+
						'<td>'+value.bus+'</td>'+
						'<td>'+value.bus_stoppage+'</td>'+
						'<td>'+value.hostler+'</td>'+
						'<td><button data-std_id = "'+value.std_id+'" class="btn btn-sm btn-success edit">Edit</button></td>'+
					'</tr>';
				});
				$('#student_list').html(x);
			}else{
				$('#student_list').html('<tr><td colspan="10" style="text-align:center;">Record not found.</td></tr>');
			}
		},
	});
}


$(document).on('click','.edit',function(){
	var std_id = $(this).data('std_id');
	$.ajax({
		type:'GET',
		url:baseUrl+'Student_fee_ctrl/studentEditRecord',
		data:{'std_id':std_id},
		dataType:'json',
		beforeSend:function(){},
		success:function(response){
			if(response.status == 200){
				$('#std_id').val(response.data[0].std_id);
				$('#fee_criteria').val(response.data[0].fee_criteria);
				if(response.data[0].fee_criteria == 4){
					$('#staff_child').val(response.data[0].staff_child);
					$('#staff_child').css('display','block');
				}
				$('#edit_class').val(response.data[0].class_id);
				$('#edit_section').val(response.data[0].sec_id);
				$('#edit_hostler').val(response.data[0].hostler);
				$('#edit_bus').val(response.data[0].bus);
				$('#bus_stoppage').val(response.data[0].bus_id);
				$('#std_update_form').modal('show');
			}else{
				alert(response.msg);
			}
		},
	});
});


$(document).on('click','#update',function(){
	var std_id = $('#std_id').val();
	var fee_criteria = $('#fee_criteria').val();
	var staff_child = $('#staff_child').val();
	var class_id = $('#edit_class').val();
	var section = $('#edit_section').val();
	var hostler = $('#edit_hostler').val();
	var bus = $('#edit_bus').val();
	var bus_stoppage = $('#bus_stoppage').val();
	
	if(fee_criteria == 4 && staff_child == ''){
		$('#staff_child_err').html('This is Required.').css('display','block');
		return false;
	}else{
		$('#staff_child_err').css('display','none');
	}
	
	if(bus == 'Yes' && bus_stoppage == ''){
		$('#bus_stoppage_err').html('This is Required.').css('display','block');
		return false;
	}else{
		$('#bus_stoppage_err').css('display','none');	
	}

	if(bus_stoppage != '' && bus == 'No'){
		$('#edit_bus_err').html('This is Required.').css('display','block');
		return false;
	}else{
		$('#edit_bus_err').css('display','none');
	}
	
	$.ajax({
		type:'POST',
		url:baseUrl+'Student_fee_ctrl/update_records',
		data:{
			'std_id':std_id,
			'fee_criteria':fee_criteria,
			'staff_child':staff_child,
			'class_id':class_id,
			'section':section,
			'hostler':hostler,
			'bus':bus,
			'bus_stoppage':bus_stoppage,
		},
		dataType:'json',
		beforeSend:function(){},
		success:function(response){
			if(response.status == 200){
				alert(response.msg);
				$('#std_update_form').modal('hide');
				if($('#search_box').val() != ''){
					$('#search_box').trigger('keyup');
				}else{
					$('#search').trigger('click');
				}
				
			}else{
				alert(response.msg);
			}
		},
	});
	
});

$(document).on('click','#fee_criteria',function(){
	var fee_criteria = $(this).val();
	if(fee_criteria == 4){
		$('#staff_child_row').css('display','block');
	}else{
		$('#staff_child').prop('selectedIndex','');
		$('#staff_child_row').css('display','none');
	}
});
</script>