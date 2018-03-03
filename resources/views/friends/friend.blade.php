@foreach($friends as $friend)
    <div class="friend__list__item">
        <div class="friend_icon">
            <a class="friend_link" href="/profile/{{ $friend->link }}"><img src="{{ $friend->avatar }}" alt=""></a>
        </div>

        <div class="friend_content">
            <a class="friend_link" href="/profile/{{ $friend->link }}"><h4 class="friend_name">{{ $friend->surname.' '.$friend->name }}</h4></a>
            <a class="friend_write_msg" href="/msg/write{{ $friend->id }}">Написать сообщение</a>
        </div>
    </div>
@endforeach