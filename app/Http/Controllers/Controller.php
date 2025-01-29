<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         version="1.0",
 *         title="Agpos Api",
 *         description="Agpos Assesment Api",
 *     )
 * )
*/
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
