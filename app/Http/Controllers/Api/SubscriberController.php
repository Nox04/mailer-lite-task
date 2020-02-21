<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriberRequest;
use App\Http\Resources\SubscriberResource;
use App\Models\Subscriber;
use Arr;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Class SubscribersController
 * @package App\Http\Controllers
 */
class SubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $state = $request->input('state');
        $sorting = json_decode($request->input('sorting'));
        $search = $request->input('search');

        $subscribers = Subscriber::with('fields');

        if ($state) {
            $subscribers->state($state);
        }

        if ($search) {
            $subscribers = $subscribers->search($search);
        }

        //$subscribers->sortResponse($sorting);

        return SubscriberResource::collection($subscribers->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SubscriberRequest $request
     * @return SubscriberResource
     * @throws Exception
     */
    public function store(SubscriberRequest $request): SubscriberResource
    {
        $data = $request->all();
        $requestFields = Arr::get($data, 'fields', []);
        unset($data['fields']);

        $subscriber = new Subscriber($data);
        $subscriber->setDefaultState();

        if (!$subscriber->save()) {
            throw new Exception('Could not create the subscriber.');
        }

        foreach ($requestFields as $key => $value) {
            if ($value) {
                $subscriber->fields()->attach($key, ['value' => $value]);
            }
        }

        $subscriber->load('fields');

        return new SubscriberResource($subscriber);
    }

    /**
     * Display the specified resource.
     *
     * @param Subscriber $subscriber
     *
     * @return SubscriberResource
     */
    public function show(Subscriber $subscriber): SubscriberResource
    {
        $subscriber->load('fields');

        return new SubscriberResource($subscriber);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SubscriberRequest $request
     * @param Subscriber $subscriber
     *
     * @return SubscriberResource
     * @throws Exception
     */
    public function update(SubscriberRequest $request, Subscriber $subscriber): SubscriberResource
    {
        $data = $request->all();
        $subscriber->fill($request->only(['name', 'email']));

        if (!$subscriber->save()) {
            throw new Exception('Could not update the subscriber.');
        }
        $fields = [];
        foreach ($data['fields'] as $key => $value) {
            $fields[$key] = ['value' => $value];
        }

        $subscriber->fields()->sync($fields);

        $subscriber->load('fields');

        return new SubscriberResource($subscriber);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Subscriber $subscriber
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Subscriber $subscriber): JsonResponse
    {
        if ($subscriber->delete()) {
            return response()->json(['success' => true]);
        } else {
            throw new Exception('Could not delete the subscriber.');
        }
    }
}
