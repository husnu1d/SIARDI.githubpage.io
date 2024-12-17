<x-app-layout>
    <x-slot name="header">
        File Dokumen
    </x-slot>
    <x-slot name="icon">
        fas fa-sharp fa-folder
    </x-slot>
    <section class=" rounded-lg shadow  bg-white">
        <div class="container">
            <table class="min-w-full table-auto ">

                <tbody class="bg-white divide-y divide-gray-200">
                    @if ($folderName == 'root')
                    <!-- Display list of subfolders within the uploadDate folder -->
                    <tr class="hover:bg-gray-100">
                        <td class="px-4 py-2 text-gray-900 flex">
                            <i class="fa-solid fa-arrow-turn-down"></i>
                            <h1 class="ml-2">
                                <a href="{{ route('documents')}}"> ... </a>
                            </h1>
                        </td>
                    </tr>
                    <tr class=" inline-flex w-full items-center justify-center hover:bg-gray-100">
                        @foreach ($folders as $folder)
                        <td class="px-4 py-2 flex-1 text-gray-900 flex ">
                            <svg class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 7h18a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2z">
                                </path>
                                <path d="M3 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                            <div class="flex items-center">
                                <a class="font-semibold flex"
                                    href="{{ route('documents.show', ['uploadDate' => $uploadDate, 'folderName' => basename($folder)]) }}">
                                    {{ basename($folder) }}
                                </a>
                            </div>
                        </td>
                        
                    </tr>
                    @endforeach
                    @else
                    <!-- Display subfolders and files inside the subfolder -->
                    @if (isset($subFolders) && count($subFolders) > 0)
                    <tr class="hover:bg-gray-100">
                        <td colspan="2" class="px-4 py-2 text-xl font-semibold text-gray-800">Subfolders</td>
                    </tr>
                    @foreach ($subFolders as $subFolder)
                    <tr class="hover:bg-gray-100">
                        <td class="px-4 py-2 text-gray-900">
                            {{ basename($subFolder) }}
                        </td>
                        <td class="px-4 py-2">
                            <a href="{{ route('documents.show', ['uploadDate' => $uploadDate, 'folderName' => basename($subFolder)]) }}"
                                class="text-blue-500 hover:underline">Buka</a>
                        </td>
                    </tr>
                    @endforeach
                    @endif

                    <!-- Files -->
                    @if (isset($files) && count($files) > 0)
                    <tr class="hover:bg-gray-100">
                        <td colspan="2" class="px-4 py-2 text-xl font-semibold text-gray-800 flex">
                            <i class="fa-solid fa-arrow-turn-down"></i>
                            <h1 class="text-base  ml-2 font-semibold cursor-pointer text-black hover:text-gray-700">
                                <a
                                    href="{{ route('documents.show', ['uploadDate' => $uploadDate, 'folderName' => 'root']) }}">
                                    .... </a>
                            </h1>
                        </td>
                        <td></td>
                    </tr>
                    @foreach ($files as $file)
                    <tr class="hover:bg-gray-100">
                        <td class="px-4 py-2 text-gray-900">
                            <i class="fa-solid fa-file-pdf"></i>
                            <a href="{{ asset('storage/Split/' . $uploadDate . '/' . $folderName . '/' . basename($file)) }}"
                                target="_blank"
                                class="text-[#1F2937] hover:text-blue-900 font-semibold">{{ basename($file) }}</a>
                        </td>
                        <td class="px-4 py-2">
                            <!-- Open the PDF directly in the browser -->


                        </td>
                    </tr>
                    @endforeach
                    @endif
                    @endif
                </tbody>
            </table>

        </div>
    </section>
</x-app-layout>