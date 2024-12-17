<x-app-layout>
    <x-slot name="header">
        File Dokumen
    </x-slot>
    <x-slot name="icon">
        fas fa-sharp fa-folder
    </x-slot>
    <section class="rounded-lg shadow bg-white">
        <div class="container">
            <table class="min-w-full table-auto">
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Files -->
                    @if (isset($files) && count($files) > 0)
                        <tr class="hover:bg-gray-100">
                            <td colspan="2" class="px-4 py-2 text-xl font-semibold text-gray-800 flex">
                                <i class="fa-solid fa-arrow-turn-down"></i>
                                <h1 class="text-base ml-2 font-semibold cursor-pointer text-black hover:text-gray-700">
                                    <a href="{{ route('folders.show', ['id' => $folder->id]) }}">
                                        {{ $folder->folder_document }}
                                    </a>
                                </h1>
                            </td>
                            <td></td>
                        </tr>
                        @foreach ($files as $file)
                            <tr class="hover:bg-gray-100">
                                <td class="px-4 py-2 text-gray-900">
                                    <i class="fa-solid fa-file-pdf"></i>
                                    <a href="{{ asset('storage/' . $file) }}" target="_blank" class="text-[#1F2937] hover:text-blue-900 font-semibold">
                                        {{ basename($file) }}
                                    </a>
                                </td>
                                <td class="px-4 py-2">
                                    <!-- Open the PDF directly in the browser -->
                                    <!-- Optionally, you can add additional actions here -->
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="px-4 py-2 text-gray-600">Tidak ada file ditemukan.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </section>
</x-app-layout>
