@extends('layouts.content')

@section('content')
    <!-- Center column -->
    <div class="centerCol">
        <div class="centerCol_inner">
            @yield('col-center')
        </div> <!-- End of the inner center column -->
    </div> <!-- End of the center column -->

    <!-- Right column -->
    <div class="rightCol">
        <div class="rightCol_inner">
            <div class="rightCol_links">
                <a href="#" style="color: #7cfc0036">Фото</a><br>
                <a href="#" style="color: #7cfc0036">Аудио</a><br>
                <a href="#" style="color: #7cfc0036">Закладки</a><br>
                <a href="#" style="color: #7cfc0036">Группы</a><br>
                <a href="#" style="color: #7cfc0036">Комментарии</a><br>
                <a href="/news/search">Найти</a>
            </div>
        </div> <!-- End of the right column content -->
    </div> <!-- End of the right column -->
@endsection