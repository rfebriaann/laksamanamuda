{{-- <div>
    <div class="p-4 rounded-lg dark:border-gray-700">
             <div class="bg-cover rounded-xl" style="background-image: url(https://images.unsplash.com/photo-1616400619175-5beda3a17896?q=80&w=2874&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D); background-position: center;" class="">
                <div class="bg-gradient-to-r from-black p-10 rounded-xl">
                    <h1 class="font-bold text-4xl text-white">🧐 Halaman Event </h1>
                </div>
             </div>
         <div>
            <section class="mt-4">
                <div class="max-w-screen-xl p-2 mx-auto lg:px-1">
                    <div class="bg-white w-full relative sm:rounded-lg overflow-hidden">
                        <div class="flex items-center justify-between d p-4">
                            <div class="flex">
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                            fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <input  type="text"
                                        wire:model.live = 'search'
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 "
                                        placeholder="Search" required="">
                                </div>
                            </div>
                            <div class="text-left ml-4 flex space-x-3">
                                <a href="{{route('events.create')}}" class="button-tambah text-white bg-gray-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-gray-700 dark:hover:bg-gray-900 focus:outline-none dark:focus:ring-gray-800" type="button" data-drawer-target="drawer-right-example" data-drawer-show="drawer-right-example" data-drawer-placement="right" aria-controls="drawer-right-example">
                                Tambah data
                                </a>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-md text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                                    <tr>
                                        <th scope="col" class="px-4 py-3">#</th>
                                        <th scope="col" class="px-4 py-3">Event Name</th>
                                        <th scope="col" class="px-4 py-3">Jumlah Seat</th>
                                        <th scope="col" class="px-4 py-3">Date </th>
                                        <th scope="col" class="px-4 py-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($events as $event)
                                    <tr class="border-b dark:border-gray-200">
                                            <td class="px-4 py-3">{{$i++}}</td>
                                            <td class="px-4 text-left text-black py-3"><span class="text-md  leading-8 font-semibold p-1 rounded-2xl px-4">{{ $event->name }}</span></td>
                                            <td class="px-4 text-left text-black py-3"><span class="text-md  leading-8 font-semibold p-1 rounded-2xl px-4">{{ $event->total_seats }}</span></td>
                                            <td class="px-4 text-left text-black py-3"><span class="text-md  leading-8 font-semibold p-1 rounded-2xl px-4">{{ $event->date }}</span></td>
                                            <td scope="row" class="px-1 py-3 flex flex-col items-start justify-center text-gray-900 font-semibold"><a class="" href="{{ route('events.edit', $event->id) }}">Edit ✏️</a> <button wire:click="destroy({{$event->id}})">Hapus 🗑️</button></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="py-4 px-3">
                            <div class="flex ">
                                <div class="flex space-x-4 items-center mb-3">
                                    <label class="w-32 text-sm font-medium text-gray-900">Per Page</label>
                                    <select
                                        wire:model.live='perPage' 
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                        <option value="10">10</option>
                                        <option value="20">20</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
         </div>
     </div>
</div> --}}
