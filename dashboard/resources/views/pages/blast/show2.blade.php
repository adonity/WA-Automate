<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-first">
                <h3>Kirim Pesan Broadcast</h3>
                <p class="text-subtitle text-muted">Detail Pesan yang dikirim</p>
            </div>
        </div>
    </x-slot>

    <section class="section">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-lg">
                                    <tbody>
                                        <tr>
                                            <th>Nama Broadcast</th>
                                            <th>
                                                {{ $blast->name }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Jenis Pesan</th>
                                            <th>
                                                {{ $blast->type->label }}
                                            </th>
                                        </tr>
                                        @if ($blast->id_type > 1)
                                            <tr>
                                                <th>Pesan Tambahan</th>
                                                <th>
                                                    @switch($blast->id_type)
                                                        @case(2)
                                                            <img src="{{ $blast->link }}" class="w-100" style="max-width: 600px">
                                                            @break
                                                        @case(3)
                                                            <video src="{{ $blast->link }}" class="w-100" style="max-width: 600px" controls>
                                                            @break
                                                        @case(4)
                                                            <table class="w-100">
                                                                <thead>
                                                                    <tr>
                                                                        <th>No</th>
                                                                        <th>Teks</th>
                                                                        <th>Jenis</th>
                                                                        <th>Content</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach (json_decode($blast->buttons) as $item)
                                                                        @php
                                                                            if(isset($item->quickReplyButton)){
                                                                                $tipe = "Quick Reply";
                                                                                $teks = $item->quickReplyButton->displayText;
                                                                                $content = "";
                                                                            }elseif(isset($item->urlButton)){
                                                                                $tipe = "Url";
                                                                                $teks = $item->urlButton->displayText;
                                                                                $content = $item->urlButton->url;
                                                                            }elseif(isset($item->callButton)){
                                                                                $tipe = "Call";
                                                                                $teks = $item->callButton->displayText;
                                                                                $content = $item->callButton->phoneNumber;
                                                                            }
                                                                        @endphp
                                                                        <tr>
                                                                            <td>{{ $item->index }}</td>
                                                                            <td>{{ $teks }}</td>
                                                                            <td>{{ $tipe }}</td>
                                                                            <td>{{ $content }}</td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                            @break
                                                        @default
                                                            
                                                    @endswitch
                                                </th>
                                            </tr>
                                        @endif
                                        <tr>
                                            <th>Pesan</th>
                                            <th>
                                                {!! formatToHtml($blast->text) !!}
                                            </th>
                                        </tr>
                                        @if ($blast->id_type == 4)
                                            <tr>
                                                <th>Footer</th>
                                                <th>
                                                    {{ ($blast->footer) }}
                                                </th>
                                            </tr>
                                        @endif
                                        <tr>
                                            <th>Status</th>
                                            <th>
                                                <table>
                                                    <tr>
                                                        <td class="p-0">Terkirim</td>
                                                        <td class="p-0">: <span class="terkirim">{{$blast->sent}}</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="p-0">Pending</td>
                                                        <td class="p-0">: <span class="pending">{{$blast->pending}}</span></td>
                                                    </tr>
                                                </table>
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="progress progress-primary mt-4">
                                <div class="progress-bar progress-bar-striped {{$blast->sent/($blast->sent+$blast->pending)*100 == 0 ? "":"progress-bar-label"}}" role="progressbar" style="width: {{$blast->sent/($blast->sent+$blast->pending)*100}}%" aria-valuenow="{{$blast->sent*($blast->sent+$blast->pending)/100}}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex h-100">
                                <a href="{{ route('blast.index') }}" class="btn btn-secondary ms-auto mt-auto mb-3"><i class="bi bi-arrow-left"></i> Back</a>
                                <button class="btn btn-primary ms-3 mt-auto mb-3 btn-mulai-blast"><i class="bi bi-send-fill"></i> Mulai Broadcast</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </section>
    <section class="section">
        <div class="d-flex">
            <h3 class="mb-3">Device List</h3> 
            <div class="ms-auto">
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-lg">
                                    <thead>
                                        <tr>
                                            <th>
                                                No
                                            </th>
                                            <th>
                                                Name
                                            </th>
                                            <th>
                                                Number
                                            </th>
                                            <th>
                                                Status
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $_devices = $blast->blastdevice->filter(function($item, $key) {
                                                            if(isset($item->device->status))
                                                                return $item->device->status == "connected";
                                                            else
                                                                return false;
                                                        });

                                        @endphp
                                        @foreach ($_devices as $key => $item)
                                            <tr>
                                                <td>
                                                    {{$key + 1}}
                                                </td>
                                                <td>
                                                    {{$item->device->name}}
                                                </td>
                                                <td>
                                                    {{$item->device->number}}
                                                </td>
                                                <td>
                                                    {{$item->device->status}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section">
        <div class="d-flex">
            <h3 class="mb-3">Contact List</h3> 
            <div class="ms-auto">
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-lg">
                                    <thead>
                                        <tr>
                                            <th>
                                                No
                                            </th>
                                            <th>
                                                Jenis
                                            </th>
                                            <th>
                                                Name
                                            </th>
                                            <th>
                                                Number
                                            </th>
                                            <th>
                                                Device
                                            </th>
                                            <th>
                                                Sent At
                                            </th>
                                            <th>
                                                Status
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $blastcontact = [];
                                        @endphp
                                        @foreach ($blast->blastcontact as $key => $item)
                                            @php
                                                if($item->status != 1){
                                                    $blastcontact[] = $item; 
                                                }
                                            @endphp
                                            <tr>
                                                <td>
                                                    {{$key + 1}}
                                                </td>
                                                <td>
                                                    {{ $item->contact->type == 1 ? "Kontak 1" :"Kontak 2"}}
                                                </td>
                                                <td>
                                                    {{ isset($item->contact->name) ? $item->contact->name :""}}
                                                </td>
                                                <td>
                                                    {{ isset($item->contact->number) ? $item->contact->number :""}}
                                                </td>
                                                <td>
                                                    {{ isset($item->device->name) ? $item->device->name :""}}
                                                </td>
                                                <td>
                                                    {{ $item->updated_at ? $item->updated_at :""}}
                                                </td>
                                                <td>
                                                    @if ($item->status == 1)
                                                        Terkirim
                                                    @else
                                                        Pending                                                        
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('livewire:load', function () {
            const _socket = io(APP_SOCKETURL);
            let terkirim = {{ $blast->sent }};
            let pending = {{ $blast->pending }};

            _socket.on("blast-"+{{$blast->id}}, function (data) {
                var pg = document.querySelector(".progress-bar");
                var elkirim = document.querySelector(".terkirim");
                var elpending = document.querySelector(".pending");

                if(data.message.status == '1'){
                    terkirim++
                    pending--
                    pg.style.width = (terkirim/(terkirim+pending)*100)+"%"

                    elkirim.innerHTML = terkirim
                    elpending.innerHTML = pending
                }
                // window.location.href = window.location.href
            });

            document.querySelector(".btn-mulai-blast").addEventListener("click", (e) => {
                // console.log("masuk")
                
                @php
                    $_blast_message = array_chunk($blast_message,500);
                @endphp
                
                @foreach ($_blast_message as $key => $item)
                    setTimeout(() => {
                        _socket.emit("broadcast-new", { blasts: {!! json_encode($item)!!}, id: {{$blast->id}}, delay: "{{getConfig("BROADCAST_DELAY")}}" })
                    }, 5000*{{$key}});
                @endforeach
            })
        })
    </script>
</x-app-layout>
