<?php
namespace App\Codeton;

use Exception;

trait SimpleAction
{
    public function Simple_action_toggle($request, $data_update)
    {
        $reload_path = $request->session()->get($this->controller_path);
        $view_response = 'response/success_reload_div';
        $message = 'Success Update';
        try {
            if (!$this->query->update($data_update)) {
                $message = 'Data not modified';
            }
        } catch (Exception $ex) {
            $message = 'Error during update, please reload page';
            $view_response = 'response/error_reload_full';
        }
        $path = $reload_path == '' ? $this->controller_path : $reload_path;
        return $this->Return_response($message, $view_response, $path);
    }

    public function Simple_action_delete($request)
    {
        $reload_path = $request->session()->get($this->controller_path);
        $view_response = 'response/success_reload_div';
        $message = 'Success Delete Data';
        try {
            if (!$this->query->delete()) {
                $message = 'Failed delete data';
                $view_response = 'response/error_reload_full';
            }
        } catch (Exception $ex) {
            $message = 'Failed delete data, invalid code';
            $view_response = 'response/error_reload_full';
        }
        $path = $reload_path == '' ? $this->controller_path : $reload_path;
        return $this->Return_response($message, $view_response, $path);
    }

    public function DeleteThenReloadJs($request)
    {
        $view_response = 'response/success_reload_js';
        $message = 'Success Delete Data';
        try {
            if (!$this->query->delete()) {
                $message = 'Failed delete data';
            }
        } catch (Exception $ex) {
            $message = 'Failed delete data, invalid code';
            $view_response = 'response/error_reload_js';
        }

        $data['message'] = $message;
        return $this->Return_response($message, $view_response);
    }

}