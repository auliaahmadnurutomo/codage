<?php

namespace App\Http\Controllers\setting\SettingUsers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Exception;
use App\Helpers\Logger;
use App\Helpers\Utility;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Codeton;
use App\Http\Controllers\setting\SettingUsers\SettingUsersListView as DataList;

/**
 * Class SettingUsersController
 * 
 * Handles user management operations
 */
class SettingUsersController extends Codeton
{
    /**
     * View folder path
     *
     * @var string
     */
    protected string $viewFolder = 'setting/SettingUsers';

    /**
     * Main database table
     *
     * @var string
     */
    protected string $mainTable = 'users';

    /**
     * Controller path
     *
     * @var string
     */
    protected string $controllerPath = 'settingUsers';

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
            'offices' => DB::table('skeleton_setting_office')
                ->where('status', 1)
                ->get(),
            'staff_position' => DB::table('skeleton_setting_position')
                ->where('status', 1)
                ->get(),
            'department' => DB::table('skeleton_setting_department')
                ->where('status', 1)
                ->get(),
            'roles' => DB::table('skeleton_setting_menu_template')
                ->where('status', 1)
                ->get()
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

        $this->query = DB::table('skeleton_users_info')->where('id', $id);
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

        try {
            DB::beginTransaction();
            
            DB::table('users_workshop')->where('id_user', $id)->delete();
            $this->query = DB::table($this->mainTable)->where('id', $id);
            $result = $this->simpleActionDelete($request);
            
            DB::commit();
            return $result;
        } catch (Exception $e) {
            DB::rollback();
            $this->logger->error($e);
            return $this->returnResponse(
                'Delete failed: ' . $e->getMessage(),
                'response/error_reload_full'
            );
        }
    }

    public function Create(Request $request)
    {
        if (!$this->acl($this->controllerPath)) {
            abort(401);
        }
        $data['type'] = 'create';
        return view($this->viewFolder . '.form', $data);
    }

    public function Store(Request $request)
    {
        if (!$request->ajax() || !$this->acl($this->controllerPath)) {
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
            'id_office' => 'required|exists:skeleton_setting_office,id',
            'email' => 'required|min:5|unique:users,email',
            'password' => 'required|min:8|without_spaces|max:30',
            'role' => 'required|exists:skeleton_setting_menu_template,id'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        try {
            $data_insert = array(
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'created_at' => now(),
                'updated_at' => now(),
            );
            if ($request->file) {
                //store file
                $data_insert['img_path'] = Utility::uploadFile('avatar', $request->file);
            }
            DB::beginTransaction();
            $id_user = DB::table($this->mainTable)->insertGetId($data_insert);
            DB::table('skeleton_users_info')->insert(
                [
                    'id_user' => $id_user,
                    'id_department' => $request->input('id_department'),
                    'id_position' => $request->input('id_staff_position'),
                    'id_office' => $request->input('id_office'),
                    'id_access_template' => $request->input('role'),
                    'status' => 1
                ]
            );
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            //log jika error
            $this->logger->error($e);
            return $this->returnResponse('Error insert data' . $e->getMessage(), 'response/error_reload_full');
        }
        //log kalau sukses
        //return $this->returnResponse('Insert Successfull','response/success_reload_full',$this->controllerPath.'/detail/'.$id);
        return $this->returnResponse('Insert Successfull', 'response/success_reload_full', $request->session()->get($this->controllerPath));
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
        if (!$this->acl($this->controllerPath)) {
            abort(401);
        }
        $id = $request->id;
        $data['results'] = DB::table($this->mainTable . ' as a')->leftJoin('skeleton_users_info as b', 'b.id_user', 'a.id')->where('a.id', '=', $id)->select('a.*', 'b.id_access_template')->first(); //editable
        !$data['results'] ? abort(404) : true;
        // dd($data);
        $data['type'] = "edit";
        // $data['office_access'] = DB::table('user_office_access')->where('id_user', $id)->get();
        $data['department'] = DB::table('skeleton_setting_department')->where('status', 1)->get();
        $data['staff_position'] = DB::table('skeleton_setting_position')->where('status', 1)->get();
        $data['offices'] = DB::table('skeleton_setting_office')->select('id', 'name')->where('status', 1)->get();
        //add another datas for edit view
        return view($this->viewFolder . '.form', $data);
    }

    public function Update(Request $request)
    {
        if (!$request->ajax() || !$this->acl($this->controllerPath)) {
            abort(401);
        }
        $id = $request->post('id_reference');
        Validator::extend('without_spaces', function ($attr, $value) {
            return preg_match('/^\S*$/u', $value);
        });

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            // 'email' => 'required|unique:users,email,'.$id.',id',
            'id_department' => 'required|exists:skeleton_setting_department,id',
            'id_staff_position' => 'required|exists:skeleton_setting_position,id',
            'id_office' => 'required|exists:skeleton_setting_office,id',
            // 'id_service_truck' => 'nullable|exists:service_truck,id',
            'email' => 'required|min:5|unique:users,email,' . $id . ',id',
            'password' => [
                'nullable',
                'min:8',
                'without_spaces',
                'max:30',
                'regex:/^[^\s]+$/',
                function ($attribute, $value, $fail) {
                    if (trim($value) == '') {
                        $fail('Password should not consist of spaces only');
                    }
                }
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        try {
            $data_insert = array(
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'updated_at' => now(),
            );
            if ($request->post('password') != '') {
                $data_insert['password'] = bcrypt($request->input('password'));
            }
            if ($request->file) {
                //store file
                $data_insert['img_path'] = Utility::uploadFile('avatar', $request->file);
            }
            $affectedRows = DB::table($this->mainTable)->where('id', $id)->update($data_insert);
            DB::table('skeleton_users_info')->where('id_user', $id)->update(
                [
                    'id_department' => $request->input('id_department'),
                    'id_position' => $request->input('id_staff_position'),
                    'id_office' => $request->input('id_office'),
                    'id_access_template' => $request->input('role'),
                    'status' => 1
                ]
            );

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
            return $this->returnResponse('Error during update '.$e, 'response/error_reload_js');
        }
        //log kalau sukses
        //return $this->returnResponse('Update Successfull','response/success_reload_full',$this->controllerPath.'/detail/'.$id);
        return $this->returnResponse($affectedRows ? 'Update Successfull' : 'Nothing to update', 'response/success_reload_full', $request->session()->get($this->controllerPath));
    }

}
