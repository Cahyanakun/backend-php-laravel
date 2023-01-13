<?php

namespace App\Services;

use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\Hash;
use App\Models\Article;

class ArticleService
{

    public function list($request)
    {
        $list = Article::when($request->search, function($q) use ($request){
            $q->where(function($sub) use($request) {
                $sub->WhereRaw("title like '%".strtolower($request->search)."%'")
                    ->orWhereRaw("description like '%".strtolower($request->search)."%'");
            });
        });

        if($request->has('user_id') && $request->user_id){
            $list->where("user_id",$request->role_id);
        }

        if($request->has('order') && $request->order && $request->has('sort') && $request->sort){
            $list->orderby($request->order,$request->sort);
        }

        if ($request->has('limit')) {
            $list = $list->paginate( $request['limit'] );
        } else {
            $list = $list->paginate(10);
        }
        return $list;
    }

    public function create($request)
    {
        $store = Article::create([
            'title' => $request['title'],
            'description' => $request['description'],
            'publish' => $request['publish'],
            'user_id' => auth()->user()->id,
        ]);
        return $store;
    }

    public function update($id, $request)
    {
        $update = Article::where('id',$id)->first();
        if ( !$update ) throw ValidationException::withMessages([
            'data' => ['Data not found.'],
        ]); 
        $update->update([
            'title' => $request['title'],
            'description' => $request['description'],
            'publish' => $request['publish'],
        ]);
        return $update;
        
    }

    public function show($id)
    {
        $show = Article::with('users')->where('id',$id)->first();
        if ( !$show ) throw ValidationException::withMessages([
            'data' => ['Data not found.'],
        ]); 
        return $show;    
        
    }

    public function delete($id)
    {
        $delete = Article::where('id',$id)->first();
        if ( !$delete ) throw ValidationException::withMessages([
            'data' => ['Data not found.'],
        ]); 
        $delete->destroy($id);
        return $delete;
    }
}
