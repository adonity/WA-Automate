<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-first">
                <h3>Tags</h3>
                <p class="text-subtitle text-muted">Tag Details</p>
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
                                            <th>NAme</th>
                                            <th>
                                                {{ $tag->name }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Tags</th>
                                            <th>
                                                {{ $tag->tags }}
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
