<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-first">
                <h3>Kirim Pesan</h3>
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
                                            <th>Device</th>
                                            <th>
                                                {{ $outbox->device_name }} | {{ $outbox->device_number }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Nomor WhatsApp</th>
                                            <th>
                                                {{ $outbox->number }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Jenis Pesan</th>
                                            <th>
                                                {{ $outbox->label }}
                                            </th>
                                        </tr>
                                        @if ($outbox->link)
                                            <tr>
                                                <th>Media</th>
                                                <th>
                                                    @switch($outbox->id_type)
                                                        @case(2)
                                                            <img src="{{ $outbox->link }}" class="w-100" style="max-width: 600px">
                                                            @break
                                                        @case(3)
                                                            <video src="{{ $outbox->link }}" class="w-100" style="max-width: 600px" controls>
                                                            @break
                                                        @case(4)
                                                            
                                                            @break
                                                        @default
                                                            
                                                    @endswitch
                                                </th>
                                            </tr>
                                        @endif
                                        <tr>
                                            <th>Pesan</th>
                                            <th>
                                                {!! formatToHtml($outbox->text) !!}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <th>
                                                @if ($outbox->status == 1)
                                                    <span class="badge bg-primary">Terkirim</span>
                                                @else
                                                    <span class="badge bg-danger">Gagal</span>
                                                @endif
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="d-flex h-100">
                                    <a href="{{ route('outbox.index') }}" class="btn btn-secondary ms-auto mt-auto mb-3"><i class="bi bi-arrow-left"></i> Back</a>
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
        })
    </script>
</x-app-layout>
