@php
$color = 'blue';
@endphp
<li class='hover:scale-105 ease-in-out duration-75 flex items-center p-4  border  rounded-md overflow-hidden shadow {{"bg-". $color . "-50"}}{{"border-". $color . "-500"}} '>
    <div  class="flex items-center gap-4">
                    <div class="rounded-full p-4 {{'bg' . $color . '-800'}} ">
                        <i :class="!isMobile ?'fa-2x':''" class="fa-light fa-messages-question text-white "></i>
                    </div>
                    <div class="flex flex-col {{'text' . $color . '-800'}} ">
                        <h3 class="text-xl font-bold ">{{$menutitle}}</h3>
                        <small class="text-sm font-thin mb-3">{{$menuQty}}</small>
                        <a href="" class="underline  text-sm font-bold">More Info</a>
                    </div>
                </div>
</li>