namespace {{ $folder }};

use DB;
use App\Codeton\DefaultFactoryIndex;
use App\Codeton\PageIndex;
use App\Helpers\ButtonHelper;
use {{ $folder }}\{{ $file }}Controller;

//class name
class {{ $file }}ListView extends {{ $file }}Controller implements DefaultFactoryIndex
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
    		["orderBy"=>"name","col" => "name","title"=>"Name","first"=>1,"mw"=>200],
            ["orderBy"=>"code","col" => "code","title"=>"Code","first"=>0,"mw"=>150],
            ["orderBy"=>"status","col" => "status","title"=>"Status","first"=>0]
            ["orderBy"=>"model","col" => "mkk.model", "static" => true, "title" => "Kelulusan", "first" => 0, "mw" => 150,"toggleable"=>true],
    	];
    }

    function defaultOrderBy(){
    	return 'id'; //default sorting column
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
                    $status['color'],$key->uuid,
                    $key->status,
                    $status['title']),
                'btn_edit' => (!$this->acl($this->controller_path)) ? '-' : ButtonHelper::href_redirect(
                    target      : url($this->controller_path . '/detail/' . $key->uuid),
                    btn_class   : 'btn-sm btn-light border rounded',
                    icon        : 'expand'
                ),
                'btn_delete'    => (!$this->acl($this->controller_path)) ? '-' :
                    ButtonHelper::btn_delete($key->uuid),
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

