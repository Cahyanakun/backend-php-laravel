<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Services\ArticleService;
use App\Http\Resources\Article\ArticleListCollection;
use App\Http\Resources\Article\ArticleDetailResource;
use App\Http\Requests\API\Article\CreateArticleRequest;
use App\Http\Requests\API\Article\UpdateArticleRequest;

class ArticleController extends Controller
{
    protected $service;

    public function __construct( ArticleService $service )
    {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $response = $this->service->list($request);
            return $this->successResp('Successfully Get List', new ArticleListCollection($response));
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
    public function store(CreateArticleRequest $request)
    {
        try {
            $response = $this->service->create($request->all());
            return $this->successResp('Successfully create data', new ArticleDetailResource($response));
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
            return $this->successResp('Detail data', new ArticleDetailResource($response));
        } catch (\Throwable $th) {
            return $this->errorResp($th->errors());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateArticleRequest $request, $id)
    {
        try {
            $response = $this->service->update($id, $request->all());
            return $this->successResp('Successfully updated data', new ArticleDetailResource($response));
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
            return $this->errorResp($th->errors());
        }
    }
}
