<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Services\UserService;
use App\Http\Resources\User\UserListCollection;
use App\Http\Resources\User\UserDetailResource;
use App\Http\Requests\API\User\CreateUserRequest;
use App\Http\Requests\API\User\UpdateUserRequest;

class UserController extends Controller
{
    protected $service;

    public function __construct( UserService $service )
    {
        $this->service = $service;
    }
    /**
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $response = $this->service->list($request);
            return $this->successResp('Successfully Get List', new UserListCollection($response));
        } catch (ValidationException $th) {
            return $this->errorResp($th->errors());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        try {
            $response = $this->service->create($request->all());
            return $this->successResp('Successfully create data', new UserDetailResource($response));
        } catch (\Throwable $th) {
            return $this->errorResp($th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $response = $this->service->show($id);
            return $this->successResp('Detail data', new UserDetailResource($response));
        } catch (\Throwable $th) {
            return $this->errorResp($th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        try {
            $response = $this->service->update($id, $request->all());
            return $this->successResp('Successfully updated data', new UserDetailResource($response));
        } catch (\Throwable $th) {
            return $this->errorResp($th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $response = $this->service->delete($id);
            return $this->successResp('Successfully deleted data', $response);
        } catch (\Throwable $th) {
            return $this->errorResp($th->getMessage());
        }
    }
}
