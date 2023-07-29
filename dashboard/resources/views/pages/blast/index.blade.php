<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-first">
                <h3>Broadcast</h3>
                <p class="text-subtitle text-muted">List Broadcast WhatsApp </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-last">
                <div class="d-flex h-100">
                    <a href="{{ route('blast.create') }}" class="btn btn-primary ms-auto mt-auto mb-3"><i class="bi bi-plus"></i> Tambah</a>
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
                                            <th>Nama</th>
                                            <th>Tangal</th>
                                            <th>Text</th>
                                            <th>Jumlah Kontak</th>
                                            <th>Owner</th>
                                            <th>Team</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($blast as $key => $item)
                                            <tr>
                                                <td class="text-bold-500">{{ $key + 1}}</td>
                                                <td>{{ $item->name }}</td>
                                                <td>
                                                    {{$item->created_at}}
                                                </td>
                                                <td width="40%">
                                                    @switch($item->id_type)
                                                        @case(2)
                                                            <img src="{{ $item->link }}" width="200px">
                                                            @break
                                                        @case(3)
                                                            <video src="{{ $item->link }}" width="200px" controls>
                                                            @break
                                                        @default
                                                            
                                                    @endswitch
                                                    <br>
                                                    @php
                                                        $html = $item->text;
                                                        try {
                                                            $html = formatToHtml($item->text);
                                                        } catch (\Throwable $th) {
                                                            //throw $th;
                                                        }
                                                        echo $html;
                                                    @endphp
                                                </td>
                                                <td>
                                                    <table>
                                                        <tr>
                                                            <td class="p-0">Terkirim</td>
                                                            <td class="p-0">: {{$item->sent}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="p-0">Pending</td>
                                                            <td class="p-0">: {{$item->pending}}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td>
                                                    {{$item->owner_name}}
                                                </td>
                                                <td>
                                                    {{$item->parent_name}}
                                                </td>
                                                <td>
                                                    <a href="{{ route('blast.show', ['blast' => $item->id]) }}" class="btn btn-sm my-1 btn-info">
                                                        <i class="bi bi-eye-fill"></i>
                                                    </a>
                                                    <form class="d-inline" action="{{ route('blast.show', ['blast' => $item->id]) }}" method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm my-1 btn-danger">
                                                            <i class="bi bi-trash2-fill"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $blast->links('vendor.pagination.bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('livewire:load', function () {
        })
    </script>
</x-app-layout>
