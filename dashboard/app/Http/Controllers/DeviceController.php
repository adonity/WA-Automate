<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Dirape\Token\Token;
use DB;

use App\Models\Device;
use App\Imports\DevicesImport;
use Maatwebsite\Excel\Facades\Excel;

class DeviceController extends Controller
{
    protected $rules = [
        'device.name' => 'required|string|min:2',
        'device.number' => 'required|string|max:15',
        'device.description' => 'string|max:500',
        'device.proxy' => 'string|max:500',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $devices = Device::admin()->get();

        // try {
        //     foreach ($devices as $cek){
        //         $find = Http::get(env('URL_WA_SERVER').'/session/find/'.$cek->id);
        //         $getres = json_decode($find->getBody());

        //         if($getres->message == "Session not found."){
        //             $status= "disconnected";
        //             DB::table('device')->where('id', $cek->id)->update(['status' => $status,'updated_at' => now()]);
        //         }
        //     }
        // } catch (\Throwable $th) {
        //     throw $th;
        // }

        $devices = Device::select('device.*',"parent.name as parent_name","owner.name as owner_name")->admin()->team()->get();

        $total = $devices->count();
        $connected = $devices->filter(function ($item) {
                return $item->status == "connected";
            })->values();
        $disconnected = $devices->filter(function ($item) {
                return $item->status != "connected";
            })->values();

        return view('pages.devices.index', compact('devices', 'total', 'connected', 'disconnected'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.devices.create');
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
            'name' => 'required|string|min:2|unique:device',
            'number' => 'required|string|max:15|unique:device',
            'category' => 'required|string',
            'description' => 'max:500',
        ]);

        $device = Device::create([
            'name' => $request->name,
            'number' => $request->number,
            'category' => $request->category,
            'description' => $request->description,
            'multidevice' => $request->multidevice,
            'status' => 0,
            'key' => (new Token())->Unique('device', 'key', 60),
            'id_users' => $id = Auth::id()
        ]);

        if ($device) {
            return redirect()
                ->route('devices.index')
                ->with([
                    'success' => 'New post has been created successfully'
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $device = Device::findOrFail($id);
        return view('pages.devices.show', compact('device'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $device = Device::findOrFail($id);
        return view('pages.devices.edit', compact('device'));
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
            'category' => 'required|string',
            'description' => 'max:500',
        ]);

        $device = Device::findOrFail($id);
        $device->name = $request->name;
        $device->number = $request->number;
        $device->description = $request->description;
        $device->multidevice = $request->multidevice;
        $device->category = $request->category;

        if ($device->save()) {
            return redirect()
                ->route('devices.index')
                ->with([
                    'success' => 'New post has been updated successfully'
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
        $device = Device::findOrFail($id);
        $device->delete();
        Http::delete(env('URL_WA_SERVER').'/session/delete/'.$id);

        return redirect()
            ->route('devices.index')
            ->with([
                'success' => 'Post has been deleted successfully'
            ]);
    }


   public function destroyAll()
    {
        $devices = Device::admin()->get();
        Device::admin()->delete();

        foreach ($devices as $key => $value) {
            Http::delete(env('URL_WA_SERVER').'/session/delete/'.$value->id);
        }

        return redirect()
            ->route('devices.index')
            ->with([
                'success' => 'All Device has been deleted successfully'
            ]);
    }

   public function destroyDisconnected()
    {
        $devices = Device::where("status", "disconnected")->admin()->get();
        Device::where("status", "disconnected")->admin()->delete();

        foreach ($devices as $key => $value) {
            Http::delete(env('URL_WA_SERVER').'/session/delete/'.$value->id);
        }

        return redirect()
            ->route('devices.index')
            ->with([
                'success' => 'Disconnected Device has been deleted successfully'
            ]);
    }


    public function scan($id)
    {
			$find = Http::get(env('URL_WA_SERVER').'/session/find/'.$id);
			$cek = json_decode($find->getBody());
            $cekMD = Device::findOrFail($id);

			if($cek->message == "Session found."){
                $device = $cekMD;
			}
			else{
				if($cekMD->multidevice == 1){
					$islegacy = "false";
				}else{
					$islegacy = "true";
				}
				$response = Http::post(env('URL_WA_SERVER').'/session/add', ['id' => $id, 'isLegacy' => $islegacy]);
				$res = json_decode($response->getBody());
                $cekMD->qrcode = $res->data->qr;
                $cekMD->save();
				$device = $cekMD;
			}

			return view('pages.devices.scan', compact('device'));
    }

    public function tonext($id)
    {
        $find = Http::get(env('URL_WA_SERVER').'/session/find/'.$id);
        $cek = json_decode($find->getBody());
        $cekMD = Device::findOrFail($id);

        if($cek->message == "Session found."){
            $device = $cekMD;
        }
        else{
            if($cekMD->multidevice == 1){
                $islegacy = "false";
            }else{
                $islegacy = "true";
            }
            $response = Http::post(env('URL_WA_SERVER').'/session/add', ['id' => $id, 'isLegacy' => $islegacy]);
            $res = json_decode($response->getBody());
            $cekMD->qrcode = $res->data->qr;
            $cekMD->save();
            $device = $cekMD;
        }

        return view('pages.devices.scan', compact('device'));
        // get the current user
        $user = User::find($id);

        // get previous user id
        $previous = User::where('id', '<', $user->id)->max('id');

        // get next user id
        $next = User::where('id', '>', $user->id)->min('id');

        return View::make('users.show')->with('previous', $previous)->with('next', $next);
    }


    public function statusUpdate(Request $request, $id)
    {
        $device = Device::findOrFail($id);
        $device->status = $request->status;
        $device->qrcode = $request->qrcode;
        $device->proxy = $request->proxy;

        if ($device->save()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'fileimport' => 'required|max:10000000|mimes:xlsx,xls,csv',
        ]);

        $import = Excel::import(new DevicesImport, $request->file('fileimport'));
        return back()->with('success', 'All good!');
    }
}
