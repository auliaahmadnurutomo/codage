<?php
namespace App\Codeton;
trait ReturnResponse
{
    public  function Return_response($message,$view_response,$reload_path = '')
    {
        $data['message']=$message;
        $data['controller_path'] = $reload_path == '' ? $this->controller_path : $reload_path;
        return response()->view($view_response, $data);
    }

}