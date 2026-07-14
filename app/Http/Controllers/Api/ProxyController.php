<?php

namespace App\Http\Controllers\Api;

use App\Enums\ProxyStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProxyRequest;
use App\Http\Requests\UpdateProxyRequest;
use App\Http\Resources\ProxyResource;
use App\Models\Proxy;
use App\Services\ProxyChecker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

#[OA\Info(title: 'Proxy Manager API', version: '1.0')]
class ProxyController extends Controller
{
    #[OA\Get(
        path: '/api/proxies',
        summary: 'Список всех прокси',
        tags: ['Прокси'],
        responses: [new OA\Response(response: 200, description: 'Список прокси')],
    )]
    public function index(): AnonymousResourceCollection
    {
        return ProxyResource::collection(Proxy::latest('id')->get());
    }

    #[OA\Post(
        path: '/api/proxies',
        summary: 'Добвить прокси',
        tags: ['Прокси'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['host', 'port', 'protocol'],
                properties: [
                    new OA\Property(property: 'host', type: 'string', example: '1.2.3.4'),
                    new OA\Property(property: 'port', type: 'integer', example: 8080),
                    new OA\Property(property: 'protocol', type: 'string', enum: ['http', 'https', 'socks4', 'socks5'], example: 'http'),
                    new OA\Property(property: 'username', type: 'string', nullable: true),
                    new OA\Property(property: 'password', type: 'string', nullable: true),
                ],
            ),
        ),
        responses: [
            new OA\Response(response: 201, description: 'Прокси добавлен'),
            new OA\Response(response: 422, description: 'Ошибка валидации'),
        ],
    )]
    public function store(StoreProxyRequest $request): JsonResponse
    {
        $proxy = Proxy::create($request->validated());

        return ProxyResource::make($proxy)
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    #[OA\Put(
        path: '/api/proxies/{proxy}',
        summary: 'Изменить прокси',
        tags: ['Прокси'],
        parameters: [
            new OA\Parameter(name: 'proxy', in: 'path', required: true, description: 'ID прокси', schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['host', 'port', 'protocol'],
                properties: [
                    new OA\Property(property: 'host', type: 'string', example: '1.2.3.4'),
                    new OA\Property(property: 'port', type: 'integer', example: 8080),
                    new OA\Property(property: 'protocol', type: 'string', enum: ['http', 'https', 'socks4', 'socks5'], example: 'http'),
                    new OA\Property(property: 'username', type: 'string', nullable: true),
                    new OA\Property(property: 'password', type: 'string', nullable: true, description: 'Пустой - пароль не меняется'),
                ],
            ),
        ),
        responses: [
            new OA\Response(response: 200, description: 'Прокси изменён'),
            new OA\Response(response: 422, description: 'Ошибка валидации'),
        ],
    )]
    public function update(UpdateProxyRequest $request, Proxy $proxy): ProxyResource
    {
        $data = $request->validated();

        // если пароль пустой, то оставляем без изменений
        if (blank($data['password'] ?? null)) {
            unset($data['password']);
        }

        $proxy->fill($data);

        // реквизиты поменялись - старый статус уже ничего не значит
        if ($proxy->isDirty()) {
            $proxy->status = ProxyStatus::Unknown;
            $proxy->latency_ms = null;
            $proxy->last_checked_at = null;
        }

        $proxy->save();

        return ProxyResource::make($proxy);
    }

    #[OA\Delete(
        path: '/api/proxies/{proxy}',
        summary: 'Удалить прокси',
        tags: ['Прокси'],
        parameters: [
            new OA\Parameter(name: 'proxy', in: 'path', required: true, description: 'ID прокси', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [new OA\Response(response: 204, description: 'Прокси удалён')],
    )]
    public function destroy(Proxy $proxy): Response
    {
        $proxy->delete();

        return response()->noContent();
    }

    #[OA\Post(
        path: '/api/proxies/{proxy}/check',
        summary: 'Проверить одну прокси',
        tags: ['Прокси'],
        parameters: [
            new OA\Parameter(name: 'proxy', in: 'path', required: true, description: 'ID прокси', schema: new OA\Schema(type: 'integer')),
        ],
        responses: [new OA\Response(response: 200, description: 'Прокси проверена')],
    )]
    public function check(Proxy $proxy, ProxyChecker $checker): ProxyResource
    {
        $proxy->applyCheckResult($checker->check($proxy));

        return ProxyResource::make($proxy);
    }

    #[OA\Post(
        path: '/api/proxies/check-all',
        summary: 'Проверить все прокси',
        tags: ['Прокси'],
        responses: [new OA\Response(response: 200, description: 'Список прокси с новыми статусами')],
    )]
    public function checkAll(ProxyChecker $checker): AnonymousResourceCollection
    {
        $proxies = Proxy::all();

        foreach ($checker->checkMany($proxies) as $id => $result) {
            $proxies->find($id)->applyCheckResult($result);
        }

        return ProxyResource::collection(Proxy::latest('id')->get());
    }
}
