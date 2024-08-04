<?php

namespace App\Http\Controllers\root\SettingMenuAccess;

use DB;
use App\Codeton\DefaultFactoryIndex;
use App\Codeton\PageIndex;
use App\Helpers\ButtonHelper;
use App\Http\Controllers\root\SettingMenuAccess\SettingMenuAccessController;

//class name
class SettingMenuAccessListView extends SettingMenuAccessController implements DefaultFactoryIndex
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
        'code' => ['column'=>'a.url','label'=>'URL'],
    	];
    }

    function tableHeader(){
    	return [
    		//nama kolom & label untuk header tabel
    		["col" => "name","title"=>"Name","first"=>1,"mw"=>200],
            ["col" => "icon","title"=>"Icon","first"=>0,"mw"=>100],
            ["col" => "parent","title"=>"Parent","first"=>0,"mw"=>150],
            ["col" => "menu_order","title"=>"Order","first"=>0,"mw"=>100],
            ["col" => "url","title"=>"Url","first"=>0,"mw"=>100],
            ["col" => "access","title"=>"Access","first"=>0,"mw"=>100],
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
            DB::table($this->main_table.' as a')
                ->leftJoin($this->main_table.' as b','b.id','a.id_parent')
                ->select('a.*','b.name as parent');
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
                'name'              => $key->name.'<br><small>'.($key->type ? 'Menu' : 'Access').'</small>',
                'icon'              => $key->icon,
                'parent'              => $key->parent,
                'menu_order'              => $key->menu_order,
                'url'              => $key->url,
                'access'              => $key->access ? 'Root' : 'User',
                'btn_activation'    => ButtonHelper::btn_toggle_activation(
                    $status['color'],$key->id,
                    $key->status,
                    $status['title']),
                'btn_edit'          =>ButtonHelper::btn_detail_modal(
                    url($this->controller_path.'/detail/'.$key->id),'#modalForm'
                ),
                'btn_delete'    => 
                    ButtonHelper::btn_delete($key->id),
                
                // 'btn_edit'          => (!$this->acl($this->controller_path)) ? '-' :ButtonHelper::btn_detail_modal(
                //     url($this->controller_path.'/detail/'.$key->id),'#modalForm'
                // ),
                // 'btn_delete'    => (!$this->acl($this->controller_path)) ? '-' :
                //     ButtonHelper::btn_delete($key->id),
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
        $parent = $this->request->get('parent');
        if(is_numeric($status)) {
            $dataList->where('status',$status);
        }
        if(is_numeric($parent)) {
            $dataList->where('a.id_parent',$parent);
        }
        //lanjutkan filter
        return $dataList;
    } 
}

