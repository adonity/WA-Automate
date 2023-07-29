<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-first">
                <h3>Devices</h3>
                <p class="text-subtitle text-muted">Device WhatsApp Details</p>
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
                                            <th>Nama</th>
                                            <th>
                                                {{ $device->name }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Nomor WhatsApp</th>
                                            <th>
                                                {{ $device->number }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Description</th>
                                            <th>
                                                {{ $device->description }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <th>
                                                @if ($device->status == "connected")
                                                    <span class="badge bg-primary">Connected</span>
                                                @else
                                                    <span class="badge bg-danger">Disconnected</span>
                                                @endif
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>API Key</th>
                                            <th>
                                                @livewire('input.apikey', ['key' => $device->key])
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="d-flex h-100">
                                    <a href="{{ route('devices.index') }}" class="btn btn-secondary ms-auto mt-auto mb-3"><i class="bi bi-arrow-left"></i> Back</a>
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
