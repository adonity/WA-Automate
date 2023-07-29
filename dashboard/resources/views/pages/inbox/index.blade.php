<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-4 order-md-1 order-first">
                <h3>{{ __("Inbox") }}</h3>
                <p class="text-subtitle text-muted">{{ __("WhatsApp Inbox") }}</p>
            </div>
            <div class="col-12 col-md-8 order-md-2 order-last">
                <div class="d-flex h-100">
                    <form id="form-filter" action="{{ route("inbox.index") }}" class="row g-2 ms-auto mt-auto mb-3 me-3">
                        <div class="col-auto">
                            <div class="input-group">
                                <select name="device" class="form-constrol form-select">
                                    <option value="">-- Pilih Device --</option>
                                    @foreach ($device as $item)
                                        <option value="{{ $item->key }}" {{ $item->key == request()->device ?"selected":"" }}>{{ $item->name }} | {{ $item->number }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="input-group">
                                <select name="read" class="form-constrol form-select">
                                    <option value="">-- Status --</option>
                                    <option value="1" {{ "1" == request()->read ?"selected":"" }}>Telah Dibaca</option>
                                    <option value="0" {{ "0" == request()->read ?"selected":"" }}>Belum Dibaca</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="input-group">
                                <input name="q" type="text" class="form-control" value="{{ request()->q }}">
                            </div>
                        </div>
                        <div class="col-auto">
                            <button href="{{ route('inbox.index') }}" class="btn btn-primary"><i class="bi bi-search"></i> {{ __("Cari") }}</button>
                        </div>
                        
                    </form>
                    <a href="{{ route('inbox.delete-all') }}" class="btn ms-2 btn-danger mt-auto mb-3" onclick="return confirm('Are you sure to delete all Inbox ?');"><i class="bi bi-trash2-fill"></i> Delete All</a>

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
                                            <th>Pesan</th>
                                            <th>Owner</th>
                                            <th>Team</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($inbox as $key => $item)
                                            <tr>
                                                <td class="text-bold-500">{{ $key + 1}}</td>
                                                <td>
                                                    <p class="fw-bold mb-0">Device :</p>
                                                    <p>{{ $item->device_name }} | {{ $item->device_number }}</p>
                                                    <p class="fw-bold mb-0">Chat Room :</p>
                                                    <p class="fw-bold mb-0">{{ $item->push_name }} | {{ $item->number }}</p>
                                                    <p>{{ date('H:i d-m-Y', strtotime($item->created_at)); }}</p>
                                                </td>
                                                <td class="message">{{ $item->text }}</td>
                                                <td>
                                                    {{ $item->owner_name }}
                                                </td>
                                                <td>
                                                    {{ $item->parent_name }}
                                                </td>
                                                <td>
                                                    <a href="{{ route('inbox.show', ["pesan_masuk"=> $item->id]) }}" class="btn btn-sm my-1 {{ $item->read >= 1?"btn-secondary":"btn-info" }}">
                                                        <i class="bi bi-eye-fill"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $inbox->withQueryString()->links('vendor.pagination.bootstrap-5') }}
                                <div class="flex justify-end">
                                    <form action="" class="ms-auto">
                                        <div class="input-group ms-auto" style="width:150px">
                                            <input class="form-control" type="number" name="page" value="{{ request()->page }}">
                                            <button class="btn btn-primary">Go</button>
                                        </div>
                                    </form>
                                </div>
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
