<div class="content-wrapper">
    <section class="content-header">
      <h1>Online<small>fee</small></h1>
      <ol class="breadcrumb">
        <li><a href="<?=base_url();?>admin/dashbord"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li class="active">Generate fee for Online</li>
      </ol>
    </section>
    <!-- Main content -->
	<section class="content">
		<div class="box box-primary" style="position: relative; left: 0px; top: 0px;">		
			<div class="box-header with-border ui-sortable-handle" style="cursor: move;">
			  <h3 class="box-title">Generate fee for Online</h3>
			  <div class="box-tools pull-right">
				<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
				  <i class="fa fa-minus"></i></button>
			  </div>
			</div>
			
			<form role="form" class="form-horizontal" id="fee_generate" method="POST">
			<div class="box-body">
                
                <div class="form-group">
                  <div class="col-sm-6">
					<select id="school" name="school" class="form-control">
						<option value="">Select School</option>
						<option value="1">CBSE</option>
						<option value="3">CG Board</option>
						<option value="2">Sharda</option>
					</select>
					<div id="school_err" class="error" style="display:none;"></div>
				</div>
                </div>
			</div>
			<div class="box-footer">
                      	<div class="col-sm-offset-8">
                            <button type="button" id="submit" class="btn btn-info">Submit</button>
                            <button type="reset" class="btn btn-default">Reset</button>
                        </div>
        			</div>
			</form>
		</div>
        </section>
</div>
<script type="text/javascript">
var baseUrl = $('#base_url').val();


$('#fee_generate').validate({
	rules:{
		school:{required:true},
	},
});

$(document).on('click','#submit',function(){
	var formvalidate = $('#fee_generate').valid();
	if(formvalidate){
		$.ajax({
			type:'POST',
			url:baseUrl+'Generate_fee_ctrl/generate_fee',
			data:{'sch_id':$('#school').val()},
			dataType:'json',
			beforeSend:function(){
				$('#loader').modal('show');
			},
			success:function(response){
				if(response.status == 200){
					alert(response.msg);
					location.reload();
				}else{
					$('#loader').modal('hide');	
					alert(response.msg);
				}
			},
		});
	}
});
</script>


