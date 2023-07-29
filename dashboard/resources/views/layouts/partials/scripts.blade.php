@livewireScripts

<script>
    const APP_SOCKETURL = "{{ env("APP_SOCKETURL") }}"
</script>

<script src="{{ mix('js/app.js') }}"></script>

<script src="{{ asset('/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('/vendors/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('/vendors/choices.js/choices.min.js') }}"></script>


<script src="{{ asset('/js/bootstrap.bundle.min.js') }}"></script>

<script src="{{ asset('/js/main.js') }}"></script>
<script
  src="https://code.jquery.com/jquery-3.6.1.min.js"
  integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ="
  crossorigin="anonymous"></script>

{{ $script ?? ''}}