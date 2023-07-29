<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Device;
use App\Models\Outbox;
use App\Models\Inbox;
use App\Models\AutoReply;
use App\Models\MessageType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ChatRoomController extends Controller
{
    public function index(Request $request)
    {
        if($request->device){
            $find = Http::get(env('URL_WA_SERVER')."/chat/get/?id=$request->device");
            $cek = json_decode($find->getBody());
            $inbox = $cek->data;
        }else{
            $inbox = [];
        }
        // dd($inbox);exit();

        $device = Device::admin()->get();

        return view('pages.chatroom.index', compact('inbox','device'));
    }

    public function show($id_device, $id)
    {
        $number = explode('@', $id)[0];
        $inbox = Inbox::
            select('inbox.*', \DB::raw("device.id as device_id"))
            ->join('device','inbox.id_device','device.id')
            ->where('inbox.number', $id)
            ->orWhere('inbox.number', $number)
            ->first();

        $find = Http::get(env('URL_WA_SERVER')."/chat/get/$id?id=$id_device&limit=1000");
        $cek = json_decode($find->getBody());
        
        $allchat = $cek->data;
        $messagetypes = MessageType::where('staktif', true)->get();

        Inbox::where('number', $id)->where('id_device', $id_device)->update(['read' => Auth::id()]);
        return response()->json($allchat);exit();

        return view('pages.chatroom.show', compact('inbox','allchat','messagetypes', 'id', 'id_device'));
    }

    public function sendWa($config){
        $pesan = formatPesan($config);

        $getnumber = substr($config->number, 0, 1);
        $regional = 62;
        if($getnumber == 0 || $getnumber == 8 ){
            $format_number = $regional.substr($config->number, 1);
        }else{
            $format_number = $config->number;
        }

        $response = Http::post(env('URL_WA_SERVER').'/chat/send?id='.$config->id_device, [
            'receiver' => $format_number,
            'message' => $pesan]);
        $res = json_decode($response->getBody());
        return $res->success;
    }

}
