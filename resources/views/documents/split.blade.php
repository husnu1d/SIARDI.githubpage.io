        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Form upload dokumen -->
                    <form action="{{ route('documents.upload') }}" method="" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <label for="document" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Dokumen (Word/PDF)</label>
                            <input type="file" name="document" id="document" required class="mt-1 block w-full">
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Upload
                            </button>
                        </div>
                    </form>

                    @if (session('success'))
                        <div class="mt-4 text-green-500">
                            {{ session('success') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>