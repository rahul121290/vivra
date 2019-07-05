<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Marks_entry_ctrl extends CI_Controller{
  
    public function __construct(){
        parent::__construct();
        $this->load->model('marks_entry_model');
        $this->load->library(array('ion_auth'));
    }
    
    private function _ShowMsgs($successfull, $successMsg, $failureMsg){
        if($successfull){
            echo json_encode(array('feedback'=>$successMsg,'feedback_class'=>'alert-success','status'=>200));
        }else{
            echo json_encode(array('feedback'=>$failureMsg,'feedback_class'=>'alert-danger','status'=>500));
        }
    }
    
    public function getSubjects(){
        $session = $this->session->userdata('session_id');
        $school = $this->session->userdata('school_id');
        $exam_type = $this->input->post('exam_type');
        
        $medium = $this->input->post('medium');
        $class = $this->input->post('class_name');
        $sub_group = $this->input->post('sub_group');
        $section = $this->input->post('section');
        $sub_type = $this->input->post('sub_type');
        
        if($this->ion_auth->is_admin()){ //-----------login member is admin---------------------
            $this->db->select('sa.sub_id,s.sub_name,st.st_name');
            $this->db->join('subject s','s.sub_id=sa.sub_id');
            $this->db->join('sub_type st','st.st_id=s.st_id');
            if(!empty($sub_group)){
                $this->db->where('sa.sg_id',$sub_group);
            }
            $this->db->order_by('s.short_order','ASC');
            $result = $this->db->get_where('subject_allocation sa',array('sa.ses_id'=>$session,'sa.sch_id'=>$school,'sa.med_id'=>$medium,'sa.class_id'=>$class,'sa.st_id'=>$sub_type,'sa.status'=>1))->result_array();
        }else{
            //-------------check login member is teacher--------------------
            $this->db->select('t.t_id,t.email');
            $this->db->join('teacher t','t.t_id = u.t_id');
            $teacher = $this->db->get_where('users u',array('u.active'=>1,'u.status'=>1,'u.id'=>$this->session->userdata('user_id')))->result_array();
            if(count($teacher)>0){
                //-----------get teacher subjects-------------------------
                $this->db->select('sa.sub_id,s.sub_name,subt.st_name');
                $this->db->join('subject_allocation sa','sa.sa_id=st.sa_id');
                $this->db->join('subject s','s.sub_id=sa.sub_id');
                $this->db->join('sub_type subt','subt.st_id=s.st_id');
                $this->db->where('st.t_id',$teacher[0]['t_id']);
                $this->db->where(array('sa.ses_id'=>$session,'sa.sch_id'=>$school,'sa.med_id'=>$medium,'sa.class_id'=>$class,'st.sec_id'=>$section,'sa.st_id'=>$sub_type,'sa.status'=>1));
                if(!empty($sub_group)){
                    $this->db->where('sa.sg_id',$sub_group);
                }
                $this->db->order_by('s.short_order','ASC');
                $result = $this->db->get_where('sub_teacher st',array('st.status'=>1))->result_array();
                //print_r($this->db->last_query());die;
            }
        }
        
        if(count($result)>0){
            echo json_encode(array('result'=>$result,'status'=>200));
        }else{
            echo json_encode(array('feedback'=>'This class not allotted any subjecs.','status'=>500));
        }
    }
   
    public function getStudentsRecords(){
        $session = $this->session->userdata('session_id');
        $school = $this->session->userdata('school_id');
        $exam_type = $this->input->post('exam_type');
        $medium = $this->input->post('medium');
        $class_name = $this->input->post('class_name');
        $sub_group = $this->input->post('sub_group');
        $section = $this->input->post('section');
        $sub_type = $this->input->post('sub_type');
        $subject = $this->input->post('subject'); 
       
        $this->db->select('mm_id,status');
        $this->db->order_by('mm_id','DESC')->limit(1);
        if(!empty($sub_group)){
            $this->db->where('sg_id',$sub_group);
        }
        $mark_master = $this->db->get_where('marks_master',array('ses_id'=>$session,'sch_id'=>$school,'et_id' =>$exam_type,'med_id' =>$medium,'class_id'=>$class_name,'sec_id'=>$section,'st_id'=>$sub_type,'sub_id'=>$subject,'status'=> 1))->result_array(); 
        
        $mm_id = 'mm_id=0';
        if(count($mark_master) > 0){
           $mm_id = 'mm_id='.$mark_master[0]['mm_id'];
        }
        //--------------get studdet records with marks--------------------------------------
        $this->db->select('sd.std_id,sd.name,sd.adm_no,sd.roll_no,c.class_name, sec.section_name, IFNULL(sm.sub_marks,"") as sub_marks,IFNULL(sm.practical,"") as practical,IFNULL(sm.notebook,"") as notebook,IFNULL(sm.enrichment,"") as enrichment,IFNULL(sm.acadmic,"") as acadmic');
        $this->db->join('class c','c.c_id=sd.class_id');
        $this->db->join('section sec','sec.sec_id=sd.sec_id');
        $this->db->join('(SELECT * FROM student_marks WHERE '.$mm_id.') sm','sm.std_id=sd.std_id','LEFT');
        if(!empty($sub_group)){
           $this->db->where('sd.sub_group',$sub_group);
        }
        if($class_name >= 14 && $sub_type == 3){
            $this->db->where('sd.elective',$subject);
        }
        $this->db->group_by('sd.std_id');
        $students = $this->db->get_where('students sd',array('sd.ses_id'=>$session,'sd.sch_id'=>$school,'sd.medium'=>$medium,'sd.class_id'=>$class_name,'sd.sec_id'=>$section,'sd.status'=>1))->result_array();
        //print_r($this->db->last_query());die;
        
        //------------get max marks------------------------------------------------------
        $this->db->select('om.out_of as sub_marks,IFNULL(om.practical,"") as practical');
        $this->db->join('out_of_marks om','om.sa_id=sa.sa_id');
        if(!empty($sub_group)){
           $this->db->where('sa.sg_id',$sub_group);
        }
        $this->db->where('om.et_id',$exam_type);
        $max_marks = $this->db->get_where('subject_allocation sa',array('sa.ses_id'=>$session,'sa.sch_id'=>$school,'sa.med_id'=>$medium,'sa.class_id'=>$class_name,'sa.st_id'=>$sub_type,'sa.sub_id'=>$subject,'sa.status'=>1))->result_array();
       
        
        if(count($max_marks) < 1){
            echo json_encode(array('feedback'=>'This Class/Subject MAX marks not define.','status'=>500));
            die;
        }
        
        if(count($students)>0){
           echo json_encode(array('students'=>$students,'max_marks'=>$max_marks,'status'=>200));
        }else{
           echo json_encode(array('feedback'=>'Students not found..!','status'=>500));
        }
    }
    
    public function marksEntry(){
        $data['session'] = $this->session->userdata('session_id');
        $data['school'] = $this->session->userdata('school_id');
        $data['exam_type'] = $this->input->post('exam_type');
        $data['medium'] = $this->input->post('medium');
        $data['class'] = $this->input->post('class_name');
        if(!empty($this->input->post('sub_group'))){
            $data['sub_group'] = $this->input->post('sub_group');
        }else{
            $data['sub_group'] = '';
        }
        $data['section'] = $this->input->post('section');
        $data['sub_type'] = $this->input->post('sub_type');
        $data['subject'] = $this->input->post('subject');
        
        $student_marks = json_decode($this->input->post('std_marks'),true);
        
        $final = array();
        foreach($student_marks as $std_mark){
            $temp = array();
            $temp['std_id'] = $std_mark[0]['std_id'];
            $temp['adm_no'] = $std_mark[1]['adm_no'];
            $temp['roll_no'] = $std_mark[2]['roll_no'];
            $temp['sub_marks'] = $std_mark[3]['subject_marks'];
            $temp['notebook'] = $std_mark[4]['notebook'];
            $temp['enrichment'] = $std_mark[5]['enrichment'];
            $temp['practical'] = $std_mark[6]['practical'];
            $temp['acadmic'] = $std_mark[7]['acadmic'];
            $final[] = $temp;
        }

        $this->_ShowMsgs(
            $this->marks_entry_model->marks_entry($data,$final),"Marks Entry Successfully.","Falied Proccess Marks Entry, Please try again."
            );       
    }
    
    public function download_sample(){
        $data['session'] = $this->session->userdata('session_id');
        $data['school'] = $this->session->userdata('school_id');
        $data['medium'] = $this->input->post('medium');
        $data['class_name'] = $this->input->post('class_name');
        $data['sub_group'] = $this->input->post('sub_group');
        $data['section'] = $this->input->post('section');
        
        $this->db->select('std_id,adm_no,roll_no');
        if(!empty($data['sub_group'])){
            $this->db->where('sub_group',$data['sub_group']);
        }
        $result = $this->db->get_where('students',array('ses_id'=>$data['session'],'sch_id'=>$data['school'],'medium'=>$data['medium'],'class_id'=>$data['class_name'],'sec_id'=>$data['section'],'status'=>1))->result_array();
        
        //-------------generate csv file------------------------
        $phpExcel = new PHPExcel();
        $prestasi = $phpExcel->setActiveSheetIndex(0);
        
        //----------put index name-------------------
        $prestasi->setCellValue('A1', 'std_id');
        $prestasi->setCellValue('B1', 'adm_no');
        $prestasi->setCellValue('C1', 'roll_no');
        $prestasi->setCellValue('D1', 'sub_marks');
        $prestasi->setCellValue('E1', 'practical');
        $prestasi->setCellValue('F1', 'notebook');
        $prestasi->setCellValue('G1', 'enrichment');
        $prestasi->setCellValue('H1', 'acadmic');
        
        //---------------------put data in excel----------------------------
        $no=0;
        $rowexcel = 1;
        foreach($result as $row){
            $no++;
            $rowexcel++;
            $prestasi->setCellValue('A'.$rowexcel, $row["std_id"]);
            $prestasi->setCellValue('B'.$rowexcel, $row["adm_no"]);
            $prestasi->setCellValue('C'.$rowexcel,  $row["roll_no"]);
        }
        
        $date =date('U');
        $objWriter = PHPExcel_IOFactory::createWriter($phpExcel, 'Excel2007');
        
        if(!is_dir('./assets/sample_data/marks_entry')){
            mkdir('./assets/sample_data/marks_entry');
        }
        $sg_name= '';
        if(!empty($data['sub_group'])){
            $sg_name= '_Group_'.$data['sub_group'];
        }
        $filename = "assets/sample_data/marks_entry/Class".$data['class_name']."_Sec_".$data['section']."_".$sg_name.".xlsx";
        //----------save excel file----------------------------
        $objWriter->save($filename);
        //-----------------------------------------------------
        if($filename){
            echo json_encode(array('file_path'=>$filename,'status'=>200));
        }else{
            echo json_encode(array('feedback'=>'something wrong.','status'=>500));
        }    
    }
    
    public function marks_csv_import(){
        $data['session'] = $this->session->userdata('session_id');
        $data['school'] = $this->session->userdata('school_id');
        $data['exam_type'] = $this->input->post('exam_type');
        $data['medium'] = $this->input->post('medium');
        $data['class'] = $this->input->post('class_name');
        $data['sub_group'] = $this->input->post('sub_group');
        $data['section'] = $this->input->post('section');
        $data['sub_type'] = $this->input->post('sub_type');
        $data['subject'] = $this->input->post('subject');
        
        $result = $this->marks_entry_model->marks_csv_import($data);
        if($result){
            echo json_encode(array('feedback'=>'Import Successfully.','status'=>200));
        }else{
            echo json_encode(array('feedback'=>'Something Wrong.','status'=>500));
        }
    }
    
    function get_section(){
        $data['user_id'] = $this->session->userdata('user_id');
        $data['session_id'] = $this->session->userdata('session_id');
        $data['school_id'] = $this->session->userdata('school_id');
        
        $data['exam_type'] = $this->input->post('exam_type');
        $data['medium'] = $this->input->post('medium');
        $data['class_id'] = $this->input->post('class_id');
        $data['sub_group'] = $this->input->post('sub_group');
        
        $this->db->select('t_id');
        $teacher = $this->db->get_where('users',array('id'=>$data['user_id']))->result_array();
        if(count($teacher) > 0){
            $this->db->select('st.sec_id,sec.section_name');
            $this->db->join('subject_allocation sa','sa.sa_id=st.sa_id');
            $this->db->join('section sec','sec.sec_id=st.sec_id');
            $this->db->join('teacher t1','t1.t_id=st.t_id');
            $this->db->where('st.t_id',$teacher[0]['t_id']);
            $this->db->where('sa.class_id',$data['class_id']);
            $this->db->where('sa.med_id',$data['medium']);
            if(!empty($data['sub_group'])){
                $this->db->where('sa.sg_id',$data['medium']);
            }
            $this->db->group_by('st.sec_id');
            $section = $this->db->get_where('sub_teacher st',array('st.status'=>1))->result_array();
            if(count($section) > 0){
                echo json_encode(array('data'=>$section,'status'=>200));
            }else{
                echo json_encode(array('msg'=>'Teacher not allocate subjects.','status'=>500));
            }
        }else{
            echo json_encode(array('msg'=>'Something went wrong.','status'=>500));
        }
    }
    
    function getSubGroup(){
        $data['user_id'] = $this->session->userdata('user_id');
        $data['session_id'] = $this->session->userdata('session_id');
        $data['school_id'] = $this->session->userdata('school_id');
        
        $data['exam_type'] = $this->input->post('exam_type');
        $data['medium'] = $this->input->post('medium');
        $data['class_id'] = $this->input->post('class_id');
        
        $this->db->select('t_id');
        $teacher = $this->db->get_where('users',array('id'=>$data['user_id']))->result_array();
        if(count($teacher) > 0){
            $this->db->select('sa.sg_id,sg.sg_name');
            $this->db->join('subject_allocation sa','sa.sa_id=st.sa_id');
            $this->db->join('sub_group sg','sg.sg_id = sa.sg_id');
            $this->db->where('st.t_id',$teacher[0]['t_id']);
            $this->db->where('sa.class_id',$data['class_id']);
            $this->db->where('sa.med_id',$data['medium']);
            $this->db->group_by('sa.sg_id');
            $subgroup = $this->db->get_where('sub_teacher st',array('st.status'=>1))->result_array();
            if(count($subgroup) > 0){
                echo json_encode(array('data'=>$subgroup,'status'=>200));
            }else{
                echo json_encode(array('msg'=>'Teacher not allocate subjects.','status'=>500));
            }
        }else{
            echo json_encode(array('msg'=>'Something went wrong.','status'=>500));
        }
    }
    
    function getSubjectType(){
        $data['user_id'] = $this->session->userdata('user_id');
        $data['session_id'] = $this->session->userdata('session_id');
        $data['school_id'] = $this->session->userdata('school_id');
        
        $data['exam_type'] = $this->input->post('exam_type');
        $data['medium'] = $this->input->post('medium');
        $data['class_id'] = $this->input->post('class_id');
        $data['section'] = $this->input->post('section');
        
        $this->db->select('t_id');
        $teacher = $this->db->get_where('users',array('id'=>$data['user_id']))->result_array();
        if(count($teacher) > 0){
            $this->db->select('sa.st_id,sub_t.st_name');
            $this->db->join('subject_allocation sa','sa.sa_id=st.sa_id');
            $this->db->join('sub_type sub_t','sub_t.st_id=sa.st_id');
            $this->db->join('sub_group sg','sg.sg_id=sa.sg_id','LEFT');
            $this->db->join('teacher t1','t1.t_id=st.t_id');
            $this->db->where('st.t_id',$teacher[0]['t_id']);
            $this->db->where('sa.class_id',$data['class_id']);
            $this->db->where('sa.med_id',$data['medium']);
            $this->db->where('st.sec_id',$data['section']);
            $this->db->group_by('sa.st_id');
            $subject_type = $this->db->get_where('sub_teacher st',array('st.status'=>1))->result_array();
            
            if(count($subject_type) > 0){
                echo json_encode(array('data'=>$subject_type,'status'=>200));
            }else{
                echo json_encode(array('msg'=>'Teacher not allocate subjects.','status'=>500));
            }
        }else{
            echo json_encode(array('msg'=>'Something went wrong.','status'=>500));
        }
    }
    
}