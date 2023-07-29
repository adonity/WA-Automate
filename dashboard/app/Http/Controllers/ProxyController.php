<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Proxy;
use App\Imports\ProxyImport;
use Maatwebsite\Excel\Facades\Excel;

class ProxyController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $proxy = Proxy::orderBy('proxy.created_at', 'asc');
        if($request->q){
            $proxy = $proxy->where(function($q) use ($request) {
            $q->where('ip', "like", '%'.$request->q.'%');
            });
        }
        $proxy = $proxy->paginate(20);

        return view('pages.proxy.index', compact('proxy'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.proxy.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'host' => 'required|string',
            'port' => 'required|string',
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $proxy = Proxy::create([
            'host' => trim($request->host),
            'port' => trim($request->port),
            'username' => trim($request->username),
            'password' => trim($request->password),
        ]);


        if ($proxy) {
            return redirect()
                ->route('proxy.index')
                ->with([
                    'success' => 'New Proxy has been created successfully'
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
        $proxy = Proxy::findOrFail($id);
        return view('pages.proxy.edit', compact('proxy'));
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

        $proxy = Proxy::findOrFail($id);
        $proxy->ip = $request->ip;

        if ($proxy->save()) {
            return redirect()
                ->route('proxy.index')
                ->with([
                    'success' => 'Proxy has been updated successfully'
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
        $device = Proxy::findOrFail($id);
        $device->delete();

        return redirect()
            ->route('proxy.index')
            ->with([
                'success' => 'Proxy has been deleted successfully'
            ]);
    }

    public function getAPI(Request $request)
    {
        $proxy = Proxy::all();

        return response()->json($proxy);
    }

    public function import(Request $request)
    {
        $request->validate([
            'fileimport' => 'required|max:10000000|mimes:xlsx,xls,csv',
        ]);

        $import = Excel::import(new ProxyImport, $request->file('fileimport'));
        return back()->with('success', 'All good!');
    }

    public function destroyAll()
    {
        Proxy::proxy()->delete();

        return redirect()
            ->route('proxy.index')
            ->with([
                'success' => 'All Contact has been deleted successfully'
            ]);
    }
}
