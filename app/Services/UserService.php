<?php

namespace App\Services;

use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserService
{

    public function list($request)
    {
        $list = User::when($request->search, function($q) use ($request){
            $q->where(function($sub) use($request) {
                $sub->WhereRaw("name like '%".strtolower($request->search)."%'")
                    ->orWhereRaw("email like '%".strtolower($request->search)."%'")
                    ->orWhereRaw("role_name like '%".strtolower($request->search)."%'");
            });
        });

        if($request->has('role_id') && $request->role_id){
            $list->where("role_id",$request->role_id);
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
        $store = User::create($request);
        return $store;
    }

    public function update($id, $request)
    {
        $update = User::where('id',$id)->first();
        if ( !$update ) throw ValidationException::withMessages([
            'data' => ['Data not found.'],
        ]); 
        $update->update($request);
        return $update;
        
    }

    public function show($id)
    {
        $show = User::with('articles')->where('id',$id)->first();
        if ( !$show ) throw ValidationException::withMessages([
            'data' => ['Data not found.'],
        ]); 
        return $show;    
        
    }

    public function delete($id)
    {
        $delete = User::where('id',$id)->first();
        if ( !$delete ) throw ValidationException::withMessages([
            'data' => ['Data not found.'],
        ]); 
        $delete->destroy($id);
        return $delete;
    }
}
