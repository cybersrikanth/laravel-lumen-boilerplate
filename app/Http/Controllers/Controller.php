<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    protected $service;
    protected $params;
    public $request;

    public function __construct(Request $request)
    {
        $this->params = $request->all();
        $this->request = $request;
    }

    public function getParams(): Request
    {
        return $this->request->replace($this->params);
    }

    public function json(array $data, int $status_code = 200): JsonResponse
    {
        return response()->json($data, $status_code);
    }
}
