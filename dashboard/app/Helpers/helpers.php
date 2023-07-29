<?php

if (! function_exists('formatToHtml')) {
    function formatToHtml($string)
    {
        $json = json_decode($string);
        $texts= "";
        
        if(isset($json->ops)){
            foreach ($json->ops as $key => $item) {
                $pre = "";
                $post = "";
                
                if(isset($item->attributes)){
                    if(isset($item->attributes->bold)){
                        $pre .= "<b>";
                    }

                    if(isset($item->attributes->italic)){
                        $pre .= "<i>";
                    }

                    if(isset($item->attributes->strike)){
                        $pre .= "<s>";
                        $post .= "</s>";
                    }

                    if(isset($item->attributes->italic)){
                        $post .= "</i>";
                    }

                    if(isset($item->attributes->bold)){
                        $post .= "</b>";
                    }
                }
                $texts .= $pre . (trim($item->insert)) . $post . " ";
            }
        }else{
            $texts = $string;
        }
        return $texts;
    }
}
if (! function_exists('formatToText')) {
    function formatToText($_json){
        $json = json_decode($_json);
        $texts= "";
        
        foreach ($json->ops as $key => $item) {
            $pre = "";
            $post = "";
            
            if(isset($item->attributes)){
                if(isset($item->attributes->bold)){
                    $pre .= "*";
                }

                if(isset($item->attributes->italic)){
                    $pre .= "_";
                }

                if(isset($item->attributes->strike)){
                    $pre .= "~";
                }
                $post = strrev($pre);
            }
            $texts .= $pre . ($item->insert) . $post . " ";
        }
        return $texts;
    }
}

if (! function_exists('countPesanMasuk')) {
    function countPesanMasuk(){
        $jml = \App\Models\Inbox::select('inbox.*','device.name as device_name','device.number as device_number')
                ->join('device','inbox.id_device','device.id')
                ->where('inbox.me', 0)
                ->where('inbox.number', "!=", "status")
                ->where('inbox.read', "=", 0)
                ->admin()
                ->orderBy('inbox.created_at', 'desc')->count();
        return $jml;
    }
}

if (! function_exists('countDevice')) {
    function countDevice(){
        $jml = \App\Models\Device::admin()->count();
        return $jml;
    }
}

if (! function_exists('countBalasOtomatis')) {
    function countBalasOtomatis(){
        $jml = \App\Models\AutoReply::select('autoreply.*','device.name as device_name','device.number as device_number')
                ->leftJoin('device','autoreply.id_device','device.id')
                ->admin()
                ->orderBy('autoreply.created_at', 'desc')->count();
        return $jml;
    }
}

if (! function_exists('countContact')) {
    function countContact($type = 1){
        $jml = \App\Models\Contact::select('contact.*','device.name as device_name','device.number as device_number')
                ->admin()
                ->where('type', $type)
                ->orderBy('contact.created_at', 'desc')->count();
        return $jml;
    }
}

if (! function_exists('formatPesan')) {
    function formatPesan($outbox){
        $pesan = [];

        try {
            $text = formatToText($outbox->text);
        } catch (\Throwable $th) {
            $text = trim($outbox->text);
        }

        if($outbox->id_type == 1){
            $pesan['text'] = $text;
        }

        if($outbox->id_type == 2){
            $pesan['image'] = ['url' => $outbox->link];
            $pesan['caption'] = $text;
        }

        if($outbox->id_type == 3){
            $pesan['video'] = ['url' => $outbox->link];
            $pesan['caption'] = $text;
        }

        if($outbox->id_type == 5){
            $pesan['document'] = ['url' => $outbox->link];
            $pesan['mimetype'] = "application/pdf";
            $pesan['fileName'] = $text;
        }

        if(isset($outbox->buttons)){
            if(isset($outbox->link)){
                if(strpos($outbox->link, ".mp4") !== false)
                    $pesan['video'] = ['url' => $outbox->link];
                else
                    $pesan['image'] = ['url' => $outbox->link];
                $pesan['caption'] = $text;
            }else{
                $pesan['text'] = $text;
            }
            $pesan["footer"] = $outbox->footer;
            $pesan["templateButtons"] = $outbox->buttons?json_decode($outbox->buttons):"";
        }

        return $pesan;
    }
}

if (! function_exists('getDevice')) {
    function getDevice($active = false){
        $query = \App\Models\Device::admin();
        if($active){
            $query->where("status", "connected"); 
        }
        return $query->get();
    }
}

if (! function_exists('getProfile')) {
    function getProfile($number, $device_id){
        if($device_id >= 1){
            try {
                $find = Http::get(env('URL_WA_SERVER')."/contact/get/$number?id=".$device_id);
                $getres = json_decode($find->getBody());
                $res = $getres->data;
                
                if($getres->message != "Failed to get profile."){
                    return (Object) [
                        'number' => $res->number,
                        'image' => $res->image,
                    ];
                }
            } catch (\Throwable $th) {
                return false;
            }
        }
        return false;
    }
}

if (! function_exists('formatNumber')) {
    function formatNumber($number){
        $getnumber = substr($number, 0, 1);
        $regional = 62;
        if($getnumber == 0){
            $format_number = $regional.substr($number, 1);
        }else{
            $format_number = $number;
        }
        return $format_number;
    }
}

if (! function_exists('getConfig')) {
    function getConfig($name){
        $config = \App\Models\Config::where('name', $name)->first();
        if($config)
            return $config->value;
        return false;
    }
}

if (! function_exists('getContactById')) {
    function getContactById($id){
        $config = \App\Models\Contact::where('number', explode("@",$id)[0])->first();
        if($config)
            return $config;
        return false;
    }
}

if (! function_exists('getRoleText')) {
    function getRoleText($id){
        switch ($id) {
            case 1:
                return "Owner";
            case 2:
                return "Super Admin";
            case 3:
                return "Team";
            default:
                return "Team";
        }
    }
}

if (! function_exists('getParent')) {
    function getParent($id){
        $users = \App\Models\User::where('id', $id)->first();
        return $users;
    }
}

if (! function_exists('tagsformat')) {
    function tagsformat($blast, $contact){
        $id_user = \Auth::id();
        $tags = \App\Models\Tags::where('id_user', $id_user)->get();
        
        $text = $blast->text;
        $footer = $blast->footer;
        $link = $blast->link;

        $text = str_replace('#nama', $contact->name, $text);
        $footer = str_replace('#nama', $contact->name, $footer);
        
        $text = str_replace('#info1', $contact->info1, $text);
        $footer = str_replace('#info1', $contact->info1, $footer);
        
        $text = str_replace('#info2', $contact->info2, $text);
        $footer = str_replace('#info2', $contact->info2, $footer);
        
        $text = str_replace('#info3', $contact->info3, $text);
        $footer = str_replace('#info3', $contact->info3, $footer);

        foreach ($tags as $key => $value) {
            $_tags = explode("|", $value->tags);
            $_tag = $_tags[rand(0, count($_tags) -1)];

            $text = str_replace("#$value->name", $_tag, $text);
            $footer = str_replace("#$value->name", $_tag, $footer);
        }

        return (Object) [
            'text' => $text,
            'footer' => $footer,
            'link' => $blast->link,
            'id_type' => $blast->id_type,
            'buttons' => $blast->buttons,
        ];
    }
}

if (! function_exists('uploadFile')) {
    function uploadFile($file){
        $destinationPath = 'uploads';

        $name = generateRandomString().$file->getClientOriginalName();
        $realpath = $file->getRealPath();

        $file->move($destinationPath, $name);

        return env('APP_URL')."/uploads/".$name;
    }
}


if (! function_exists('generateRandomString')) {
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}