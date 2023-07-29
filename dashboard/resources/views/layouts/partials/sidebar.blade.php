<x-maz-sidebar :href="route('dashboard')" :logo="asset('images/logo/baltic_brand.png')">

    <!-- Add Sidebar Menu Items Here -->
    <x-maz-sidebar-item name="Dashboard" :link="route('dashboard')" icon="fa bi-grid-fill"></x-maz-sidebar-item>
    @if (Auth::user()->role == 1 || Auth::user()->role == 2)
        <x-maz-sidebar-item name="Users" :link="url('users')" icon="fa bi-people-fill"></x-maz-sidebar-item>
    @endif
    @php
        $jmldevice = countDevice();
    @endphp
    <x-maz-sidebar-item name="Device" :link="url('devices')" icon="fa bi-phone-fill"></x-maz-sidebar-item>
    {{-- <x-maz-sidebar-item name="Contact ({{ countContact() }})" :link="url('kontak')" icon="fa bi-person-lines-fill"></x-maz-sidebar-item> --}}
    {{-- <x-maz-sidebar-item name="Contact 2 ({{ countContact(2) }})" :link="url('kontak2')" icon="fa bi-person-lines-fill"></x-maz-sidebar-item> --}}
    @if ($jmldevice > 0)
        {{-- <x-maz-sidebar-item name="Sending" :link="url('kirim-pesan')" icon="fa bi-send-fill"></x-maz-sidebar-item> --}}
        {{-- <x-maz-sidebar-item name="Inbox (<span class='counting-pesan mx-0'>{{ countPesanMasuk() }}</span>)" :link="url('pesan-masuk')" icon="fa bi-envelope-fill"></x-maz-sidebar-item> --}}
        {{-- <x-maz-sidebar-item name="Broadcast" :link="url('blast')" icon="fa bi-chat-dots"></x-maz-sidebar-item>
        <x-maz-sidebar-item name="Auto Reply" :link="url('balas-otomatis')" icon="fa bi-robot"></x-maz-sidebar-item> --}}
    @endif
        {{-- <x-maz-sidebar-item name="Tags" :link="url('tags')" icon="fa bi-tags"></x-maz-sidebar-item> --}}
    @if (Auth::user()->role == 2)
        {{-- <x-maz-sidebar-item name="Proxy" :link="url('proxy')" icon="fa bi-gear"></x-maz-sidebar-item> --}}
    @endif
    @if (Auth::user()->role == 1)
        <x-maz-sidebar-item name="Proxy" :link="url('proxy')" icon="fa bi-shield-shaded"></x-maz-sidebar-item>
        {{-- <x-maz-sidebar-item name="IP Whitelist" :link="url('ipwhitelist')" icon="fa bi-shield-slash-fill"></x-maz-sidebar-item>
        <x-maz-sidebar-item name="Config" :link="url('config')" icon="fa bi-gear"></x-maz-sidebar-item> --}}
    @endif

</x-maz-sidebar>
