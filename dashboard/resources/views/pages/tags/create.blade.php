<x-app-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-first">
                <h3>Tag</h3>
                <p class="text-subtitle text-muted">Tambah Tag</p>
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
    @if (isset($error))
        <div class="alert alert-danger">
            <h4 class="alert-heading">{{ __("Error") }}</h4>
            <p>{{$error}}</p>
        </div>
    @endif
    <section class="section">
        <div class="row">
            <div class="col-12 col-md-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <form action="{{ route('tags.store') }}" method="post" class="form form-horizontal">
                                {{ csrf_field() }}
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Name</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <div class="position-relative">
                                                    <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" placeholder="Name">
                                                </div>
                                                <x-maz-input-error for="name" />
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Tags</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <div class="position-relative">
                                                    <textarea type="text" name="tags" class="form-control {{ $errors->has('tags') ? 'is-invalid' : '' }}" placeholder="Tags"></textarea>
                                                </div>
                                                <x-maz-input-error for="tags" />
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-end">
                                            <a href="{{ route("tags.index") }}" class="btn btn-light-secondary me-1 mb-1">Back</a>
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
