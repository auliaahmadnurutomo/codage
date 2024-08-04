<?php

namespace App\Http\Controllers\setting\SettingOffice;

use DB;
use App\Codeton\DefaultFactoryIndex;
use App\Codeton\PageIndex;
use App\Helpers\ButtonHelper;
use App\Http\Controllers\setting\SettingOffice\SettingOfficeController;

//class name
class SettingOfficeListView extends SettingOfficeController implements DefaultFactoryIndex
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
        'name' => ['column'=>'name','label'=>'Name'],
        'code' => ['column'=>'code','label'=>'Code'],
    	];
    }

    function tableHeader(){
    	return [
    		//nama kolom & label untuk header tabel
    		["col" => "name","title"=>"Name","first"=>1,"mw"=>200],
            ["col" => "code","title"=>"Code","first"=>0,"mw"=>150],
            ["col" => "status","title"=>"Status","first"=>0]
    	];
    }


    function defaultOrderBy(){
    	return 'id'; //default sorting column
    }
    function defaultOrderType(){
    	return 'asc'; //default sorting type
    }

	function TableView(){
        $data = $this->buildIndexView($this->request);
        $this->request->session()->put($this->controller_path, $this->request->fullUrl());
        if (!$this->request->ajax()) {
            $data['fields'] = array_keys($data['list_data'][0] ?? []);
            $data['column_search'] = $this->columnSearch();
    		$data['table_header'] = $this->tableHeader();
            $data['btn_create'] = ButtonHelper::button_modal(url($this->controller_path.'/create'));
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
            DB::table($this->main_table);
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
                'code'              => $key->code,
                'btn_activation'    => ButtonHelper::btn_toggle_activation(
                    $status['color'],$key->id,
                    $key->status,
                    $status['title']),
                'btn_edit'          => (!$this->acl($this->controller_path)) ? '-' :ButtonHelper::btn_detail_modal(
                    url($this->controller_path.'/detail/'.$key->id),'#modalForm'
                ),
                'btn_delete'    => (!$this->acl($this->controller_path)) ? '-' :
                    ButtonHelper::btn_delete($key->id),
                // 'btn_activation'    => ButtonHelper::btn_toggle_activation(
                //     $status['color'],$key->uuid,
                //     $key->status,
                //     $status['title']),
                // 'btn_edit'          => (!$this->acl($this->controller_path)) ? '-' :ButtonHelper::btn_detail_modal(
                //     url($this->controller_path.'/detail/'.$key->uuid),'#modalForm'
                // ),
                // 'btn_delete'    => (!$this->acl($this->controller_path)) ? '-' :
                //     ButtonHelper::btn_delete($key->uuid),
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
        if(is_numeric($status)) {
            $dataList->where('status',$status);
        }
        //lanjutkan filter
        return $dataList;
    } 
}

