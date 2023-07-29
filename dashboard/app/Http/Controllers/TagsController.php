<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Tags;

class TagsController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tags = Tags::admin()->orderBy('tags.created_at', 'asc');
        if($request->q){
            $tags = $tags->where(function($q) use ($request) {
                $q->where('name', "like", '%'.$request->q.'%');
            });
        }
        $tags = $tags->paginate(20);

        return view('pages.tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2',
            'tags' => 'required|string',
        ]);

        $check = Tags::where('name', $request->name)->where('id_user', \Auth::id())->get();

        if(count($check) > 0){
            return redirect()
                ->back()
                ->withInput()
                ->with([
                    'error' => 'Nama Sudah digunakan'
                ]);
        }else{
            $tags = Tags::create([
                'name' => trim($request->name),
                'tags' => trim($request->tags),
                'id_user' => \Auth::id(),
            ]);
        }


        if ($tags) {
            return redirect()
                ->route('tags.index')
                ->with([
                    'success' => 'New Tags has been created successfully'
                ]);
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with([
                    'error' => 'Some problem occurred, please try again'
                ]);
        }
    
    }

    /**
     * Show the form for editing the specified resource.
    *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tags = Tags::findOrFail($id);
        return view('pages.tags.edit', compact('tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2',
            'tags' => 'required|string',
        ]);

        $tags = Tags::findOrFail($id);
        $tags->name = $request->name;
        $tags->tags = $request->tags;

        if ($tags->save()) {
            return redirect()
                ->route('tags.index')
                ->with([
                    'success' => 'Tags has been updated successfully'
                ]);
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with([
                    'error' => 'Some problem occurred, please try again'
                ]);
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
        $device = Tags::findOrFail($id);
        $device->delete();

        return redirect()
            ->route('tags.index')
            ->with([
                'success' => 'Tags has been deleted successfully'
            ]);
    }
}
