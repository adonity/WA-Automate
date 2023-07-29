<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AutoReply;
use App\Models\Device;
use App\Models\MessageType;

class AutoReplyController extends Controller
{
    protected $rules = [
        'autoreply.trigger' => 'required|string|max:225',
        'autoreply.reply' => 'required|string|max:225',
    ];
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $device = Device::admin()->get();
        $autoreply = AutoReply::select('autoreply.*','device.name as device_name','device.number as device_number')
                    ->with('type')
                    ->admin()
                    ->leftJoin('device','autoreply.id_device','device.id')
                    ->where(function ($query) use ($request){
                        $query->orWhere('autoreply.trigger', 'like', '%' . $request->q . '%')
                        ->orWhere('autoreply.text', 'like', '%' . $request->q . '%');
                    })
                    ->deviceKey($request->device)
                    ->orderBy('autoreply.created_at', 'desc')
                    ->paginate(10);
        return view('pages.autoreply.index', compact('autoreply', 'device'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $messagetypes = MessageType::where('staktif', true)->get();
        $devices = Device::admin()->get();
        return view('pages.autoreply.create', compact('devices','messagetypes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [
            'id_device' => $request->id_device,
            'trigger' => trim(formatToText($request->trigger)),
            'id_type' => $request->id_type,
            'link' => $request->link,
            'text' => $request->text,
            'footer' => $request->footer,
            'status' => $request->status,
        ];

        $file = $request->file('file');

        if($file){
            $path = uploadFile($file);
            $data['link'] = $path;
        }

        $buttons = null;

        if($request->button_title != null){
            $buttons = [];
            for ($i=0; $i < count($request->button_title); $i++) {
                $button = ['index' => $i+1];
                switch ($request->button_type[$i]) {
                    case 'call':
                        $button['callButton'] = (Object) [
                            'displayText' => $request->button_title[$i],
                            'phoneNumber' => $request->button_phone[$i],
                        ];
                        break;
                    case 'url':
                        $button['urlButton'] = (Object) [
                            'displayText' => $request->button_title[$i],
                            'url' => $request->button_url[$i],
                        ];
                        break;
                    default:
                        $button['quickReplyButton'] = (Object) [
                            'displayText' => $request->button_title[$i],
                        ];
                        break;
                };
                $buttons[] = $button;
            }
        }

        if($buttons != null) $data['buttons'] = json_encode($buttons);
        
        $device = AutoReply::create($data);

        if ($device) {
            return redirect()
                ->route('autoreply.index')
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $device = AutoReply::findOrFail($id);
        $device->delete();

        return redirect()
            ->route('autoreply.index')
            ->with([
                'success' => 'Post has been deleted successfully'
            ]);
    }
}
