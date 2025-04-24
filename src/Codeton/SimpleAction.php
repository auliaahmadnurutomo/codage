<?php

namespace App\Codeton;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Trait SimpleAction
 * 
 * Provides simple CRUD action functionality
 */
trait SimpleAction
{
    /**
     * Toggle action with database update
     *
     * @param Request $request The HTTP request
     * @param array $dataUpdate Data to update
     * @return Response HTTP response
     */
    public function simpleActionToggle(Request $request, array $dataUpdate): Response
    {
        $reloadPath = $request->session()->get($this->controller_path);
        $viewResponse = 'response/success_reload_div';
        $message = 'Success Update';
        
        try {
            if (!$this->query->update($dataUpdate)) {
                $message = 'Data not modified';
            }
        } catch (Exception $exception) {
            $message = 'Error during update, please reload page';
            $viewResponse = 'response/error_reload_full';
        }
        
        $path = $reloadPath ?: $this->controller_path;
        return $this->returnResponse($message, $viewResponse, $path);
    }

    /**
     * Delete action
     *
     * @param Request $request The HTTP request
     * @return Response HTTP response
     */
    public function simpleActionDelete(Request $request): Response
    {
        $reloadPath = $request->session()->get($this->controller_path);
        $viewResponse = 'response/success_reload_div';
        $message = 'Success Delete Data';
        
        try {
            if (!$this->query->delete()) {
                $message = 'Failed delete data';
                $viewResponse = 'response/error_reload_full';
            }
        } catch (Exception $exception) {
            $message = 'Failed delete data, invalid code';
            $viewResponse = 'response/error_reload_full';
        }
        
        $path = $reloadPath ?: $this->controller_path;
        return $this->returnResponse($message, $viewResponse, $path);
    }

    /**
     * Delete and reload with JavaScript
     *
     * @param Request $request The HTTP request
     * @return Response HTTP response
     */
    public function deleteThenReloadJs(Request $request): Response
    {
        $viewResponse = 'response/success_reload_js';
        $message = 'Success Delete Data';
        
        try {
            if (!$this->query->delete()) {
                $message = 'Failed delete data';
            }
        } catch (Exception $exception) {
            $message = 'Failed delete data, invalid code';
            $viewResponse = 'response/error_reload_js';
        }
        
        return $this->returnResponse($message, $viewResponse);
    }
}