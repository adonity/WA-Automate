<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Config;
use Maatwebsite\Excel\Facades\Excel;

class ConfigController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $config = Config::orderBy('config.created_at', 'asc');
        if($request->q){
            $config = $config->where(function($q) use ($request) {
            $q->where('value', "like", '%'.$request->q.'%')
              ->orWhere('name', "like", '%'.$request->q.'%');
            });
        }
        $config = $config->paginate(20);

        return view('pages.config.index', compact('config'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.config.create');
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
            'name' => 'required|string|min:2|unique:config',
            'value' => 'required|string',
        ]);

        $config = Config::create([
            'name' => trim($request->name),
            'value' => trim($request->value),
        ]);


        if ($config) {
            return redirect()
                ->route('config.index')
                ->with([
                    'success' => 'New Config has been created successfully'
                ]);
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with([
                    'errors' => 'Some problem occurred, please try again'
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
        $config = Config::findOrFail($id);
        return view('pages.config.edit', compact('config'));
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
            'value' => 'required|string',
        ]);

        $config = Config::findOrFail($id);
        $config->name = $request->name;
        $config->value = $request->value;

        if ($config->save()) {
            return redirect()
                ->route('config.index')
                ->with([
                    'success' => 'Config has been updated successfully'
                ]);
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with([
                    'errors' => 'Some problem occurred, please try again'
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
        $device = Config::findOrFail($id);
        $device->delete();

        return redirect()
            ->route('config.index')
            ->with([
                'success' => 'Config has been deleted successfully'
            ]);
    }
}
