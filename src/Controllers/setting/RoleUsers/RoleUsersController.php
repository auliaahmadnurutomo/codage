<?php

namespace App\Http\Controllers\setting\RoleUsers;

use App\Http\Controllers\Codeton;
use App\Helpers\Logger;
use App\Codeton\GenerateMenuSidebar;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Exception;

/**
 * Class RoleUsersController
 * 
 * Handles role and user management operations
 */
class RoleUsersController extends Codeton
{
    /**
     * View folder path
     *
     * @var string
     */
    protected string $viewFolder = 'setting/RoleUsers';

    /**
     * Main database table
     *
     * @var string
     */
    protected string $mainTable = 'skeleton_setting_menu_template';

    /**
     * Controller path
     *
     * @var string
     */
    protected string $controllerPath = 'roleUsers';

    /**
     * Current record ID
     *
     * @var int|null
     */
    private ?int $id = null;

    /**
     * Logger instance
     *
     * @var Logger
     */
    protected Logger $logger;

    /**
     * Constructor
     *
     * @param Request $request
     * @param Logger $logger
     */
    public function __construct(Request $request, Logger $logger)
    {
        $this->logger = $logger;
        View::share([
            'controller_path' => $this->controllerPath,
        ]);
    }

    /**
     * Display index page
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function pageIndex(Request $request)
    {
        if (!$this->acl($this->controllerPath)) {
            abort(401);
        }

        $list = new DataList(request: $request);
        return $list->tableView();
    }

    /**
     * Toggle activation status
     *
     * @param Request $request
     * @return Response
     */
    public function activation(Request $request): Response
    {
        if (!$request->ajax() || !$this->acl($this->controllerPath)) {
            abort(401);
        }

        $status = ($request->get('data3')) ? 0 : 1;
        $dataUpdate = ['status' => $status];
        $id = $request->get('data2');

        if (!$id) {
            return $this->returnResponse('Payload error', 'response/error_reload_full');
        }

        $this->query = DB::table($this->mainTable)->where('id', $id);
        return $this->simpleActionToggle($request, $dataUpdate);
    }

    /**
     * Delete a record
     *
     * @param Request $request
     * @return Response
     */
    public function delete(Request $request): Response
    {
        if (!$request->ajax() || !$this->acl($this->controllerPath)) {
            abort(401);
        }

        $id = $request->get('data2');
        
        if (!$id) {
            return $this->returnResponse('Payload error', 'response/error_reload_full');
        }

        DB::beginTransaction();
        try {
            $this->query = DB::table($this->mainTable)->where('id', $id);
            DB::table('skeleton_setting_template_access')
                ->where('id_menu_template', $id)
                ->delete();
                
            $result = $this->simpleActionDelete($request);
            DB::commit();
            return $result;
        } catch (Exception $e) {
            DB::rollback();
            $this->logger->error($e);
            return $this->returnResponse('Delete failed: ' . $e->getMessage(), 'response/error_reload_full');
        }
    }

    /**
     * Show create form
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        if (!$this->acl($this->controllerPath)) {
            abort(401);
        }

        $menu = new GenerateMenuSidebar();
        return view($this->viewFolder . '.form', [
            'type' => 'create',
            'acl' => $menu->aclTree()
        ]);
    }

    /**
     * Store a new record
     *
     * @param Request $request
     * @return Response|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if (!$request->ajax() || !$this->acl($this->controllerPath)) {
            abort(401);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:' . $this->mainTable . ',name',
            'authorization' => 'required|array',
            'authorization.*' => 'required|exists:skeleton_setting_menu_access,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            DB::beginTransaction();
            
            $idRole = DB::table($this->mainTable)->insertGetId([
                'name' => $request->name,
                'id_user_insert' => Auth::id(),
                'id_user_update' => Auth::id(),
                'id_office' => 1
            ]);

            $this->insertAccess($idRole, $request->input('authorization'));
            
            DB::commit();
            
            return $this->returnResponse(
                'Insert Successful',
                'response/success_reload_full',
                $request->session()->get($this->controllerPath)
            );
        } catch (Exception $e) {
            DB::rollback();
            $this->logger->error($e);
            return $this->returnResponse(
                'Error during insert: ' . $e->getMessage(),
                'response/error_reload_full'
            );
        }
    }

    /**
     * Insert access rights
     *
     * @param int $idMenuTemplate
     * @param array $authorization
     * @return void
     */
    private function insertAccess(int $idMenuTemplate, array $authorization): void
    {
        $dataInsert = array_map(function ($value) use ($idMenuTemplate) {
            return [
                'id_menu_access' => $value,
                'id_menu_template' => $idMenuTemplate
            ];
        }, $authorization);

        DB::table('skeleton_setting_template_access')->insert($dataInsert);
    }

    public function Edit(Request $request)
    {
        if(!$this->acl($this->controllerPath)){abort(401);}
        $id = $request->id;
        
            $data['results'] = DB::table($this->mainTable)->where('id','=',$id)->first(); //editable
            // !$data['results'] ? abort(404) : true;
            
            $menu = new GenerateMenuSidebar();
            $selectedAccess = DB::table('skeleton_setting_template_access')->where('id_menu_template',$id)->get();
            // dd($selectedAccess);
            $data['acl'] = $menu->acl_tree($selectedAccess);
            $data['type'] = "edit";

            //add another datas for edit view
            return view($this->viewFolder.'.form', $data);
        try {
            $id = $request->id;
            $data['results'] = DB::table($this->mainTable)->where('id','=',$id)->first(); //editable
            // !$data['results'] ? abort(404) : true;
            $menu = new GenerateMenuSidebar();
            $selectedAccess = DB::table('skeleton_setting_template_access')->where('id_menu_template',$id)->get();
            $data['acl'] = $menu->acl_tree($selectedAccess);
            $data['type'] = "edit";

            //add another datas for edit view
            return view($this->viewFolder.'.form', $data);
        } catch (Exception $e) {
            // return redirect($this->controllerPath);
            dd($e->getMessage());
        }
    }

    public function Update(Request $request){
        if(!$request->ajax() || !$this->acl($this->controllerPath)){abort(401);}
        $id = $request->post('id_reference');
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:'.$this->mainTable.',name,'.$id.',id',
            'authorization' => 'required|array',
            'authorization.*' => 'required|exists:skeleton_setting_menu_access,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        try {
            $data_update = array(
                'name' =>$request->input('name'),
                'id_user_update'    => Auth::user()->id,
            );
            DB::beginTransaction();
            DB::table($this->mainTable)->where('id',$id)
            // ->where('id_office',1) //dev
            ->update($data_update);

            //insert access
            DB::table('skeleton_setting_template_access')->where('id_menu_template',$id)->delete();
            $this->insertAccess($id,$request->input('authorization'));

            //log data update
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            //log jika error
            $this->logger->error($e);
            return $this->Return_response('Error during update'.$e->getMessage(),'response/error_reload_js');
        }
        //log kalau sukses
        //return $this->Return_response('Update Successfull','response/success_reload_full',$this->controller_path.'/detail/'.$id);
        return $this->Return_response('Update Successfull','response/success_reload_full',$request->session()->get($this->controllerPath));
    }

}
