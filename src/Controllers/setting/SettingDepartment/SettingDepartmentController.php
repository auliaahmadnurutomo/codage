<?php

namespace App\Http\Controllers\setting\SettingDepartment;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use App\Http\Controllers\Codeton;
use App\Helpers\Logger;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\setting\SettingDepartment\SettingDepartmentListView as DataList;

/**
 * Class SettingDepartmentController
 * 
 * Handles department settings management
 */
class SettingDepartmentController extends Codeton
{
    /**
     * View folder path
     *
     * @var string
     */
    protected string $viewFolder = 'setting/SettingDepartment';

    /**
     * Main database table
     *
     * @var string
     */
    protected string $mainTable = 'skeleton_setting_department';

    /**
     * Controller path
     *
     * @var string
     */
    protected string $controllerPath = 'settingDepartment';

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

        $this->query = DB::table($this->mainTable)->where('id', $id);
        return $this->simpleActionDelete($request);
    }

    /**
     * Show create form
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        if (!$request->ajax() || !$this->acl($this->controllerPath)) {
            abort(401);
        }

        return view($this->viewFolder . '.form', ['type' => 'create']);
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
            'name' => 'required|max:255|min:1',
            'code' => 'nullable|max:255|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            $dataInsert = [
                'id_office' => 1,
                'name' => $request->input('name'),
                'code' => $request->input('code'),
                'id_user_insert' => Auth::id(),
            ];

            DB::table($this->mainTable)->insert($dataInsert);

            return $this->returnResponse(
                'Insert Successful',
                'response/success_reload_div',
                $request->session()->get($this->controllerPath)
            );
        } catch (Exception $e) {
            $this->logger->error($e);
            return $this->returnResponse(
                'Error during insert: ' . $e->getMessage(),
                'response/error_reload_full'
            );
        }
    }

    /**
     * Show edit form
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request)
    {
        if (!$this->acl($this->controllerPath)) {
            abort(401);
        }

        try {
            $data['results'] = DB::table($this->mainTable)
                ->where('id', $request->id)
                ->first();

            if (!$data['results']) {
                abort(404);
            }

            $data['type'] = 'edit';
            return view($this->viewFolder . '.form', $data);
        } catch (Exception $e) {
            $this->logger->error($e);
            return redirect($this->controllerPath);
        }
    }

    /**
     * Update an existing record
     *
     * @param Request $request
     * @return Response|\Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        if (!$request->ajax() || !$this->acl($this->controllerPath)) {
            abort(401);
        }

        $id = $request->post('id_reference');
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|min:1',
            'code' => 'nullable|max:255|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        try {
            $dataUpdate = [
                'name' => $request->input('name'),
                'code' => $request->input('code'),
            ];

            $affectedRows = DB::table($this->mainTable)
                ->where('id', $id)
                ->update($dataUpdate);

            return $this->returnResponse(
                $affectedRows ? 'Update Successful' : 'Nothing to update',
                'response/success_reload_div',
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
}
