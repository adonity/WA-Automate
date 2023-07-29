<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-4 order-md-1 order-first">
                <h3>{{ __("Inbox") }}</h3>
                <p class="text-subtitle text-muted">{{ __("WhatsApp Inbox") }}</p>
            </div>
            <div class="col-12 col-md-8 order-md-2 order-last">
                <div class="d-flex h-100">
                    <form id="form-filter" action="{{ route("chatroom.index") }}" class="row g-2 ms-auto mt-auto mb-3 me-3">
                        <div class="col-auto">
                            <div class="input-group">
                                <select name="device" class="form-constrol form-select">
                                    <option value="">-- Pilih Device --</option>
                                    @foreach ($device as $item)
                                        <option value="{{ $item->id }}" {{ $item->id == request()->device ?"selected":"" }}>{{ $item->name }} | {{ $item->number }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
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
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Info</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($inbox as $key => $item)
                                            @php
                                                $contact = getContactById($item->id)
                                            @endphp
                                            <tr>
                                                <td class="text-bold-500">{{ $key + 1}}</td>
                                                <td class="">
                                                    <table class="">
                                                        <tr>
                                                            <td class="py-1 px-0">Name</td>
                                                            <td class="py-1 ps-5">: <b>{{ isset($contact->name)?$contact->name:"" }}</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="py-1 px-0">Number</td>
                                                            <td class="py-1 ps-5">: <b>{{ isset($contact->number)?$contact->number:explode("@", $item->id)[0] }}</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="py-1 px-0">UnRead Message</td>
                                                            <td class="py-1 ps-5">: <b>{{ isset($item->unreadCount)?$item->unreadCount:0 }}</b></td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td>
                                                    <a href="{{ route('chatroom.show', ['device_id' => request()->device, "id"=> $item->id]) }}" class="btn btn-sm my-1 btn-info">
                                                        <i class="bi bi-eye-fill"></i>
                                                    </a>
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
            const els = document.querySelectorAll('.message');
            els.forEach(el => {
                let text = el.innerText
                el.innerHTML = format_text( text );
            });
        })
        
        const submitForm = (e) => {
            document.querySelector("#form-filter").submit()
        }
        document.querySelector("select[name=device]").addEventListener('change', submitForm)
        document.querySelector("select[name=read]").addEventListener('change', submitForm)

        function format_text(text){
            return text.replace(/(?:\*)(?:(?!\s))((?:(?!\*|\n).)+)(?:\*)/g,'<b>$1</b>')
            .replace(/(?:_)(?:(?!\s))((?:(?!\n|_).)+)(?:_)/g,'<i>$1</i>')
            .replace(/(?:~)(?:(?!\s))((?:(?!\n|~).)+)(?:~)/g,'<s>$1</s>')
            .replace(/(?:--)(?:(?!\s))((?:(?!\n|--).)+)(?:--)/g,'<u>$1</u>')
            .replace(/(?:```)(?:(?!\s))((?:(?!\n|```).)+)(?:```)/g,'<tt>$1</tt>');
        }
    </script>
</x-app-layout>
