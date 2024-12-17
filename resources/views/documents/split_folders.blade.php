<x-app-layout>
    <x-slot name="header">
        File Dokumen
    </x-slot>
    <x-slot name="icon">
        fas fa-sharp fa-folder
    </x-slot>
    <section class="mt-4 w-full h-screen overflow-hidden">
        @if (empty($folderData))
            <p class="text-gray-600">Tidak ada folder split yang ditemukan.</p>
        @else
        <table class="min-w-full text-sm text-left text-gray-800 " id="folderTable">
            <thead class="bg-[#1F2937] text-white">
                <tr>
                    <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wider">Folder Name</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-300">
                @foreach ($folderData as $folder)
                    <tr class="font-semibold text-[#1F2937] cursor-pointer hover:bg-gray-200 folder-row" data-folder-date="{{ $folder['folder_name'] }}">
                        <td class="px-6 py-3">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 7h18a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2z"></path>
                                    <path d="M3 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                                <a href="{{ route('documents.show', ['uploadDate' => $folder['folder_name'], 'folderName' => 'root']) }}">
                                    {{ $folder['folder_name'] }}
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </section>

<script>
    // Fungsi untuk menyaring folder berdasarkan tanggal
   document.getElementById('filterForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Mencegah form dari reload

    // Ambil nilai tanggal mulai dan akhir
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;

    // Fungsi untuk mengubah tanggal dalam format 'DD-MM-YYYY' menjadi objek Date
    
    function convertToDate(dateStr) {
    const [day, month, year] = dateStr.split('-'); // Mengambil bagian tanggal, bulan, dan tahun
    return new Date(`${year}-${month}-${day}T00:00:00`); // Menambahkan waktu default agar tetap valid
    }


    const startDateObj = startDate ? convertToDate(startDate) : null;
    const endDateObj = endDate ? convertToDate(endDate) : null;

    const rows = document.querySelectorAll('.folder-row');
    
    rows.forEach(row => {
        const folderDate = row.getAttribute('data-folder-date'); // Mendapatkan tanggal dari atribut data
        const [day, month, year] = folderDate.split('-'); // Pisahkan tanggal menjadi bagian-bagian
        const folderDateObj = new Date(`${year}-${month}-${day}`); // Ubah menjadi objek Date

        // Periksa apakah tanggal folder dalam rentang tanggal yang dipilih
        const isInRange = (!startDateObj || folderDateObj >= startDateObj) && 
                          (!endDateObj || folderDateObj <= endDateObj);

        if (isInRange) {
            row.style.display = ''; // Tampilkan baris jika cocok
        } else {
            console.log(row)
            row.style.display = 'none'; // Sembunyikan baris jika tidak cocok
        }
    });
});

</script>

</x-app-layout>
