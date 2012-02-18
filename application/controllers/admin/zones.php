<?php

class Zones extends Application
{
	
	public function __construct()
	{
		parent::__construct();
		$this->ag_auth->restrict('admin'); // restrict this controller to admins only
		$this->table_tpl = array(
			'table_open' => '<table border="0" cellpadding="4" cellspacing="0" class="dataTable">'
		);
		$this->table->set_template($this->table_tpl);
		
		$this->breadcrumb->add_crumb('Home','admin/dashboard');
		$this->breadcrumb->add_crumb('System','admin/apps/manage');
		
	}

	public function ajaxmanage(){

		$limit_count = $this->input->post('iDisplayLength');
		$limit_offset = $this->input->post('iDisplayStart');
		
		$sort_col = $this->input->post('iSortCol_0');
		$sort_dir = $this->input->post('sSortDir_0');

		$columns = array(
			'district','city','province','country'
			);

		// get total count result
		$count_all = $this->db->count_all($this->config->item('jayon_zones_table'));
		$count_display_all = $this->db->count_all_results($this->config->item('jayon_zones_table'));

		if($this->input->post('sSearch') != ''){
			$srch = $this->input->post('sSearch');
			$this->db->like('district',$srch);
			$this->db->or_like('city',$srch);
			$this->db->or_like('province',$srch);
			$this->db->or_like('country',$srch);
		}
		
		$data = $this->db->limit($limit_count, $limit_offset)->order_by($columns[$sort_col],$sort_dir)->get($this->config->item('jayon_zones_table'));
		
		//print $this->db->last_query();
		
		$result = $data->result_array();
			
		$aadata = array();
		
		
		foreach($result as $value => $key)
		{
			$delete = anchor("admin/zones/delete/".$key['id']."/", "Delete"); // Build actions links
			$edit = anchor("admin/zones/edit/".$key['id']."/", "Edit"); // Build actions links
			//$aadata[] = array($key['holiday'],$key['holidayname'],$edit.' '.$delete); // Adding row to table
			$aadata[] = array($key['district'],$key['city'],$key['province'],$key['country'],$edit.' '.$delete); // Adding row to table
		}
		
		$result = array(
			'sEcho'=> $this->input->post('sEcho'),
			'iTotalRecords'=>$count_all,
			'iTotalDisplayRecords'=> $count_display_all,
			'aaData'=>$aadata
		);
		
		print json_encode($result);
	}
	
	
	public function manage()
	{
	    $this->load->library('table');		

		$this->breadcrumb->add_crumb('Zones','admin/zones/manage');
			
		$data = $this->db->get($this->config->item('jayon_zones_table'));
		$result = $data->result_array();
		$this->table->set_heading('District', 'City','Province','Country','Actions'); // Setting headings for the table
		
		foreach($result as $value => $key)
		{
			$delete = anchor("admin/zones/delete/".$key['id']."/", "Delete"); // Build actions links
			$edit = anchor("admin/zones/edit/".$key['id']."/", "Edit"); // Build actions links
			$this->table->add_row($key['district'],$key['city'],$key['province'],$key['country'],$edit.' '.$delete); // Adding row to table
		}

		$page['sortdisable'] = '';
		$page['ajaxurl'] = 'admin/zones/ajaxmanage';
		$page['add_button'] = array('link'=>'admin/zones/add','label'=>'Add New Zone');
		$page['page_title'] = 'Manage Zones';
		$this->ag_auth->view('ajaxlistview',$page); // Load the view
	}
	
	public function delete($id)
	{
		$this->db->where('id', $id)->delete($this->config->item('jayon_zones_table'));
		$page['page_title'] = 'Delete Zone';
		$this->ag_auth->view('zones/delete_success',$page);
	}

	public function get_zones($id){
		$result = $this->db->where('id', $id)->get($this->config->item('jayon_zones_table'));
		if($result->num_rows() > 0){
			return $result->row_array();
		}else{
			return false;
		}
	}
	
	public function update_holiday($id,$data){
		$result = $this->db->where('id', $id)->update($this->config->item('jayon_zones_table'),$data);
		return $this->db->affected_rows();
	}
	
	
	public function add()
	{
		$this->form_validation->set_rules('district', 'District', 'required|trim|xss_clean');
		$this->form_validation->set_rules('city', 'City', 'required|trim|xss_clean');
		$this->form_validation->set_rules('province', 'Province', 'required|trim|xss_clean');
		$this->form_validation->set_rules('country', 'Country', 'required|trim|xss_clean');
				
		if($this->form_validation->run() == FALSE)
		{	
			$data['page_title'] = 'Add Zone';
			$this->ag_auth->view('zones/add',$data);
		}
		else
		{
			$dataset['district'] = set_value('district');
			$dataset['city'] = set_value('city');
			$dataset['province'] = set_value('province');
			$dataset['country'] = set_value('country');
			
			if($this->db->insert($this->config->item('jayon_zones_table'),$dataset) === TRUE)
			{
				$data['message'] = "The zone has now been set.";
				$data['page_title'] = 'Add Zone';
				$data['back_url'] = anchor('admin/zones/manage','Back to list');
				$this->ag_auth->view('message', $data);
				
			} // if($this->ag_auth->register($username, $password, $email) === TRUE)
			else
			{
				$data['message'] = "The zone can not be set.";
				$data['page_title'] = 'Add Zone Error';
				$data['back_url'] = anchor('admin/zones/manage','Back to list');
				$this->ag_auth->view('message', $data);
			}

		} // if($this->form_validation->run() == FALSE)
		
	} // public function register()

	public function edit($id)
	{
		$this->form_validation->set_rules('district', 'District', 'required|trim|xss_clean');
		$this->form_validation->set_rules('city', 'City', 'required|trim|xss_clean');
		$this->form_validation->set_rules('province', 'Province', 'required|trim|xss_clean');
		$this->form_validation->set_rules('country', 'Country', 'required|trim|xss_clean');
		
		$user = $this->get_zones($id);
		$data['user'] = $user;
				
		if($this->form_validation->run() == FALSE)
		{
			$data['page_title'] = 'Edit Zone';
			$this->ag_auth->view('zones/edit',$data);
		}
		else
		{
			$dataset['district'] = set_value('district');
			$dataset['city'] = set_value('city');
			$dataset['province'] = set_value('province');
			$dataset['country'] = set_value('country');
			
			if($this->db->where('id',$id)->update($this->config->item('jayon_zones_table'),$dataset) == TRUE)
			//if($this->update_user($id,$dataset) === TRUE)
			{
				$data['message'] = "The zone has now updated.";
				$data['page_title'] = 'Edit Zone';
				$data['back_url'] = anchor('admin/zones/manage','Back to list');
				$this->ag_auth->view('message', $data);
				
			} // if($this->ag_auth->register($username, $password, $email) === TRUE)
			else
			{
				$data['message'] = "The zone can not be updated.";
				$data['page_title'] = 'Edit Zone Error';
				$data['back_url'] = anchor('admin/zones/manage','Back to list');
				$this->ag_auth->view('message', $data);
			}

		} // if($this->form_validation->run() == FALSE)
		
	} // public function register()

}

?>