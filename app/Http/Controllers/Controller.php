<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Changeorg Laravel API Rest Documentation",
 *      description="Changeorg Laravel API Rest Documentation",
 *      @OA\Contact(
 *          email="ilarranaga@jesuitasformacion.com"
 *      )
 * )
 *
 * @OA\Server(
 *      url="https://laravel-changeorg-api.herokuapp.com/",
 *      description="API Server"
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}