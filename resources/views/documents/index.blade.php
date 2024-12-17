<x-app-layout>
    <x-slot name="header">
        Split Dokumen
    </x-slot>
    <x-slot name="icon">
        fas fa-sharp fa-split
    </x-slot>

    <div class="flex items-center justify-between ">
        <form action="{{ route('documents.upload') }}" method="POST" enctype="multipart/form-data"
            class=" mb-3 w-full min-h-96 ">
            @csrf
            <div class="flex w-full justify-between">
                <div class=" duration-75 transition-all ease-in-out hover:scale-95 space-x-4 hover:bg-red-400 bg-red-500  text-white  p-3 text-center rounded-lg cursor-pointer"
                    id="dropZone" ondrop="handleDrop(event)" ondragover="handleDragOver(event)">
                    <label for="document" class="block text-sm font-medium cursor-pointer">
                        <span class="text-base"><i class="fa-regular fa-file-plus"></i> Tambahkan File</span>
                    </label>
                    <input type="file" name="document[]" id="document" accept=".pdf" class="hidden" multiple required />
                </div>
                <button type="submit"
                    class="duration-75 transition-all ease-in-out hover:scale-95 bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Upload
                </button>
            </div>

            <!-- Display selected file names below -->

            <div id="uploadedFilesList" class="mt-4 flex flex-col auto-rows-[20em]">
                <table class="min-w-full text-sm text-left text-gray-700 bg-white">
                    <thead class="bg-[#1F2937] text-white">
                        <tr>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider"> file preview</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider"> Page</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider"> Nama Folder</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider"> aksi</th>
                        </tr>
                    </thead>
                    <tbody id="test">
                    </tbody>
                </table>
                <!-- Dynamically added files will appear here -->
            </div>
            

        </form>
    </div>



    <!-- Include PDF.js Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.getElementById('document').addEventListener('change', async function () {
    const fileInput = document.getElementById('document');
    const mi = document.getElementById('test');

    const files = Array.from(fileInput.files);

    // Mengambil data folder via AJAX
    async function fetchFolders() {
        try {
            const response = await fetch('{{ route('documents.ajax') }}', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            });

            if (!response.ok) {
                throw new Error('Failed to fetch folders');
            }

            const data = await response.json();
            return data.folders; // Mengambil daftar folder dari response
        } catch (error) {
            console.error('Error fetching folders:', error);
            return [];
        }
    }
    function populateDropdown(folders) {
    if (!Array.isArray(folders) || folders.length === 0) {
        console.warn("No folders data available.");
        return [];
    }

    return folders.map(folder => {
        const option = document.createElement('option');
        option.value = folder.id || '';  // default empty string if id is undefined
        option.textContent = folder.folder_document || 'No Name';  // default text if folder_document is undefined
        return option;
    });
}

    

    // Looping untuk memproses setiap file yang dipilih
    files.forEach(async (file) => {
        // Split PDF menjadi halaman (fungsi asumsinya sudah ada)
        const pages = await splitPdf(file);
        const folders = await fetchFolders(); // Ambil data folder via AJAX

        pages.forEach((page, index) => {
            const uploadedFilesList = document.createElement('tr');

            // Membuat elemen tabel untuk gambar halaman
            const pageEntry = document.createElement('td');
            pageEntry.classList.add('aspect-square', 'p-2', 'rounded', 'w-40',
                'flex', 'items-center', 'overflow-hidden');
            const pageImage = document.createElement('img');
            pageImage.src = page; // Gambar halaman
            pageImage.alt = `Page ${index + 1}`;
            pageImage.classList.add('rounded', 'w-full', 'h-auto');

            // Membuat elemen tabel untuk informasi halaman
            const pageInfo = document.createElement('p');
            pageInfo.textContent = `Page ${index + 1}`;
            pageInfo.classList.add('text-gray-800', 'text-lg');

            // Membuat elemen dropdown
            const Dropdown = document.createElement('select');
            Dropdown.classList.add('border-gray-300', 'rounded-md', 'p-2', 'w-full');

            // Menambahkan opsi folder ke dropdown
            const folderOptions = populateDropdown(folders);
            folderOptions.forEach(option => {
                Dropdown.appendChild(option);
            });

            // Membuat tombol hapus
            const removeBtn = document.createElement('button');
            removeBtn.classList.add('bg-red-500', 'flex', 'items-center', 'px-4',
                'py-2', 'gap-2', 'rounded-xl', 'text-white',
                'hover:text-red-600', 'font-bold');
            removeBtn.innerText = 'Delete';
            removeBtn.onclick = function () {
                removeFile(uploadedFilesList, fileInput, file);
            };

            // Membuat elemen tabel untuk dropdown dan tombol
            const entryDropdown = document.createElement('td');
            entryDropdown.classList.add('px-6', 'py-3');
            const btnEntry = document.createElement('td');
            btnEntry.classList.add('px-6', 'py-3');
            const infoEntry = document.createElement('td');
            infoEntry.classList.add('px-6', 'py-3');

            // Menyusun elemen ke dalam tabel
            pageEntry.appendChild(pageImage);
            infoEntry.appendChild(pageInfo);
            entryDropdown.appendChild(Dropdown);
            btnEntry.appendChild(removeBtn);

            uploadedFilesList.appendChild(pageEntry);
            uploadedFilesList.appendChild(infoEntry);
            uploadedFilesList.appendChild(entryDropdown);
            uploadedFilesList.appendChild(btnEntry);

            // Menambahkan ke elemen utama
            mi.appendChild(uploadedFilesList);
        });
    });
});
async function splitPdf(file) {
        const reader = new FileReader();

        return new Promise((resolve, reject) => {
            reader.onload = async function(e) {
                const pdfData = new Uint8Array(e.target.result);

                // Load PDF menggunakan PDF.js
                const pdf = await pdfjsLib.getDocument(pdfData).promise;

                const pages = [];
                for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                    const page = await pdf.getPage(pageNum);

                    // Render halaman menjadi gambar (canvas)
                    const viewport = page.getViewport({
                        scale: 1.5
                    });
                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');

                    canvas.width = viewport.width;
                    canvas.height = viewport.height;

                    await page.render({
                        canvasContext: context,
                        viewport: viewport
                    }).promise;

                    // Simpan URL gambar dari halaman
                    pages.push(canvas.toDataURL('image/png'));
                }

                resolve(pages); // Kembalikan array gambar halaman
            };

            reader.onerror = reject;
            reader.readAsArrayBuffer(file);
        });
    }

    function removeFile(fileEntry, fileInput, file) {
        const fileIndex = Array.from(fileInput.files).indexOf(file);
        const newFileList = Array.from(fileInput.files).filter((_, index) => index !== fileIndex);

        // Update the file list in the input
        const dataTransfer = new DataTransfer();
        newFileList.forEach(newFile => dataTransfer.items.add(newFile));
        fileInput.files = dataTransfer.files;

        fileEntry.remove();
    }
    </script>
    </x-app-layout>