<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
        <!-- Fonts -->
         <link rel="icon" href="{{ asset('storage/logo-1.png') }}" type="image/png">
        <link rel="preconnect" href="https://fonts.bunny.net">
         <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
                integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
                crossorigin="anonymous">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://atugatran.github.io/FontAwesome6Pro/css/all.min.css"/>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-white">
      

        {{-- <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div> --}}
        <a href="https://poltek.stialanmakassar.ac.id/" class="fixed flex flex-col space-y-4 items-center bottom-4 right-12  opacity-50  hover:opacity-100 duration-75 ease-in-out transition-all">
          <span class="text-blue-500">Sponsored by :</span>
          <img src="{{asset('storage/stia.png')}}" alt="" type="png" class="w-20 h-20">
        </a>
        <div
        class="flex w-full  h-screen overflow-hidden"
        x-data="{ isMobile: window.innerWidth <= 925, sidenavOpen: false, showModal: false, modalBantuan: false}"
        x-init="() => window.addEventListener('resize', () => isMobile = window.innerWidth <= 925)"
      >
      <template x-if="modalBantuan ===  true">
        <div x-show="modalBantuan" class="fixed inset-0 flex items-center justify-center z-50 bg-gray-800 bg-opacity-50">
          <div class="bg-white rounded-lg p-6 max-w-lg w-full">
              <div class="flex flex-col text-gray-800 justify-between items-center">
                <div class="flex flex-col">
                                    <h3 class="text-xl font-bold"><i class="fas fa-solid fa-question mr-8"></i> FAQ</h3>
                                    <div class="m-4 text-pretty">
                                      <p class="text-sm py-2 font-semibold">1.	 Bagaimana cara mengubah kata sandi?Masuk ke pengaturan akun dan pilih opsi "Ubah Kata Sandi".</p>
                                      <p  class="text-sm font-semibold">2. Apa yang harus dilakukan jika berkas tidak dapat diunggah?
                    Pastikan ukuran file tidak melebihi batas dan format file didukung oleh sistem.
                    .</p>

                                    </div>
                                    <h3 class="text-xl font-bold"><i class="fas fa-solid fa-check-to-slot mr-8"></i> Pemecahan Masalah
</h3>
                                    <div class="m-4 text-pretty">
                                      <p class="text-sm py-2 font-semibold">1.	 Kesalahan Login: Periksa username dan password Anda. Jika lupa kata sandi, klik "Lupa Kata Sandi".</p>
                                      <p  class="text-sm font-semibold">2. Berkas Tidak Ditemukan: Pastikan Anda telah memilih kategori atau filter pencarian yang tepat.

                    .</p>

                                    </div>

                                    <div class="bg-gray-800 text-white px-4 py-2 text-pretty">
                                      <span>Jika anda mengalami kendala atau masalah silahkan <span>hubungi tim IT</span> </span>
                                    </div>

                                    </div>

                </div>
                  <button @click="modalBantuan = false" class="text-gray-500 hover:text-gray-700">&times;</button>
              </div>
              
          </div>
        </div>
        </template>
      @include('layouts.aside')
      <div class="flex   w-full h-full overflow-y-auto p-4 bg-gray-100 box-border shadow-lg rounded-lg" >
        <div class=" flex-1 flex flex-col h-fit ">
          <header class="flex flex-1  relative">
            <nav class="flex-1 h-fit px-4">
              <ul
                class="flex items-center justify-between  text-center"
                x-data="{openSettings:false}"
              >
                <li>
                  <button
                    x-show="isMobile && !sidenavOpen"
                    @click="sidenavOpen = true"
                    class="text-white bg-slate-700 hover:bg-slate ease-in-out duration-100 transition-all hover:scale-110 px-3 py-1 rounded-full shadow-lg"
                  >
                    <i class="fas fa-bars" aria-hidden="true"></i>
                    <span class="sr-only">Open Navigation</span>
                  </button>
                </li>
              </ul>
            </nav>
          </header>
          @isset($header)
          <div class="px-6 py-8 basis-[44px] flex flex-col  bg-white shadow-md rounded-md  ">
            <div class="inline-flex space-x-4 mb-2 text-4xl text-gray-800 font-bold ">
              <i class="{{$icon}}"></i>
              <h1>{{$header}}</h1>
            </div>
            @if(Route::currentRouteName() == 'dashboard')
                <small class="text-lg text-balance font-thin">Selamat datang di Aplikasi SIARDIKU ( Sistem Informasi Arsip Digitalku )</small>
            @endif

          </div>
          @endisset
          <main class="mt-6  flex-1 px-6">
            {{$slot}}
          </main>
        </div>
    </div>
    
    </body>
</html>