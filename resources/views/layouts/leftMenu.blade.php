<!-- Left column -->
<div class="leftCol">
    <div class="leftCol_inner">
        <a href="{{ $leftMenuLinks['profile'] }}" class="leftMenuLinks">Моя страница</a><br>
        <a href="/news" class="leftMenuLinks news">Новости</a><br>
        <a href="/messanger" class="leftMenuLinks">Messanger</a><br>
        <a href="/friends" class="leftMenuLinks">Друзья</a><br>
        <a href="#" class="leftMenuLinks" style="color: #7cfc0036">Аудио</a><br>
        <a href="#" class="leftMenuLinks" style="color: #7cfc0036">Видео</a><br>
        <a href="#" class="leftMenuLinks" style="color: #7cfc0036">Файлы</a><br>
        <a href="#" class="leftMenuLinks">Настройки</a>
        <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">Выход</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    </div>
</div> <!-- End of the left column -->