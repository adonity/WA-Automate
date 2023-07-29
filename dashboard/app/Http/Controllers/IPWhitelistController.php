<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\IPWhitelist;
use Maatwebsite\Excel\Facades\Excel;

class IPWhitelistController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ipwhitelist = IPWhitelist::orderBy('ipwhitelist.created_at', 'asc');
        if($request->q){
            $ipwhitelist = $ipwhitelist->where(function($q) use ($request) {
            $q->where('ip', "like", '%'.$request->q.'%');
            });
        }
        $ipwhitelist = $ipwhitelist->paginate(20);

        return view('pages.ipwhitelist.index', compact('ipwhitelist'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.ipwhitelist.create');
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
            'ip' => 'required|string',
        ]);

        $ipwhitelist = IPWhitelist::create([
            'ip' => trim($request->ip),
        ]);


        if ($ipwhitelist) {
            return redirect()
                ->route('ipwhitelist.index')
                ->with([
                    'success' => 'New IPWhitelist has been created successfully'
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
        $ipwhitelist = IPWhitelist::findOrFail($id);
        return view('pages.ipwhitelist.edit', compact('ipwhitelist'));
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
            'ip' => 'required|string',
        ]);

        $ipwhitelist = IPWhitelist::findOrFail($id);
        $ipwhitelist->ip = $request->ip;

        if ($ipwhitelist->save()) {
            return redirect()
                ->route('ipwhitelist.index')
                ->with([
                    'success' => 'IPWhitelist has been updated successfully'
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
        $device = IPWhitelist::findOrFail($id);
        $device->delete();

        return redirect()
            ->route('ipwhitelist.index')
            ->with([
                'success' => 'IPWhitelist has been deleted successfully'
            ]);
    }
}
