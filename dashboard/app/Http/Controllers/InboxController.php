<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Device;
use App\Models\Outbox;
use App\Models\Inbox;
use App\Models\AutoReply;
use App\Models\MessageType;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class InboxController extends Controller
{
    public function index(Request $request)
    {
        $device = Device::admin()->get();       
        $inbox = Inbox::select(\DB::raw('inbox.id'),'inbox.number','inbox.text','inbox.link','inbox.id_device','inbox.me','inbox.push_name','inbox.read', 'inbox.created_at','device.name as device_name','device.number as device_number',"parent.name as parent_name","owner.name as owner_name")
                    ->join('device','inbox.id_device','device.id')
                    ->where('inbox.me', 0)
                    ->where('inbox.owndevice', 0)
                    ->where('inbox.number', "!=", "status")
                    ->deviceKey($request->device)
                    ->search($request->q)
                    ->read($request->read)
                    ->whereIn('inbox.id', 
                        Inbox::select(\DB::raw('max(inbox.id) as id'))
                        ->join('device','inbox.id_device','device.id')
                        ->where('inbox.me', 0)
                        ->where('inbox.number', "!=", "status")
                        ->deviceKey($request->device)
                        ->search($request->q)
                        ->read($request->read)
                        ->admin()
                        ->team()
                        ->orderBy('inbox.created_at', 'desc')
                        ->groupBy("inbox.number")
                        ->get()->pluck("id")
                    )
                    ->admin()
                    ->team()
                    ->orderBy('inbox.created_at', 'desc');
                    
        $inbox = $inbox->paginate(20);

        return view('pages.inbox.index2', compact('inbox','device'));
    }

    public function getAPI(Request $request){
        $inbox = Inbox::select(\DB::raw('inbox.id'),'inbox.number','inbox.text','inbox.link','inbox.id_device','inbox.me','inbox.push_name','inbox.read', 'inbox.created_at','device.name as device_name','device.number as device_number',"parent.name as parent_name","owner.name as owner_name")
                    ->join('device','inbox.id_device','device.id')
                    ->where('inbox.me', 0)
                    ->where('inbox.owndevice', 0)
                    ->where('inbox.number', "!=", "status")
                    ->deviceKey($request->device)
                    ->search($request->q)
                    ->read($request->read)
                    ->whereIn('inbox.id', 
                        Inbox::select(\DB::raw('max(inbox.id) as id'))
                        ->join('device','inbox.id_device','device.id')
                        ->where('inbox.me', 0)
                        ->where('inbox.number', "!=", "status")
                        ->deviceKey($request->device)
                        ->search($request->q)
                        ->read($request->read)
                        ->admin()
                        ->team()
                        ->orderBy('inbox.created_at', 'desc')
                        ->groupBy("inbox.number")
                        ->get()->pluck("id")
                    )
                    ->admin()
                    ->team()
                    ->orderBy('inbox.created_at', 'desc');
        $inbox = $inbox->paginate(20);
        return view('pages.inbox.list', compact('inbox'));
    }

    public function getChatAPI($id){
        $inbox = Inbox::
            select('inbox.*', \DB::raw("device.id as device_id"), "device.name as device_name", "device.status as device_status")
            ->join('device','inbox.id_device','device.id')
            ->where('inbox.id', $id)->first();

        Inbox::where('number', $inbox->number)->where('id_device', $inbox->device_id)->update(['read' => Auth::id()]);
        
        $allchat = Inbox::where('number', $inbox->number)->where('id_device', $inbox->device_id)->get();
        $messagetypes = MessageType::where('staktif', true)->get();

        return view('pages.inbox.chatbox', compact('inbox','allchat','messagetypes'));
    }

    public function getChatListAPI($id){
        $inbox = Inbox::
            select('inbox.*', \DB::raw("device.id as device_id"), "device.name as device_name", "device.status as device_status")
            ->join('device','inbox.id_device','device.id')
            ->where('inbox.id', $id)->first();
        Inbox::where('number', $inbox->number)->where('id_device', $inbox->device_id)->update(['read' => Auth::id()]);
        $allchat = Inbox::where('number', $inbox->number)->where('id_device', $inbox->device_id)->get();

        return view('pages.inbox.list-chat', compact('allchat'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_device' => 'required',
            'number' => 'required|string|min:9',
        ]);

        $number = $request->number;

        $inbox = Inbox::create([
            'id_device' => $request->id_device,
            'number' => $number,
            'text' => $request->text,
        ]);

        if ($inbox) {
            $this->checkAutoReply($inbox);
            return response()->json(['success' => true, 'data' => $inbox]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function show($id)
    {
        $inbox = Inbox::
            select('inbox.*', \DB::raw("device.id as device_id"), "device.name as device_name", "device.status as device_status")
            ->join('device','inbox.id_device','device.id')
            ->where('inbox.id', $id)->first();

        Inbox::where('number', $inbox->number)->where('id_device', $inbox->device_id)->update(['read' => Auth::id()]);
        
        $allchat = Inbox::where('number', $inbox->number)->where('id_device', $inbox->device_id)->get();
        $messagetypes = MessageType::where('staktif', true)->get();

        return view('pages.inbox.show', compact('inbox','allchat','messagetypes'));
    }

    public function storeAPI(Request $request){
        $validated = $request->validate([
            'id_device' => 'required',
            'number' => 'required|string|min:9',
            'me' => 'boolean',
        ]);

        $number = $request->number;

        $inbox = Inbox::create([
            'id_device' => $request->id_device,
            'number' => $number,
            'text' => $request->text,
            'me' => $request->me,
            'link' => $request->link,
            'push_name' => $request->push_name,
        ]);

        // if(($request->me == 0)){
        //     $number = formatNumber($number);

        //     $contact = Contact::where("number", $number)->first();

        //     if(!$contact){
        //         $contact = new Contact();
        //     }
        //     $contact->name = $request->push_name;
        //     $contact->number = $number;
        //     $contact->type = 1;
        //     $contact->save();
        // }

        if ($inbox) {
            if($request->me !== 1 && $inbox->text !== "" && $inbox->text != null)
                $this->checkAutoReply($inbox);
            return response()->json(['success' => true, 'data' => $inbox]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    
    private function checkAutoReply($inbox){
        $ar = AutoReply::where(function($q) use ($inbox) {
            foreach (explode(" ", $inbox->text) as $key => $value) {
                $q->orWhere('trigger', "$value");
            }
        })->where(function($q) use ($inbox) {
            $q->where('id_device', $inbox->id_device)
              ->orWhereNull('id_device');
            })->first();

        $number = explode('@', $inbox->number)[0];

        $devices = Device::where('number', $number)->count();

        if($devices > 0){
            $inbox->delete();
        }
        
        if(!$ar){
            return response()->json(['success' => false]);
        }

        $data = [
            'id_device' => $inbox->id_device,
            'number' => $number,
            'id_type' => $ar->id_type,
            'link' => $ar->link,
            'text' => $ar->text,
            'footer' => $ar->footer,
            'buttons' => $ar->buttons,
            'status' => 0,
            'id_user' => $ar->id_user,
        ];

        $data['status'] = $this->sendWa((Object) $data);

        $outbox = Outbox::create($data);

        if ($outbox) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    public function sendWa($config){
        $pesan = formatPesan($config);
        $format_number = formatNumber($config->number);

        $response = Http::post(env('URL_WA_SERVER').'/chat/send?id='.$config->id_device, [
            'receiver' => $format_number,
            'message' => $pesan]);
        $res = json_decode($response->getBody());
        return $res->success;
    }

   public function destroyAll()
    {
        $devices = Inbox::admin()->join('device','inbox.id_device','device.id')->get();
        Inbox::admin()->join('device','inbox.id_device','device.id')->select("inbox.*")->delete();

        return redirect()
            ->route('inbox.index')
            ->with([
                'success' => 'Contact has been deleted successfully'
            ]);
    }

    public function setAllRead(){
        Inbox::admin()->join('device','inbox.id_device','device.id')->select("inbox.*")->update(['read' => Auth::id()]);
        return redirect()
            ->route('inbox.index')
            ->with([
                'success' => 'Inbox has been set read successfully'
            ]);
    }
}
