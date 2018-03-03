@extends('layouts.content')

@section('content')
    <!-- Center column -->
    <div class="centerCol">
        <div class="centerCol_inner friends">
            <div id="friends__search">
                <div><i class="fa fa-search" aria-hidden="true"></i></div>
                <textarea id="friends__search__textarea" rows="1" placeholder="Найти"></textarea>
            </div>

            @yield('friends')
        </div>
    </div> <!-- End of the center column -->

    <!-- Right column -->
    <div class="rightCol">
        <div class="rightCol_inner">
            <div class="rightCol_links">
                <a href="/friend/search">Найти друга</a>
                <a href="/friend/requests/incoming">Запросы</a>
                <a href="/friend/requests/outgoing">Мои запросы</a>
            </div>
        </div> <!-- End of the right column content -->
    </div> <!-- End of the right column -->
@endsection