<?php

namespace App\Http\Controllers\setting\UsersProfile;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use App\Http\Controllers\Codeton;
use App\Helpers\Logger;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\setting\UsersProfile\UsersProfileListView as DataList;

/**
 * Class UsersProfileController
 * 
 * Handles user profile management operations
 */
class UsersProfileController extends Codeton
{
    /**
     * View folder path
     *
     * @var string
     */
    protected string $viewFolder = 'setting/UsersProfile';

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
    protected string $controllerPath = 'usersProfile';

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
     * Show create/edit form
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        if (!$this->acl($this->controllerPath)) {
            abort(401);
        }

        $data['results'] = DB::table($this->mainTable)
            ->where('id', Auth::id())
            ->first();
            
        $data['backlink'] = '/';
        $data['type'] = 'edit';
        
        return view($this->viewFolder . '.form', $data);
    }

    /**
     * Update user profile
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        if (!$request->ajax() || !$this->acl($this->controllerPath)) {
            abort(401);
        }

        Validator::extend('without_spaces', function ($attr, $value) {
            return preg_match('/^\S*$/u', $value);
        });

        $validationRules = [
            'img_path' => 'nullable'
        ];

        if ($request->password) {
            $validationRules['password'] = [
                'nullable',
                'min:8',
                'without_spaces',
                'max:30',
                'regex:/^[^\s]+$/',
                function ($attribute, $value, $fail) {
                    if (trim($value) === '') {
                        $fail('Password should not consist of spaces only');
                    }
                }
            ];
        }

        $validator = Validator::make($request->all(), $validationRules);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        
        try {
            $affectedRows = null;
            
            if ($request->password !== null) {
                $dataUpdate = [
                    'password' => Hash::make($request->password)
                ];
                
                $affectedRows = DB::table($this->mainTable)
                    ->where('id', Auth::id())
                    ->update($dataUpdate);
            }

            return $this->returnResponse(
                $affectedRows ? 'Update Successful' : 'Nothing to update',
                'response/success_reload_full',
                $request->session()->get($this->controllerPath)
            );
        } catch (Exception $e) {
            $this->logger->error($e);
            return $this->returnResponse(
                'Error during update: ' . $e->getMessage(),
                'response/error_reload_js'
            );
        }
    }

    /**
     * Handle file upload
     *
     * @param Request $request
     * @param array $data
     * @param string $fileName
     * @return array
     */
    private function checkFileUpload(Request $request, array $data, string $fileName): array
    {
        if ($request->hasFile('img_path')) {
            $userAuth = DB::table($this->mainTable)
                ->where('id', Auth::id())
                ->first();

            if (File::exists($userAuth->img_path)) {
                File::delete($userAuth->img_path);
            }

            $imageName = time() . '-user-profile.' . $request->img_path->extension();
            $uploadPath = 'storage/avatar/' . $fileName;
            
            $request->img_path->move(public_path($uploadPath), $imageName);
            $data['img_path'] = $uploadPath . '/' . $imageName;
        }

        return $data;
    }
}