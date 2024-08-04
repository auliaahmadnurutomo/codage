<?php

namespace App\Http\Controllers\setting\SettingUsers;

use DB;
use Auth;
use View;
use Exception;
use Validator;
use App\Helpers\Logger;
use App\Helpers\Utility;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Codeton;
use App\Http\Controllers\setting\SettingUsers\SettingUsersListView as DataList;

class SettingUsersController extends Codeton
{
    protected $view_folder = 'setting/SettingUsers'; //editable, lokasi folder view
    protected $main_table = 'users'; //optional
    protected $controller_path = 'settingUsers';
    private $id;
    protected $logger;

    public function __construct(Request $request, Logger $logger)
    {
        $this->logger = $logger;
        View::share([
            'controller_path' => $this->controller_path,
            'workshop' => DB::table('setting_workshop')->get(),
            'service_truck' => DB::table('service_truck')->get(),
            'department' => DB::table('skeleton_setting_department')->get(),
            'roles' => DB::table('skeleton_setting_menu_template')->get()
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
        DB::table('users_workshop')->where('id_user', $id)->delete();
        return $this->Simple_action_delete($request); //editable table
    }

    public function Create(Request $request)
    {
        if (!$this->acl($this->controller_path)) {
            abort(401);
        }
        $data['type'] = 'create';
        $data['workshop'] = DB::table('setting_workshop')->get();
        $data['users_workshop'] = DB::table('users_workshop')->get();
        $data['department']    = DB::table('skeleton_setting_department')->where('status', 1)->get();
        $data['staff_position']    = DB::table('skeleton_setting_position')->where('status', 1)->get();
        $data['detail_office']     = DB::table('office_detail')->select('id', 'name')->where('status', 1)->get();
        return view($this->view_folder . '.form', $data);
    }

    public function Store(Request $request)
    {
        if (!$request->ajax() || !$this->acl($this->controller_path)) {
            abort(401);
        }

        Validator::extend('without_spaces', function ($attr, $value) {
            return preg_match('/^\S*$/u', $value);
        });

        $validator = Validator::make($request->all(), [
            //validasi input store disini
            'name' => 'required',
            'id_department' => 'required|exists:skeleton_setting_department,id',
            'id_staff_position' => 'required|exists:skeleton_setting_position,id',
            // 'id_service_truck' => 'nullable|exists:service_truck,id',
            'email' => 'required|min:5|unique:users,email',
            'password' => 'required|min:8|without_spaces|max:30',
            // 'user_workshop' => 'required',
            'id_office' => 'required|exists:setting_workshop,id',
            'role' => 'required|exists:skeleton_setting_menu_template,id'
        ]);
        $validator->after(function ($validator) use ($request) {
            if ($this->isDataExist('create', $request->input('email'), $request->input('id_office'), null)) {
                $validator->errors()->add('errors', 'Data Sudah Ada!');
            }
        });
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        try {
            $data_insert = array(
                'name'                  => $request->input('name'),
                'email'                 => $request->input('email'),
                'password'              => bcrypt($request->input('password')),
                'id_department'         => $request->input('id_department'),
                'id_staff_position'     => $request->input('id_staff_position'),
                'id_service_truck'      => is_numeric($request->input('id_service_truck')) ? $request->input('id_service_truck') : null,
                'id_office'             => $request->input('id_office'),
                'created_at'            => now(),
                'updated_at'            => now(),
            );
            if($request->file){
                //store file
                $data_insert['img_path'] = Utility::uploadFile('avatar',$request->file);
            }
            $id_user = DB::table($this->main_table)->insertGetId($data_insert);
            if ($id_user) {
                DB::table('skeleton_users_info')->insert(
                    ['id_user' => $id_user, 
                    'id_department' => $request->input('id_department'), 
                    'id_position' => $request->input('id_staff_position'), 
                    'id_office' => $request->input('id_office'), 
                    'id_access_template' => $request->input('role'), 
                    'status' => 1]);
                // if ($request->user_workshop != null) {
                //     DB::table('users_workshop')->insert([
                //         'id_user'       => $id_user,
                //         'user_workshop'   => json_encode($request->user_workshop)
                //     ]);
                // }
            }
        } catch (Exception $e) {
            //DB::rollback();
            //log jika error
            $this->logger->error($e);
            return $this->Return_response('Error insert data'.$e->getMessage(), 'response/error_reload_full');
        }
        //log kalau sukses
        //return $this->Return_response('Insert Successfull','response/success_reload_full',$this->controller_path.'/detail/'.$id);
        return $this->Return_response('Insert Successfull', 'response/success_reload_full', $request->session()->get($this->controller_path));
    }

    private function isDataExist($action, $name, $id_office, $id = null)
    {
        if ($action == 'create') {
            // Lakukan query ke database untuk memeriksa apakah data ada di kedua kolom
            return DB::table('users')->where('name', $name)
                ->where('id_office', $id_office)
                ->exists();
        } else {
            return DB::table('users')->where('name', $name)
                ->where('id_office', $id_office)
                ->where('id', '<>', $id)
                ->exists();
        }
    }

    public function Edit(Request $request)
    {
        if(!$this->acl($this->controller_path)){abort(401);}
        try {
            $id = $request->id;
            $data['results'] = DB::table($this->main_table. ' as a')->leftJoin('skeleton_users_info as b','b.id_user','a.id')->where('a.id','=',$id)->select('a.*','b.id_access_template')->first(); //editable
            !$data['results'] ? abort(404) : true;
            // dd($data);
            $data['type'] = "edit";
            // $data['office_access'] = DB::table('user_office_access')->where('id_user', $id)->get();
            $data['users_workshop'] = DB::table('users_workshop')->where('id_user', $id)->get();
            $data['workshop'] = DB::table('setting_workshop')->get();
            $data['department']    = DB::table('skeleton_setting_department')->where('status', 1)->get();
            $data['staff_position']    = DB::table('skeleton_setting_position')->where('status', 1)->get();
            $data['detail_office']     = DB::table('office_detail')->select('id', 'name')->where('status', 1)->get();
            //add another datas for edit view
            return view($this->view_folder.'.form', $data);
        } catch (Exception $e) {
            abort(500);
        }
    }

    public function Update(Request $request){
        if(!$request->ajax() || !$this->acl($this->controller_path)){abort(401);}
        $id = $request->post('id_reference');
        Validator::extend('without_spaces', function ($attr, $value) {
            return preg_match('/^\S*$/u', $value);
        });

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'id_department' => 'required|exists:skeleton_setting_department,id',
            'id_staff_position' => 'required|exists:skeleton_setting_position,id',
            'id_office' => 'required|exists:setting_workshop,id',
            // 'id_service_truck' => 'nullable|exists:service_truck,id',
            'email' => 'required|min:5|unique:users,email,' . $id . ',id',
            'password' => ['nullable', 'min:8','without_spaces', 'max:30', 'regex:/^[^\s]+$/', function ($attribute, $value, $fail) {
                if (trim($value) == '') {
                    $fail('Password should not consist of spaces only');
                }
            }],
        ]);

        $validator->after(function ($validator) use ($request, $id) {
            if ($this->isDataExist('update', $request->input('email'), $request->input('id_office'), $id)) {
                $validator->errors()->add('errors', 'Data Sudah Ada!');
            }
        });
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        try {
            $data_insert = array(
                'name'                  => $request->input('name'),
                'email'                 => $request->input('email'),
                'id_department'         => $request->input('id_department'),
                'id_office'             => $request->input('id_office'),
                'id_staff_position'     => $request->input('id_staff_position'),
                'id_service_truck'      => is_numeric($request->input('id_service_truck')) ? $request->input('id_service_truck') : null,
                'updated_at'            => now(),
            );
            if ($request->post('password') != '') {
                $data_insert['password'] = bcrypt($request->input('password'));
            }
            if($request->file){
                //store file
                $data_insert['img_path'] = Utility::uploadFile('avatar',$request->file);
            }
            $affectedRows = DB::table($this->main_table)->where('id', $id)->update($data_insert);
            DB::table('skeleton_users_info')->where('id_user',$id)->update(
                [
                'id_department' => $request->input('id_department'), 
                'id_position' => $request->input('id_staff_position'), 
                'id_office' => $request->input('id_office'), 
                'id_access_template' => $request->input('role'), 
                'status' => 1]);

            // $table =  DB::table('users_workshop')->where('id_user', $id);
            // if ($request->user_workshop != null) {
            //     if ($table->count() == 0) {
            //         DB::table('users_workshop')->insert([
            //             'id_user'       => $id,
            //             'user_workshop'   => json_encode($request->user_workshop)
            //         ]);
            //     } else {
            //         $table->update(['user_workshop' => json_encode($request->user_workshop)]);
            //     }
            // }
        } catch (Exception $e) {
            //DB::rollback();
            //log jika error
            $this->logger->error($e);
            return $this->Return_response('Error during update','response/error_reload_js');
        }
        //log kalau sukses
        //return $this->Return_response('Update Successfull','response/success_reload_full',$this->controller_path.'/detail/'.$id);
        return $this->Return_response($affectedRows ? 'Update Successfull' : 'Nothing to update','response/success_reload_full',$request->session()->get($this->controller_path));
    }

}
