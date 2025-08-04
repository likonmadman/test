<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Api Documentation",
 *      description="Документация для api",
 *      @OA\Contact(
 *          email="8likon8@gmail.com"
 *      )
 * )
 * @OA\Server(
 *      url="http://localhost",
 *      description="Основной API"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="apikey",
 *     type="apiKey",
 *     in="header",
 *     name="X-API-KEY"
 * )
 */
abstract class Controller
{
    //
}
