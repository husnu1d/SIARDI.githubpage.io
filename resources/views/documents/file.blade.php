<style>
.file-name {
    max-width: 100px;
    /* Sesuaikan dengan lebar yang diinginkan */
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.success{
    background-color: #06D001 !important;
}
.error{
    background-color: red !important;
    color:white !important;
}
</style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<x-app-layout>
   

    <x-slot name="header">
        Split Dokumen
    </x-slot>
    <x-slot name="icon">
        fas fa-sharp fa-split
    </x-slot>
    <!-- Trigger button to open modal -->
    <div
        class="w-full flex items-center h-[50vh] justify-center flex-col bg-gray-50 border-2 border-gray-300 shadow-md hover:bg-white  overflow-hidden rounded-mx ">
        <i class="fa-sharp-duotone fa-solid fa-cloud-arrow-up text-9xl text-[#18202F]"></i>
        <button type="button" class="bg-blue-900 hover:bg-[#18202F] text-white font-bold py-2 px-4 rounded"
            onclick="openModal()">
            Upload Document
        </button>
        <span class="block text-gray-400 font-semibold mt-5">Tambahkan File Yang Ingin Di Split</span>
        <div class="flex justify-center">
            <span class="block text-gray-400 font-semibold">Format yang di dukung :</span>
            <strong class="text-red-800 font-semibold ml-1"> .Pdf</strong>
        </div>

    </div>

    <!-- Modal -->
    <div id="uploadModal" class="fixed inset-0 flex items-center justify-center z-50 hidden bg-gray-900 bg-opacity-50">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full">
            <div class="mb-3 flex justify-between items-center">
                <h3 class="text-xl font-semibold">Upload Document</h3>
                <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>
            <form action="{{ route('documents.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <!-- File Upload with Drop Zone -->
                    <div class="border-2 border-dashed border-gray-300 p-3 text-center rounded-lg cursor-pointer"
                        id="dropZone" ondrop="handleDrop(event)" ondragover="handleDragOver(event)">
                        <label for="document" class="block text-sm font-medium text-gray-700 cursor-pointer">
                            <span class="text-base"><i class="fa-regular fa-file-plus"></i> CHOISE FILE</span>
                        </label>
                        <input type="file" name="document[]" id="document" accept=".pdf" class="hidden" multiple
                            required />
                    </div>
                </div>

                <!-- Display selected file names below -->
                <div id="fileNames" class="mt-2 text-gray-600"></div>

                <div id="uploadedFilesList" class="mt-4">
                    <!-- Dynamically added files will appear here -->
                </div>
                <div class="flex justify-end mt-6">
                    <button type="submit"
                        class="bg-blue-900 hover:bg-[#18202F] text-white font-bold py-2 px-4 rounded">
                        Upload
                    </button>
                    <button type="button" onclick="closeModal()"
                        class="ml-4 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Cancel
                    </button>
                </div>

@if (session()->has('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            confirmButtonText: 'OK',
            customClass: {
                confirmButton: 'success' // Apply your custom class here
            }
        });
    </script>
@endif

@if (session()->has('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            confirmButtonText: 'OK',
            customClass: {
                confirmButton: 'error' // Custom class for error if needed
            }
        });
    </script>
@endif

            </form>

        </div>
    </div>

    <!-- Include PDF.js Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>

    <script>
    // Open modal
    function openModal() {
        const modal = document.getElementById('uploadModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    // Close modal
    // Close modal and reset file input
    function closeModal() {
        const modal = document.getElementById('uploadModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');

        // Reset the file input
        const fileInput = document.getElementById('document');
        fileInput.value = ''; // Clear the file input

        // Clear the file names displayed
        const uploadedFilesList = document.getElementById('uploadedFilesList');
        uploadedFilesList.innerHTML = ''; // Clear the displayed file list
    }


    // Show file names when files are selected
    document.getElementById('document').addEventListener('change', function() {
        const fileInput = document.getElementById('document');
        const uploadedFilesList = document.getElementById('uploadedFilesList');

        // Display selected files' names
        const files = Array.from(fileInput.files);

        // Add each file to the list below
        files.forEach(async (file) => {
            const fileEntry = document.createElement('div');
            fileEntry.classList.add('flex', 'items-center', 'mb-2', 'justify-between',
                'bg-gray-100', 'p-2', 'rounded');

            const fileName = document.createElement('span');
            fileName.classList.add('text-gray-600',
                'file-name'); // Tambahkan kelas file-name untuk memotong teks panjang
            fileName.textContent = file.name;

            // Get the page count
            const pageCount = await getPdfPageCount(file);

            const fileInfo = document.createElement('span');
            fileInfo.classList.add('text-sm', 'text-gray-500');
            fileInfo.textContent = `${pageCount} pages | ${getFileSize(file)} KB`;

            const removeBtn = document.createElement('button');
            removeBtn.classList.add('text-gray-500', 'hover:text-red-600', 'font-bold');
            removeBtn.innerHTML = '&times;';
            removeBtn.onclick = function() {
                removeFile(fileEntry, fileInput, file);
            };

            fileEntry.appendChild(fileName);
            fileEntry.appendChild(fileInfo);
            fileEntry.appendChild(removeBtn); // Move "Remove" button to the end
            uploadedFilesList.appendChild(fileEntry);
        });
    });

    // Get PDF page count using PDF.js
    async function getPdfPageCount(file) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();

            // Read the file as an ArrayBuffer
            reader.onload = function(e) {
                const pdfData = new Uint8Array(e.target.result);

                // Use PDF.js to load the PDF
                pdfjsLib.getDocument(pdfData).promise.then(function(pdf) {
                    resolve(pdf.numPages); // Return the number of pages
                }).catch(function(error) {
                    reject(error); // If there's an error, reject the promise
                });
            };

            // Read the file as an ArrayBuffer
            reader.readAsArrayBuffer(file);
        });
    }

    // Get file size in KB
    function getFileSize(file) {
        return (file.size / 1024).toFixed(2); // Size in KB
    }

    // Remove file entry from the list
    function removeFile(fileEntry, fileInput, file) {
        const fileIndex = Array.from(fileInput.files).indexOf(file);
        const newFileList = Array.from(fileInput.files).filter((_, index) => index !== fileIndex);

        // Update the file list in the input
        const dataTransfer = new DataTransfer();
        newFileList.forEach(newFile => dataTransfer.items.add(newFile));
        fileInput.files = dataTransfer.files;

        fileEntry.remove();
    }

    // Handle file drop
    function handleDrop(event) {
        event.preventDefault();
        const fileInput = document.getElementById('document');
        const uploadedFilesList = document.getElementById('uploadedFilesList');

        const files = event.dataTransfer.files;
        if (files.length > 0) {
            // Add each file to the input
            Array.from(files).forEach(async (file) => {
                const fileEntry = document.createElement('div');
                fileEntry.classList.add('flex', 'items-center', 'mb-2', 'justify-between', 'bg-gray-100',
                    'p-2', 'rounded');

                const fileName = document.createElement('span');
                fileName.classList.add('text-gray-600');
                fileName.textContent = file.name;

                // Get the page count
                const pageCount = await getPdfPageCount(file);

                const fileInfo = document.createElement('span');
                fileInfo.classList.add('text-sm', 'text-gray-500');
                fileInfo.textContent = `${pageCount} pages | ${getFileSize(file)} KB`;

                const removeBtn = document.createElement('button');
                removeBtn.classList.add('text-gray-500', 'hover:text-red-600', 'font-bold');
                removeBtn.innerHTML = '&times;';
                removeBtn.onclick = function() {
                    removeFile(fileEntry, fileInput, file);
                };

                fileEntry.appendChild(fileName);
                fileEntry.appendChild(fileInfo);
                fileEntry.appendChild(removeBtn); // Move "Remove" button to the end
                uploadedFilesList.appendChild(fileEntry);
            });
        }
    }

    // Allow drag-and-drop
    function handleDragOver(event) {
        event.preventDefault();
    }
    </script>
</x-app-layout>