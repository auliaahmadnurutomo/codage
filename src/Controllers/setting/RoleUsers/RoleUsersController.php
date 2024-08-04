<?php

namespace App\Http\Controllers\setting\RoleUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Codeton;
use App\Helpers\Logger;
use Exception;
use Validator;
use DB;
use Auth;
use View;
use App\Codeton\GenerateMenuSidebar;
use App\Http\Controllers\setting\RoleUsers\RoleUsersListView as DataList;

class RoleUsersController extends Codeton
{
    protected $view_folder = 'setting/RoleUsers'; //editable, lokasi folder view
    protected $main_table = 'skeleton_setting_menu_template'; //optional
    protected $controller_path = 'roleUsers';
    private $id;
    protected $logger;

    public function __construct(Request $request, Logger $logger)
    {
        $this->logger = $logger;
        View::share([
            'controller_path' => $this->controller_path,
        ]);
    }

    public function PageIndex(Request $request)
    {
        if (!$this->acl($this->controller_path)) {
            abort(401);
        }
        $list = new DataList(request: $request);
        return $list->TableView();
    }

    public function Activation(Request $request)
    {
        if (!$request->ajax() || !$this->acl($this->controller_path)) {
            abort(401);
        }
        $status = ($request->get('data3')) ? 0 : 1;
        $data_update = array('status' => $status);
        $id = $request->get('data2');
        if (!$id) {
            return $this->Return_response('Payload error', 'response/error_reload_full');
        }
        $this->query = DB::table($this->main_table)->where('id', $id);
        return $this->Simple_action_toggle($request, $data_update); //editable table
    }

    public function Delete(Request $request)
    {
        if (!$request->ajax() || !$this->acl($this->controller_path)) {
            abort(401);
        }
        $id = $request->get('data2');
        if (!$id) {
            return $this->Return_response('Payload error', 'response/error_reload_full');
        }
        $this->query = DB::table($this->main_table)->where('id', $id);
        DB::table('skeleton_setting_template_access')->where('id_menu_template',$id)->delete();
        return $this->Simple_action_delete($request); //editable table
    }

    public function Create(Request $request)
    {
        if (!$this->acl($this->controller_path)) {
            abort(401);
        }
        $data['type'] = 'create';
        $menu = new GenerateMenuSidebar();
        $data['acl'] = $menu->acl_tree();
        return view($this->view_folder . '.form', $data);
    }

    public function Store(Request $request)
    {
        if (!$request->ajax() || !$this->acl($this->controller_path)) {
            abort(401);
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:'.$this->main_table.',name',
            'authorization' => 'required|array',
            'authorization.*' => 'required|exists:skeleton_setting_menu_access,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        try {

            $data_insert = array(
                'name'              => $request->name,
                'id_user_insert'    => Auth::user()->id,
                'id_user_update'    => Auth::user()->id,
                'id_office'           => 1
            );
            DB::beginTransaction();
            $idRole = DB::table($this->main_table)->insertGetId($data_insert);
            //insert access
            DB::table('skeleton_setting_template_access')->where('id_menu_template',$idRole)->delete();
            $this->insertAccess($idRole,$request->input('authorization'));
            //log data insert
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            //log jika error
            $this->logger->error($e);
            return $this->Return_response('Error insert data'. $e->getMessage(), 'response/error_reload_full');
        }
        //log kalau sukses
        //return $this->Return_response('Insert Successfull','response/success_reload_full',$this->controller_path.'/detail/'.$uuid);
        return $this->Return_response('Insert Successfull', 'response/success_reload_full', $request->session()->get($this->controller_path));
    }

    private function insertAccess($id_menu_template,$authorization){
        $data_insert = [];
        foreach ($authorization as $key => $value) {
            $data_insert[]=[
                'id_menu_access' => $value,
                'id_menu_template' =>$id_menu_template
            ];
        }
        DB::table('skeleton_setting_template_access')->insert($data_insert);
    }

    public function Edit(Request $request)
    {
        if(!$this->acl($this->controller_path)){abort(401);}
        $id = $request->id;
        
            $data['results'] = DB::table($this->main_table)->where('id','=',$id)->first(); //editable
            // !$data['results'] ? abort(404) : true;
            
            $menu = new GenerateMenuSidebar();
            $selectedAccess = DB::table('skeleton_setting_template_access')->where('id_menu_template',$id)->get();
            // dd($selectedAccess);
            $data['acl'] = $menu->acl_tree($selectedAccess);
            $data['type'] = "edit";

            //add another datas for edit view
            return view($this->view_folder.'.form', $data);
        try {
            $id = $request->id;
            $data['results'] = DB::table($this->main_table)->where('id','=',$id)->first(); //editable
            // !$data['results'] ? abort(404) : true;
            $menu = new GenerateMenuSidebar();
            $selectedAccess = DB::table('skeleton_setting_template_access')->where('id_menu_template',$id)->get();
            $data['acl'] = $menu->acl_tree($selectedAccess);
            $data['type'] = "edit";

            //add another datas for edit view
            return view($this->view_folder.'.form', $data);
        } catch (Exception $e) {
            // return redirect($this->controller_path);
            dd($e->getMessage());
        }
    }

    public function Update(Request $request){
        if(!$request->ajax() || !$this->acl($this->controller_path)){abort(401);}
        $id = $request->post('id_reference');
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:'.$this->main_table.',name,'.$id.',id',
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
            DB::table($this->main_table)->where('id',$id)
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
        return $this->Return_response('Update Successfull','response/success_reload_full',$request->session()->get($this->controller_path));
    }

}
