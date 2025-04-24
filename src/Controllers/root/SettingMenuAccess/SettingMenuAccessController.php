<?php

namespace App\Http\Controllers\root\SettingMenuAccess;

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
use App\Http\Controllers\root\SettingMenuAccess\SettingMenuAccessListView as DataList;

/**
 * Class SettingMenuAccessController
 * 
 * Handles CRUD operations for menu access settings
 */
class SettingMenuAccessController extends Codeton
{
    /**
     * View folder path
     * 
     * @var string
     */
    protected string $viewFolder = 'root/SettingMenuAccess';
    
    /**
     * Main database table
     * 
     * @var string
     */
    protected string $mainTable = 'skeleton_setting_menu_access';
    
    /**
     * Controller path
     * 
     * @var string
     */
    protected string $controllerPath = 'settingMenuAccess';
    
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
     * @return void
     */
    public function __construct(Request $request, Logger $logger)
    {
        $this->logger = $logger;
        View::share([
            'controller_path' => $this->controllerPath,
            'parents' => DB::table($this->mainTable)
                ->where('status', 1)
                ->where('type', 1)
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
        // Access control check can be uncommented when ready
        // if (!$this->acl($this->controllerPath)) {
        //     abort(401);
        // }
        
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
        // Access control check can be uncommented when ready
        // if (!$request->ajax() || !$this->acl($this->controllerPath)) {
        //     abort(401);
        // }
        
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
        // Access control check can be uncommented when ready
        // if (!$request->ajax() || !$this->acl($this->controllerPath)) {
        //     abort(401);
        // }
        
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
        // Access control check can be uncommented when ready
        // if (!$request->ajax() || !$this->acl($this->controllerPath)) {
        //     abort(401);
        // }
        
        $data['type'] = 'create';
        return view($this->viewFolder . '.form', $data);
    }

    /**
     * Store a new record
     *
     * @param Request $request
     * @return Response|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Access control check can be uncommented when ready
        // if (!$request->ajax() || !$this->acl($this->controllerPath)) {
        //     abort(401);
        // }
        
        $validator = Validator::make($request->all(), [
            // Add validation rules here as needed
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        
        try {
            $uuid = Str::uuid();
            
            // Transaction can be uncommented when ready
            // DB::beginTransaction();
            
            $dataInsert = [
                'id_parent' => $request->input('parent'),
                'menu_order' => $request->input('order'),
                'name' => $request->input('name'),
                'type' => $request->input('type'),
                'access' => $request->input('level'),
                'icon' => $request->input('icon'),
                'sess_name' => $request->input('sess_name'),
                'url' => $request->input('url'),
                'uuid' => $uuid,
                // Uncomment when authentication is active
                // 'id_user_insert' => Auth::user()->id,
                // 'id_user_update' => Auth::user()->id,
            ];
            
            DB::table($this->mainTable)->insert($dataInsert);
            
            // DB::commit();
        } catch (Exception $e) {
            // DB::rollback();
            $this->logger->error($e);
            return $this->returnResponse(
                'Error during insert: ' . $e->getMessage(),
                'response/error_reload_full'
            );
        }
        
        return $this->returnResponse(
            'Insert Successful',
            'response/success_reload_div',
            $request->session()->get($this->controllerPath)
        );
    }

    /**
     * Show edit form
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request)
    {
        // Access control check can be uncommented when ready
        // if (!$this->acl($this->controllerPath)) {
        //     abort(401);
        // }
        
        try {
            $id = $request->id;
            $data['results'] = DB::table($this->mainTable)
                ->where('id', $id)
                ->first();
                
            if (!$data['results']) {
                abort(404);
            }
            
            $data['type'] = "edit";
            
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
        // Access control check can be uncommented when ready
        // if (!$request->ajax() || !$this->acl($this->controllerPath)) {
        //     abort(401);
        // }
        
        $id = $request->post('id_reference');
        
        $validator = Validator::make($request->all(), [
            // Add validation rules here as needed
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        
        try {
            // Transaction can be uncommented when ready
            // DB::beginTransaction();
            
            $dataUpdate = [
                'id_parent' => $request->input('parent'),
                'menu_order' => $request->input('order'),
                'name' => $request->input('name'),
                'type' => $request->input('type'),
                'access' => $request->input('level'),
                'icon' => $request->input('icon'),
                'sess_name' => $request->input('sess_name'),
                'url' => $request->input('url'),
                // Uncomment when authentication is active
                // 'id_user_update' => Auth::user()->id,
            ];
            
            $affectedRows = DB::table($this->mainTable)
                ->where('id', $id)
                ->update($dataUpdate);
                
            // DB::commit();
        } catch (Exception $e) {
            // DB::rollback();
            $this->logger->error($e);
            return $this->returnResponse('Error during update', 'response/error_reload_js');
        }
        
        $message = $affectedRows ? 'Update Successful' : 'Nothing to update';
        
        return $this->returnResponse(
            $message,
            'response/success_reload_div',
            $request->session()->get($this->controllerPath)
        );
    }
}
