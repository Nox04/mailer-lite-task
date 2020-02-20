<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FieldRequest;
use App\Http\Resources\FieldResource;
use App\Models\Field;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Class FieldController
 * @package App\Http\Controllers
 */
class FieldController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        return FieldResource::collection(Field::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param FieldRequest $request
     * @return FieldResource
     * @throws Exception
     */
    public function store(FieldRequest $request)
    {
        $field = new Field($request->validated());
        if (!$field->save()) {
            throw new Exception('Could not create the subscriber.');
        }
        return new FieldResource($field);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Field $field
     * @return FieldResource
     */
    public function show(Request $request, Field $field)
    {
        return new FieldResource($field);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param FieldRequest $request
     * @param Field $field
     * @return FieldResource
     * @throws Exception
     */
    public function update(FieldRequest $request, Field $field)
    {
        $field = $field->fill($request->validated());

        if ($field->save()) {
            return new FieldResource($field);
        } else {
            throw new Exception('Could not update the field.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Field $field
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Field $field)
    {
        if ($field->delete()) {
            return response()->json(['success' => true]);
        } else {
            throw new Exception('Could not delete the field.');
        }
    }
}
