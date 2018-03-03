@extends('layouts.friends.main')

@section('friends')
    <div id="friends__list">
        @foreach($people as $person)
            <div class="friend__list__item">
                <div class="friend_icon">
                    <a class="friend_link" href="/profile/{{ $person->link }}"><img src="{{ $person->avatar }}" alt=""></a>
                </div>

                <div class="friend_content">
                    <a class="friend_link" href="/profile/{{ $person->link }}"><h4 class="friend_name">{{ $person->surname.' '.$person->name }}</h4></a>
                    <a class="friend_write_msg" href="/msg/write{{ $person->id }}">Написать сообщение</a>
                </div>

                @isset($person->request)
                    <div class="request_sended">
                        Request was sended
                    </div>
                @else
                    <a class="friend_send_request" href="/friend/request/send/{{ $person->id }}" onclick="sendFriendRequest(event)">+</a>
                @endisset
            </div>
        @endforeach
    </div>
@endsection