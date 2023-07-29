<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Contact;
use App\Imports\ContactImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\Rule;

class Contact2Controller extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $contact = Contact::admin()->contact2()
                    ->orderBy('contact.created_at', 'asc');
        if($request->q){
            $contact = $contact->where(function($q) use ($request) {
            $q->where('number', "like", '%'.$request->q.'%')
              ->orWhere('name', "like", '%'.$request->q.'%');
            });
        }
        $contact = $contact->paginate(20);

        return view('pages.contact2.index', compact('contact'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.contact2.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $number = formatNumber(trim($request->number));
        $validated = $request->validate([
            'name' => 'required|string|min:2',
            'number' => [
                'required'
            ],
        ]);

        if(Contact::where('number', $number)->count() > 0){
            return redirect()
                ->back()
                ->withInput()
                ->with([
                    'error' => 'Given number are not unique'
                ]);
        }

        $contact = Contact::create([
            'name' => trim($request->name),
            'number' => formatNumber(trim($request->number)),
            'type' => 2,
            'id_user' => \Auth::id(),
        ]);


        if ($contact) {
            return redirect()
                ->route('kontak2.index')
                ->with([
                    'success' => 'New Contact has been created successfully'
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
        $contact = Contact::findOrFail($id);
        return view('pages.contact2.edit', compact('contact'));
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
            'number' => 'required|string|max:15',
        ]);

        $contact = Contact::findOrFail($id);
        $contact->name = $request->name;
        $contact->type = 2;
        $contact->number = formatNumber($request->number);

        if ($contact->save()) {
            return redirect()
                ->route('kontak2.index')
                ->with([
                    'success' => 'Contact has been updated successfully'
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
        $device = Contact::findOrFail($id);
        $device->delete();

        return redirect()
            ->route('kontak2.index')
            ->with([
                'success' => 'Contact has been deleted successfully'
            ]);
    }

    public function destroyAll()
    {
        Contact::admin()->contact2()->delete();
        // dd(Contact::admin()->get());die;

        return redirect()
            ->route('kontak2.index')
            ->with([
                'success' => 'All Contact has been deleted successfully'
            ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'fileimport' => 'required|max:1000000|mimes:xlsx,xls,csv',
        ]);

        $import = Excel::import(new ContactImport(2), $request->file('fileimport'));
        return back()->with('success', 'All good!');
    }
}
