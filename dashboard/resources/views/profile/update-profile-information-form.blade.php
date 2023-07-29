<section class="section">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ __('Profile Information') }}</h4>
            <p class="card-description">{{ __('Update your account\'s profile information and email address.') }}</p>
        </div>
        <div class="card-body">
            
            <x-maz-alert class="mr-3" on="saved" color='success'>
                Saved
            </x-maz-alert>
            <form wire:submit.prevent="updateProfileInformation">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="form-group">
                        <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                            <!-- Profile Photo File Input -->
                            <input type="file" class="d-none"
                                wire:model="photo"
                                x-ref="photo"
                                x-on:change="
                                        photoName = $refs.photo.files[0].name;
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            photoPreview = e.target.result;
                                        };
                                        reader.readAsDataURL($refs.photo.files[0]);
                                " />
                            <div class="position-relative" style="width: 150px">
                                <!-- Current Profile Photo -->
                                <div class="w-100 cursor-pointer" role="button" x-show="! photoPreview" x-on:click.prevent="$refs.photo.click()">
                                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="w-100">
                                </div>
                                <!-- New Profile Photo Preview -->
                                <div class="w-100 cursor-pointer d-inline-block" role="button" x-on:click.prevent="$refs.photo.click()">
                                    <img x-bind:src="photoPreview" alt="{{ $this->user->name }}" class="w-100" x-show="photoPreview" style="display: none;">
                                </div>
                                @if ($this->user->profile_photo_path)
                                    <button type="button" class="btn btn-sm rounded-0 btn-danger position-absolute top-0 end-0" wire:click="deleteProfilePhoto">
                                        <i class="bi bi-x"></i>
                                    </button>
                                @endif
                            </div>
                            <x-jet-input-error for="photo" class="mt-2" />
                        </div>
                    </div>
                @endif
                <!-- Name -->
                <div class="form-group">
                    <x-jet-label for="name" value="{{ __('Name') }}" />
                    <input id="name" type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" wire:model.defer="state.name" autocomplete="name" >
                    <x-maz-input-error for="name" />
                </div>

                <!-- Email -->
                <div class="col-span-6 sm:col-span-4">
                    <x-jet-label for="email" value="{{ __('Email') }}" />
                    <input type="email" name="email " id="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" wire:model.defer="state.email" >
                    <x-maz-input-error for="email" />
                </div>


                <button class="btn btn-primary float-end mt-2"  wire:loading.attr="disabled" wire:target="photo">Save</button>
            </form>
        </div>
    </div>
</section>
