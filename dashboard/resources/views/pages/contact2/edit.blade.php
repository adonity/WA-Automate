<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-first">
                <h3>Kontak 2</h3>
                <p class="text-subtitle text-muted">Edit Kontak </p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-last">
            </div>
        </div>
    </x-slot>

    @if ($errors->count() > 0)
        <div class="alert alert-danger">
            <h4 class="alert-heading">{{ __("Error") }}</h4>
            <ul>
                @foreach ($errors->messages() as $key => $item)
                    @foreach ($item as $child)
                        <li>{{ $child }}</li>
                    @endforeach
                @endforeach
            </ul>
        </div>
    @endif
    <section class="section">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <form action="{{ route('kontak2.update', ['kontak2' => $contact->id]) }}" method="post" class="form form-horizontal">
                                @csrf
                                @method('PUT')
		                        <input type="hidden" name="id" value="{{ $contact->id }}"> <br/>
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Nama</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" name="name" class="form-control" placeholder="Nama" value="{{ $contact->name }}">
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-person"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Nomor WhatsApp</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="number" name="number" class="form-control" placeholder="Nomor WhatsApp" value="{{ $contact->number }}">
                                                    <div class="form-control-icon">
                                                        <i class="bi bi-phone"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- <div class="col-md-4">
                                            <label>Info 1</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <div class="position-relative">
                                                    <input type="text" name="info1" class="form-control {{ $errors->has('info1') ? 'is-invalid' : '' }}" placeholder="Info 1" value="{{ $contact->info1 }}">
                                                </div>
                                                <x-maz-input-error for="info1" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Info 2</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <div class="position-relative">
                                                    <input type="text" name="info2" class="form-control {{ $errors->has('info2') ? 'is-invalid' : '' }}" placeholder="Info 2" value="{{ $contact->info2 }}">
                                                </div>
                                                <x-maz-input-error for="info2" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Info 3</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <div class="position-relative">
                                                    <input type="text" name="info3" class="form-control {{ $errors->has('info3') ? 'is-invalid' : '' }}" placeholder="Info 3" value="{{ $contact->info3 }}">
                                                </div>
                                                <x-maz-input-error for="info3" />
                                            </div>
                                        </div> --}}
                                        <div class="col-12 d-flex justify-content-end">
                                            <a href="{{ url("/kontak") }}" class="btn btn-light-secondary me-1 mb-1">Back</a>
                                            <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                            <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
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
