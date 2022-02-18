<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inicio') }}
        </h2>
    </x-slot>

    <div class="flex flex-col justify-center items-center py-12">
        <form action="{{route('dashboard')}}" method="GET">
            <div class="relative mt-1 w-96">
                <div class="flex">
                    <select name="comicOrHero" id="comicOrHero" class="-full pl-3 pr-10 py-2 border-2 border-gray-200 rounded-xl hover:border-gray-300 focus:outline-none focus:border-blue-500 transition-color">
                        <option value="hero">Heroe</option>
                        <option value="comic">Comic</option>
                    </select>
                    <input name="inputValue" type="text" id="inputValue" class="w-full pl-3 pr-10 py-2 border-2 border-gray-200 rounded-xl hover:border-gray-300 focus:outline-none focus:border-blue-500 transition-colors" placeholder="Busca un heroe o comic...">
                    <button type="submit" class="block w-7 h-7 text-center text-xl leading-0 absolute top-2 right-2 text-gray-400 focus:outline-none hover:text-gray-900 transition-colors"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                      </svg></button>
                </div>
            </div>
        </form>
    </div>

    {{-- Sin resultados --}}

    {{-- <div class="flex flex-col justify-center items-center py-12">
        <span class="text-gray-400">No hay resultados :(</span>
    </div> --}}

    <div class="container my-12 mx-auto px-4 md:px-12">
        @if (count($data) != 0)
        <div class="flex flex-wrap -mx-1 lg:-mx-4">

            @foreach ($data as $item)

            <!-- Column -->
            <div class="my-1 px-1 w-full md:w-1/2 lg:my-4 lg:px-4 lg:w-1/3">

                <!-- Article -->
                @if ($category != 'comic')
                <article class="overflow-hidden rounded-lg shadow-lg">

                    <a href="#">
                        <img alt="Placeholder" class="block h-96 w-full" src="{{$item['thumbnail']['path'].'.'.$item['thumbnail']['extension']}}">
                    </a>

                    <header class="flex items-center justify-between leading-tight p-2 md:p-4">
                        <h1 class="text-lg">
                            <a class="no-underline hover:underline text-black" href="#">
                                {{$item['name']}}
                            </a>
                        </h1>
                        <p class="text-grey-darker text-sm">
                            Ultima actualización: {{ \Carbon\Carbon::parse($item['modified'])->format('j F, Y') }}
                        </p>
                    </header>

                    <footer class="flex items-center justify-between leading-none p-2 md:p-4">
                        <span class="flex items-center text-black">
                            <p class="text-sm">
                                @if ($item['description'] != '')
                                {{Str::limit($item['description'],100)}}
                                @else
                                No hay una descripción disponible :(
                                @endif
                            </p>
                        </span>
                        <form title="Marcar como favorito" class="no-underline text-grey-darker hover:text-red-dark" action="{{url('update-fav/'.$item['id'])}}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 hover:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                  </svg>
                            </button>
                            </form>
                    </footer>

                </article>
                @else

                <article class="overflow-hidden rounded-lg shadow-lg">

                    <a href="#">
                        <img alt="Placeholder" class="block h-96 w-full" src="{{$item['thumbnail']['path'].'.'.$item['thumbnail']['extension']}}">
                    </a>

                    <header class="flex items-center justify-between leading-tight p-2 md:p-4">
                        <h1 class="text-lg">
                            <a class="no-underline hover:underline text-black" href="#">
                                {{$item['title']}}
                            </a>
                        </h1>
                        <p class="text-grey-darker text-sm">
                            No. de páginas: {{$item['pageCount']}}
                        </p>
                    </header>

                    <footer class="flex items-center justify-between leading-none p-2 md:p-4">
                        <a class="flex items-center no-underline hover:underline text-black" href="#">
                            <p class="text-sm">
                                @if ($item['description'] != '')
                                {{Str::limit($item['description'],100)}}
                                @else
                                No hay una descripción disponible :(
                                @endif
                            </p>
                        </a>
                        <a title="Marcar como favorito" class="no-underline text-grey-darker hover:text-red-dark" href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 hover:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                              </svg>
                        </a>
                    </footer>

                </article>

                @endif
                <!-- END Article -->

            </div>
            <!-- END Column -->

            @endforeach
        @else

            <div class="flex flex-col justify-center items-center py-12">
                <span>
                    No hay registros de lo que buscas :(
                </span>
            </div>
        </div>
        @endif



    </div>
</x-app-layout>
