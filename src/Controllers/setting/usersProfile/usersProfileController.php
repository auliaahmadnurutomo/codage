<?php

namespace App\Http\Controllers\setting\usersProfile;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Codeton;
use App\Helpers\Logger;
use Exception;
use Validator;
use DB;
use Auth;
use View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\setting\usersProfile\usersProfileListView as DataList;

class usersProfileController extends Codeton
{
    protected $view_folder = 'setting/usersProfile'; //editable, lokasi folder view
    protected $main_table = 'users'; //optional
    protected $controller_path = 'usersProfile';
    private $id;
    protected $logger;

    public function __construct(Request $request, Logger $logger)
    {
        $this->logger = $logger;
        View::share([
            'controller_path' => $this->controller_path,
        ]);
    }

    public function Create(Request $request)
    {
        // if (!$this->acl($this->controller_path)) {
        //     abort(401);
        // }
        $data['results'] = DB::table($this->main_table)
            ->where('id', Auth::user()->id)
            ->first();
        $data['office'] = DB::table('skeleton_setting_office')->get();
        $data['backlink'] = '/';
        $data['department'] = DB::table('skeleton_setting_department')->get();
        $data['staff_position'] = DB::table('skeleton_setting_position')->get();
        $data['type'] = 'edit';
        return view($this->view_folder . '.form', $data);
    }

    public function Update(Request $request){
        // if(!$request->ajax() || !$this->acl($this->controller_path)){abort(401);}
        Validator::extend('without_spaces', function ($attr, $value) {
            return preg_match('/^\S*$/u', $value);
        });

        $data_validation = [
            'img_path'  => 'nullable'
        ];

        if($request->password) {
            $data_validation['password'] = ['nullable', 'min:8','without_spaces', 'max:30', 'regex:/^[^\s]+$/', function ($attribute, $value, $fail) {
                if (trim($value) == '') {
                    $fail('Password should not consist of spaces only');
                }
            }];
        }

        $validator = Validator::make($request->all(), $data_validation);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        
        try {
            $affectedRows = null;
            if($request->file('img_path')) {
                $data_update = array(
                    'img_path'  => $request->img_path
                );
                $fileName = 'user_profile';
                $data_update = $this->checkFileUpload($request, $data_update, $fileName);
                $affectedRows = DB::table($this->main_table)->where('id', Auth::user()->id)->update($data_update);
            }
            if($request->password != null) {
                $data_update = array(
                    'password'  => Hash::make($request->password)
                );
                $affectedRows = DB::table($this->main_table)->where('id', Auth::user()->id)->update($data_update);
            }
            // dd($data_update);
        } catch (Exception $e) {
            //log jika error
            $this->logger->error($e);
            return $this->Return_response('Error during update : '. $e->getMessage(),'response/error_reload_js');
        }
        //log kalau sukses
        //return $this->Return_response('Update Successfull','response/success_reload_full',$this->controller_path.'/detail/'.$id);
        return $this->Return_response($affectedRows ? 'Update Successfull' : 'Nothing to update','response/success_reload_full',$request->session()->get($this->controller_path));
    }

    private function checkFileUpload($request, $data, $fileName)
    {
        if ($request->file('img_path')) {
            $userAuth = DB::table($this->main_table)->where('id', Auth::user()->id)->first();
            if (File::exists($userAuth->img_path)) {
                File::delete($userAuth->img_path);
            }
            $imageName = time() . '-user-profile.' . $request->img_path->extension();
            $uploadedImage = $request->img_path->move(public_path('storage/' . $fileName . '/'), $imageName);
            $imagePath = 'storage/' . $fileName . '/' . $imageName;

            $data['img_path'] = $imagePath;
        }

        return $data;
    }

}
