<x-app-layout>
    <x-slot name="header">
        File Dokumen
    </x-slot>
    <x-slot name="icon">
        fas fa-sharp fa-folder
    </x-slot>
    <section class="mt-4 w-full" x-data="modalHandler">
        <div class="flex items-center justify-between mb-3">
            <div x-show="AddModalOpen"
                class="fixed inset-0 bg-gray-800 bg-opacity-75 flex justify-center items-center z-50">
                <div class="bg-white rounded-lg w-1/3 p-6" @click.away="closeModal('add')">
                    <h5 class="text-lg font-bold mb-4">Tambah Berkas</h5>
                    <form action="{{ route('folders.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="font-bold">Nama berkas</label>
                            <input type="text"
                                class="form-control @error('folder_document') is-invalid @enderror w-full border-gray-300 rounded-md p-2"
                                name="folder_document" value="{{ old('folder_document') }}">
                            @error('folder_document')
                            <div class="text-red-500 mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="font-bold">Kategori</label>
                            <select class="w-full border-gray-300 rounded-md p-2" name="id_kategori">
                                <option value="" disabled selected>Pilih Kategori</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('id_kategori') == $category->id ? 'selected' : '' }}>
                                    {{ $category->nama_kategori }}</option>
                                @endforeach
                            </select>
                            @error('id_kategori')
                            <div class="text-red-500 mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <input type="hidden" name="id_author" value="{{ Auth::user()->id }}">
                            <input type="text" class="form-control @error('author') is-invalid @enderror" name="author"
                                value="{{ Auth::user()->name }}" hidden>
                        </div>

                        <div class="mb-3">
                            <label class="font-bold">Keterangan</label>
                            <textarea
                                class="w-full border-gray-300 rounded-md p-2 @error('keterangan') is-invalid @enderror"
                                name="keterangan" rows="5" required>{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                            <div class="text-red-500 mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="flex justify-end mt-4">
                            <button type="button" @click="closeModal('add')"
                                class="border bg-red-500 text-white px-4 py-2 rounded-md">Close</button>
                            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md"> Kirim</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tombol Tambah Kategori -->
            <button class="bg-gray-800 text-white flex items-center px-4 py-2 rounded-md gap-4"
                @click="openModal('add')">
                <i class="fas fa-solid fa-plus"></i>
                <span>Tambah Berkas</span>
            </button>

            <!-- Form Pencarian -->
            <form method="GET" action="{{ route('folders') }}" class="flex items-center group justify-end">
                <label for="searchingNama" class="sr-only">Cari kategori</label>
                <input type="search" name="search" id="searchingNama" placeholder="Cari kategori disini"
                    value="{{ request('search') }}" class="w-96 bg-gray-300 rounded-lg overflow-hidden">
                <button type="submit" class="ml-2 px-4 py-2 bg-gray-800 text-white rounded-md">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>





        <!-- View folders -->
        <table class="min-w-full text-sm text-left text-gray-700 bg-white" id="folderTable">
            <thead class="bg-[#1F2937] text-white">
                <tr>
                    <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider">Nama berkas</th>
                    <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider">Tanggal Publish</th>
                    <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider">Keterangan</th>
                    <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider">Kategori</th>
                    <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider">Author</th>
                    <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-300">
                @foreach ($folders as $folder)
                <tr class="font-semibold text-[#1F2937]   folder-row" data-folder-date="{{ $folder['folder_name'] }}">
                    <td class="px-6 py-3">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 7h18a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2z">
                                </path>
                                <path d="M3 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                            <a href="{{ route('folders.show', ['id' => $folder->id]) }}">
                                {{ $folder->folder_document }}
                            </a>
                        </div>
                    </td>
                    <td class="px-6 py-3">{{ \Carbon\Carbon::parse($folder['updated_at'])->format('d/m/Y H:i:s') }}</td>
                    <td class="px-6 py-3">{{$folder['keterangan']}}</td>
                    <td class="px-6 py-3">{{ $folder->kategori->nama_kategori }}</td> <!-- Menampilkan nama kategori -->
                    <td class="px-6 py-3">{{$folder['author']}}</td>
                    <td class="px-6 py-3">
                        <button class="text-gray-800 flex items-center px-4 py-2 gap-2 "><i class="fas fa-sharp fa-pen"
                                data-toggle="modal" data-target="#editFolderModal{{$folder->id}}"></i> Edit</button>
                        <button class="bg-red-500 flex items-center  px-4 py-2 gap-2 rounded-xl text-white"><i
                                class="fas fa-sharp fa-trash"></i> Hapus</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </section>
</x-app-layout>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('modalHandler', () => ({
        AddModalOpen: false,
        EditModalOpen: false,
        deleteModalOpen: false,
        currentEditId: null,
        currentEditName: '',
        currentDeleteId: null,

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
            } else if (type === 'delete') {
                this.deleteModalOpen = false;
                this.currentDeleteId = null;
            }
        },

        openDeleteModal(id) {
            this.currentDeleteId = id;
            this.deleteModalOpen = true;
        }
    }));
});
</script>