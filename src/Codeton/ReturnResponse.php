<?php

namespace App\Codeton;

/**
 * Trait ReturnResponse
 * 
 * Provides standardized response handling functionality
 */
trait ReturnResponse
{
    /**
     * Return a formatted response
     *
     * @param string $message Response message
     * @param string $viewResponse View template to use for response
     * @param string $reloadPath Optional path to reload (defaults to controller path)
     * @return \Illuminate\Http\Response The HTTP response
     */
    public function returnResponse(string $message, string $viewResponse, string $reloadPath = ''): \Illuminate\Http\Response
    {
        $data = [
            'message' => $message,
            'controller_path' => $reloadPath ?: $this->controller_path
        ];
        
        return response()->view($viewResponse, $data);
    }
}