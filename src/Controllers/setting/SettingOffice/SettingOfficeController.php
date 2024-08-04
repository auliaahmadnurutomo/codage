<?php

namespace App\Http\Controllers\setting\SettingOffice;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Codeton;
use App\Helpers\Logger;
use Exception;
use Validator;
use DB;
use Auth;
use View;
use App\Http\Controllers\setting\SettingOffice\SettingOfficeListView as DataList;

class SettingOfficeController extends Codeton
{
    protected $view_folder = 'setting/SettingOffice'; //editable, lokasi folder view
    protected $main_table = 'skeleton_setting_office'; //optional
    protected $controller_path = 'settingOffice';
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
        $this->query = DB::table($this->main_table)->where('uuid', $id);
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
        $this->query = DB::table($this->main_table)->where('uuid', $id);
        return $this->Simple_action_delete($request); //editable table
    }

    public function Create(Request $request)
    {
        if (!$request->ajax() || !$this->acl($this->controller_path)) {
            abort(401);
        }
        $data['type'] = 'create';
        return view($this->view_folder . '.form', $data);
    }

    public function Store(Request $request)
    {
        if (!$request->ajax() || !$this->acl($this->controller_path)) {
            abort(401);
        }
        $validator = Validator::make($request->all(), [
            //validasi input store disini
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        try {
            $uuid = Str::uuid();
            //DB::beginTransaction();
            $data_insert = array(
                // 'uuid'            => $uuid,
                'id_corporate' => 1, //dev
                'name'            => $request->input('name'),
                'code'            => $request->input('code'),
                'id_user_insert'  => Auth::user()->id,
                //'id_user_update'  => Auth::user()->id,
            );
            DB::table($this->main_table)->insert($data_insert);
            //log data insert
            //DB::commit();
        } catch (Exception $e) {
            //DB::rollback();
            //log jika error
            $this->logger->error($e);
            return $this->Return_response('Error during insert', 'response/error_reload_full');
        }
        //log kalau sukses
        //return $this->Return_response('Update Successfull','response/success_reload_js');
        return $this->Return_response('Insert Successfull', 'response/success_reload_div', $request->session()->get($this->controller_path));
    }


    public function Edit(Request $request)
    {
        if(!$this->acl($this->controller_path)){abort(401);}
        try {
            $id = $request->id;
            // $data['results'] = DB::table($this->main_table)->where('uuid','=',$id)->first(); //editable
            $data['results'] = DB::table($this->main_table)->where('id','=',$id)->first(); //editable
            !$data['results'] ? abort(404) : true;
            $data['type'] = "edit";
            //add another datas for edit view
            return view($this->view_folder.'.form', $data);
        } catch (Exception $e) {
            return redirect($this->controller_path);
        }
    }

    public function Update(Request $request){
        if(!$request->ajax() || !$this->acl($this->controller_path)){abort(401);}
        $id = $request->post('id_reference');
        $validator = Validator::make($request->all(), [
            //validasi input store disini
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        try {
            DB::beginTransaction();
            $data_update = array(
                'name'            => $request->input('name'),
                'code'            => $request->input('code'),
            );
            // $affectedRows = DB::table($this->main_table)->where('uuid',$id)->update($data_update);
            $affectedRows = DB::table($this->main_table)->where('id',$id)->update($data_update);
            //log data update
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            //log jika error
            $this->logger->error($e);
            return $this->Return_response('Error during update','response/error_reload_js');
        }
        //log kalau sukses
        //return $this->Return_response('Update Successfull','response/success_reload_js');
        return $this->Return_response($affectedRows ? 'Update Successfull' : 'Nothing to update','response/success_reload_div',$request->session()->get($this->controller_path));
    }
    
}
