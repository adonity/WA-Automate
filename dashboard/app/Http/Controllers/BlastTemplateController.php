<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Blast;
use App\Models\BlastContact;
use App\Models\BlastDevice;
use App\Models\ContactGroup;
use App\Models\Contact;
use App\Models\Contact2;
use App\Models\MessageType;
use DB;
use Illuminate\Support\Facades\Auth;

class BlastGroupController extends Controller
{
    protected $rules = [
        'name' => 'required',
        'id_contactgroup' => 'required',
        'id_type' => 'required',
    ];
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $blast = Blast::select('blast.*', DB::raw('SUM(IF(blastcontact.status=1,1,0)) as sent'), DB::raw('SUM(IF(blastcontact.status=0,1,0)) as pending'),"parent.name as parent_name","owner.name as owner_name")
                    ->leftJoin('blastcontact','blastcontact.id_blast','blast.id')
                    ->admin()
                    ->leftJoin(\DB::raw('users owner'),'owner.id','blast.id_user')
                    ->leftJoin(\DB::raw('users parent'),'owner.parent','parent.id')
                    ->groupBy('blast.id')
                    ->orderBy('blast.created_at', 'desc')
                    ->paginate(10);

        return view('pages.blast.index', compact('blast'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $devices = Device::admin()->get();
        $contact = Contact::admin()->contact1()->get();
        $contact2 = Contact::admin()->contact2()->get();
        $messagetypes = MessageType::where('staktif', true)->get();
        return view('pages.blast.create', compact('devices', 'messagetypes', 'contact', 'contact2'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            
            $data = [
                'name' => $request->name,
                'id_type' => $request->id_type,
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

            $blast = Blast::create($data);

            $contact = $request->contact;
            $contact2 = $request->contact2 != null ?$request->contact2:[] ;

            $_contact = count($contact);
            $_contact2 = count($contact2);
            $fullcontact = $_contact > $_contact2 ? $_contact : $_contact2;

            for ($i=0; $i < $fullcontact; $i++) {
                if($i < $_contact){
                    $blastcontact = new BlastContact;
                    $blastcontact->id_blast = $blast->id;
                    $blastcontact->id_contact = $contact[$i];
                    $blastcontact->save();
                }
                if($i < $_contact2){
                    $blastcontact = new BlastContact;
                    $blastcontact->id_blast = $blast->id;
                    $blastcontact->id_contact = $contact2[$i];
                    $blastcontact->save();
                }
            }

            // foreach ($request->contact as $key => $value) {
            //     $blastcontact = new BlastContact;
            //     $blastcontact->id_blast = $blast->id;
            //     $blastcontact->id_contact = $value;
            //     $blastcontact->save();
            // }

            foreach ($request->device as $key => $value) {
                $blastdevice = new BlastDevice;
                $blastdevice->id_blast = $blast->id;
                $blastdevice->id_device = $value;
                $blastdevice->save();
            }

            DB::commit();
            return redirect()
                ->route('blast.index')
                ->with([
                    'success' => 'New Blast has been created successfully'
                ]);
        } catch (\Exception $e) {
            dd($e);die;
            DB::rollback();
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
        $blast = Blast::select('blast.*', DB::raw('SUM(IF(blastcontact.status=1,1,0)) as sent'), DB::raw('SUM(IF(blastcontact.status=0,1,0)) as pending'))
                    ->leftJoin('blastcontact','blast.id','blastcontact.id_blast')
                    ->with('blastcontact','blastcontact.contact','blastcontact.device','blastdevice','blastdevice.device')
                    ->groupBy('blast.id')
                    ->orderBy('blast.created_at', 'desc')
                    ->where('blast.id', $id)->first();

        $blast_message = $this->generateMessage($blast);
        
        return view('pages.blast.show2', compact('blast','blast_message'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $device = Blast::findOrFail($id);
        $device->delete();

        return redirect()
            ->route('blast.index')
            ->with([
                'success' => 'Blast has been deleted successfully'
            ]);
    }
}
