<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use DB;

use App\Models\Outbox;
use App\Models\Device;
use App\Models\Contact;
use App\Models\MessageType;

class OutboxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $device = Device::admin()->get();
        $outbox = Outbox::select('outbox.*','device.name as device_name','device.number as device_number',"parent.name as parent_name","owner.name as owner_name")
                    ->join('device','outbox.id_device','device.id')
                    ->team()
                    ->admin()
                    ->deviceKey($request->device)
                    ->search($request->q)
                    ->status($request->status)
                    ->orderBy("created_at", "desc");
        $outbox = $outbox->paginate(10);
        return view('pages.outbox.index', compact('outbox','device'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $devices = Device::admin()->get();
        $contacts = Contact::admin()->get();
        $messagetypes = MessageType::where('staktif', true)->get();
        return view('pages.outbox.create', compact('devices', 'contacts', 'messagetypes'));
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
            'id_type' => $request->id_type,
            'number' => $request->number,
            'link' => $request->link,
            'text' => $request->text,
            'footer' => $request->footer,
            'status' => 0,
            'id_user' => Auth::id(),
        ];

        $buttons = null;

        $file = $request->file('file');

        if($file){
            $path = uploadFile($file);
            $data['link'] = $path;
        }

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

        // echo json_encode($buttons);exit();

        $data['status'] = $this->sendWa((Object) $data);

        if(!$data['status']){
            $data['status'] = $this->sendWa((Object) $data);
        }

        $outbox = Outbox::create($data);

        if ($outbox) {
            if($request->ajax())
                return response()->json($outbox);
            if($request->back)
                {
                    sleep(2);
                return redirect()
                    ->back()
                    ->with([
                        'success' => 'New Message has been created successfully'
                    ]);
                }
            else
                return redirect()
                    ->route('outbox.index')
                    ->with([
                        'success' => 'New Message has been created successfully'
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
        $outbox = Outbox::select('device.name as device_name', 'device.number as device_number', 'outbox.number as number', 'outbox.text as text', 'outbox.link', 'outbox.status', 'outbox.id_type', 'messagetype.label')
                    ->join('device', 'device.id', 'id_device')
                    ->join('messagetype', 'messagetype.id', 'id_type')
                    ->where('outbox.id', $id)
                    ->first();
        return view('pages.outbox.show', compact('outbox'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $outbox = Outbox::findOrFail($id);
        return view('pages.outbox.edit', compact('outbox'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function resend($id){
        $outbox = Outbox::findOrFail($id);

        $outbox->status = $this->sendWa($outbox);
        $outbox->save();
        return redirect()
            ->back()
            ->with([
                'success' => 'Post has been send successfully'
            ]);
    }

    public function storeAPI(Request $request){
        $device = Device::where('key', $request->key)->first();

        if(!$device){
            return response()->json(['success' => false]);exit();
        }
        
        $validated = $request->validate([
            'key' => 'required',
            'id_type' => 'required',
            'number' => 'required|string|max:15',
            'link' => 'max:255',
        ]);

        $data = [
            'id_device' => $device->id,
            'id_type' => $request->id_type,
            'number' => $request->number,
            'link' => $request->link,
            'text' => $request->text,
            'footer' => $request->footer,
            'status' => 0,
            'id_user' => $device->id_users,
        ];

        $buttons = $request->buttons;

        if($buttons != null) $data['buttons'] = json_encode($buttons);

        $data['status'] = $this->sendWa((Object) $data);

        if(!$data['status']){
            $data['status'] = $this->sendWa((Object) $data);
        }

        $outbox = Outbox::create($data);

        if ($outbox) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function sendWa($outbox){
        $pesan = formatPesan($outbox);

        $getnumber = substr($outbox->number, 0, 1);
        $regional = 62;
        if($getnumber == 0 || $getnumber == 8 ){
            $format_number = $regional.substr($outbox->number, 1);
        }else{
            $format_number = $outbox->number;
        }

        $response = Http::post(env('URL_WA_SERVER').'/chat/send?id='.$outbox->id_device, [
            'receiver' => $format_number,
            'message' => $pesan]);
        $res = json_decode($response->getBody());
        return $res->success;
    }
}
