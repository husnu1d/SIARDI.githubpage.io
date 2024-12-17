<x-app-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>
    <x-slot name="icon">
        fas fa-sharp fa-home
    </x-slot>
    <section class="my-8 h-full" x-data="modalHandler">
        <div>
            <div class="flex items-center justify-between">
                <!-- Tombol Tambah Kategori -->
                <button class="bg-gray-800 text-white flex items-center px-4 py-2 rounded-md gap-4"
                    @click="openModal('add')">
                    <i class="fas fa-solid fa-plus"></i>
                    <span>Tambah Kategori</span>
                </button>

                <!-- Form Pencarian -->
                <form method="GET" action="{{ route('dashboard') }}" class="flex items-center group justify-end">
                    <label for="searchingNama" class="sr-only">Cari kategori</label>
                    <input type="search" name="search" id="searchingNama" placeholder="Cari kategori disini"
                        value="{{ request('search') }}" class="w-96 bg-gray-300 rounded-lg overflow-hidden">
                    <button type="submit" class="ml-2 px-4 py-2 bg-gray-800 text-white rounded-md">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <!-- Modal Tambah Kategori -->
            <form method="POST" action="{{ route('kategori.store') }}" x-show="AddModalOpen"
                class="fixed inset-0 bg-gray-800 bg-opacity-75 flex justify-center items-center z-50"
                @click.away="closeModal('add')">
                @csrf
                <div class="bg-white p-6 rounded-lg w-1/3">
                    <h3 class="text-xl font-bold mb-6"><i class="fas fa-sharp fa-plus"></i> Input Kategori</h3>
                    <input type="text" name="nama_kategori" placeholder="Nama kategori"
                        class="w-full p-2 border border-gray-300 rounded-md mb-4">
                    <div class="flex justify-end gap-4">
                        <button type="button" @click="closeModal('add')"
                            class="border bg-red-500 text-white px-4 py-2 rounded-md">Cancel</button>
                        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md"><i
                                class="fas fa-sharp fa-paper-plane-top"></i> Kirim</button>
                    </div>
                </div>
            </form>

            <!-- Modal Edit Kategori -->
            <form method="POST" :action="`{{ url('kategori/update') }}/${currentEditId}`" x-show="EditModalOpen"
                class="fixed inset-0 bg-gray-800 bg-opacity-75 flex justify-center items-center z-50"
                @click.away="closeModal('edit')">
                @csrf
                @method('PUT')
                <div class="bg-white p-6 rounded-lg w-1/3">
                    <h3 class="text-xl font-bold mb-6"><i class="fas fa-sharp fa-edit"></i> Edit Kategori</h3>
                    <input type="text" name="nama_kategori" placeholder="Nama kategori"
                        class="w-full p-2 border border-gray-300 rounded-md mb-4" :value="currentEditName">
                    <div class="flex justify-end gap-4">
                        <button type="button" @click="closeModal('edit')"
                            class="border bg-red-500 text-white px-4 py-2 rounded-md">Cancel</button>
                        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md"><i
                                class="fas fa-sharp fa-paper-plane-top"></i> Update</button>
                    </div>
                </div>
            </form>


        </div>

        <!-- Flash Message -->
        <div x-data="{ showMessage: false, message: '' }" x-init="
            @if (session('success'))
                showMessage = true;
                message = '{{ session('success') }}';
                setTimeout(() => showMessage = false, 3000);
            @endif
        ">
            <template x-if="showMessage">
                <div x-show="showMessage" class="bg-green-500 my-8 text-white p-2 rounded">
                    <p x-text="message"></p>
                </div>
            </template>
        </div>

        <!-- Daftar Kategori -->
        <ul
            class="grid grid-cols-3 mt-6 gap-8 md:grid-cols-5  bg-white border border-gray-300 p-8 shadow-lg w-full">
            @foreach ($kategori as $kategoris)
            <li x-data="{ hover: false }" @mouseenter="hover = true" @mouseleave="hover = false"
                class="hover:scale-105 ease-in-out duration-75 flex flex-col justify-between p-4 bg-white border border-gray-800 rounded-md overflow-hidden">
                <div class="flex flex-1 items-center gap-4">
                    <div class="rounded-full p-4 bg-gray-800">
                        <i class="fa-light fa-messages-question text-white"></i>
                    </div>
                    <div class="flex flex-col text-gray-800">
                        <!-- Menggunakan Alpine.js untuk mengontrol kondisi terbuka/tidaknya teks -->
                        <h3 style="width: 8ch; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"
                            class="font-semibold text-balance mt-6 cursor-pointer">
                            {{ $kategoris['nama_kategori'] }}
                        </h3>
                        <small class="text-sm font-thin mb-3">10 File</small>
                    </div>

                    <!-- <div class="flex flex-col text-gray-800 ">
                        <h3 class="font-semibold text-balance mt-2 " style="width: 8ch;white-space: nowrap;overflow: hidden;text-overflow: ellipsis; ">
                            {{ $kategoris['nama_kategori'] }}
                        </h3>
                        <small class="text-sm font-thin mb-3">10 File</small>
                    </div> -->

                </div>
                <hr class="border border-black mt-3 mb-2 w-full">
                <a href="#" class="text-center hover:underline flex justify-center px-4 py-2">More Info</a>
                <div :class="hover ? ' block opacity-100 translate-x-0' : 'hidden opacity-0 translate-x-10'"
                    class="transition-all absolute right-0 top-0 flex items-center duration-500 ease-in-out">
                    <button @click="openModal('edit', {{ $kategoris->id }}, '{{ $kategoris->nama_kategori }}')"
                        class="border border-gray-800 text-gray-800 px-2 py-1 hover:bg-gray-500 hover:text-white transition-all duration-75">Edit</button>
                    <form method="POST" action="{{ route('kategori.delete', $kategoris->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="text-gray-800 border border-gray-800 hover:bg-red-500 hover:text-white px-2 py-1  transition-all duration-75 rounded-[0_0_20px_0]">
                            <i class="fas fa-sharp fa-xmark"></i>
                        </button>
                    </form>
                </div>
            </li>
            @endforeach
        </ul>
    </section>
</x-app-layout>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('modalHandler', () => ({
        AddModalOpen: false,
        EditModalOpen: false,
        currentEditId: null,
        currentEditName: '',
        openModal(type, id = null, name = '') {
            if (type === 'add') {
                this.AddModalOpen = true;
            } else if (type === 'edit') {
                this.EditModalOpen = true;
                this.currentEditId = id;
                this.currentEditName = name;
            }
        },
        closeModal(type) {
            if (type === 'add') {
                this.AddModalOpen = false;
            } else if (type === 'edit') {
                this.EditModalOpen = false;
                this.currentEditId = null;
                this.currentEditName = '';
            }
        },
    }));
});
</script>