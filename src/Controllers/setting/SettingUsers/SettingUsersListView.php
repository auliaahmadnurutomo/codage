<?php

namespace App\Http\Controllers\setting\SettingUsers;

use DB;
use App\Codeton\DefaultFactoryIndex;
use App\Codeton\PageIndex;
use App\Helpers\ButtonHelper;
use App\Http\Controllers\setting\SettingUsers\SettingUsersController;

//class name
class SettingUsersListView extends SettingUsersController implements DefaultFactoryIndex
{
	use PageIndex;
	public $request;
	function __construct($request)
    {
        $this->request = $request;
    }

    function columnSearch(){
    	return [
    	//nama kolom & label untuk option drowdown di search
            'name' => ['column'=>'a.name','label'=>'Name'],
            'email' => ['column'=>'a.email','label'=>'Email'],
    	];
    }

    function tableHeader(){
    	return [
    		//nama kolom & label untuk header tabel
    		["col" => "a.name","title"=>"Name","first"=>1,"mw"=>200],
            ["col" => "a.email","title"=>"Email","first"=>0,"mw"=>150],
            ["col" => "e.name", "title" => "Role", "first" => 0],
            ["col" => "office.name", "title" => "Office", "first" => 0],
            ["col" => "d.status","title"=>"Status","first"=>0]
    	];
    }

    function defaultOrderBy(){
    	return 'a.name'; //default sorting column
    }
    function defaultOrderType(){
    	return 'asc'; //default sorting type
    }

    /**
     * Undocumented function
     *
     * @return View|void|null
     */
	function TableView(){
        $this->request->session()->put($this->controller_path, $this->request->fullUrl());
        $data = $this->buildIndexView($this->request);
        if (!$this->request->ajax()) {
            $data['fields'] = array_keys($data['list_data'][0] ?? []);
            $data['column_search'] = $this->columnSearch();
    		$data['table_header'] = $this->tableHeader();
            $data['btn_create'] = ButtonHelper::href_redirect(
                    target      : url($this->controller_path . '/create'),
                    btn_class   : 'btn-md btn-primary',
                    icon        : 'plus',
                    text        : 'New'
                );
            //move
            return view($this->view_folder . '.page-index', $data);
        } else {
            return response()->json($data);
        }
        // return $this->buildIndexView($this->request);
	}

	function queryDataList(){
		$dataList =
		//free fow query list
        DB::table($this->main_table . ' as a')
        ->leftJoin('skeleton_users_info as d','d.id_user','a.id')
        ->leftJoin('skeleton_setting_department as dept','dept.id','d.id_department')
        ->leftJoin('skeleton_setting_position as pos','pos.id','d.id_position')
        ->leftJoin('skeleton_setting_office as office','office.id','d.id_office')
        ->leftJoin('skeleton_setting_menu_template as e','e.id','d.id_access_template')
        ->selectRaw('a.*,d.status, dept.name as department, pos.name as staff_position, office.name as office, e.name as role');
		return $dataList;
	}

	function set_data_before_send($dataResults){
        $no =  $this->set_numbering($dataResults);
        $list_data = [];
        foreach ($dataResults as $key) {
            $status = $this->set_toggle_status($key->status);
            $list_data[] = [
            	//generate sebelum dikirim ke view
                'no'                => $no,
                'name'              => $key->name,
                'email'              => $key->email,
                'role'              => $key->role,
                'office_name'              => $key->office,
                'btn_activation'    => ButtonHelper::btn_toggle_activation(
                    $status['color'],$key->id,
                    $key->status,
                    $status['title']),
                'btn_edit' => (!$this->acl($this->controller_path)) ? '-' : ButtonHelper::href_redirect(
                    target      : url($this->controller_path . '/detail/' . $key->id),
                    btn_class   : 'btn-sm btn-light border rounded',
                    icon        : 'expand'
                ),
                'btn_delete'    => (!$this->acl($this->controller_path)) ? '-' :
                    ButtonHelper::btn_delete($key->id),
            ];
            $no++;
        }
        return $list_data;
    }

    /**
     * Undocumented function
     *
     * @param [type] $request
     * @return void
     */
    function filterColumn($dataList){
    	//filter process
        $status = $this->request->get('status');
        $office_detail = $this->request->get('office_detail');
        if(is_numeric($status)) {
            $dataList->where('d.status',$status);
        }

        if ($office_detail) {
            $dataList->where('office.code', $office_detail);
        }
        //lanjutkan filter
        return $dataList;
    }  
}

