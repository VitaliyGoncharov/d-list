@extends('layouts.content')

@section('content')
    <div id="messanger">
        <div id="messanger__search">
            <div><i class="fa fa-search" aria-hidden="true"></i></div>
            <textarea rows="1" placeholder="Найти"></textarea>
        </div>

        <div id="messanger__chatslist">
            @foreach ($convs_data as $conv_data)
                <div class="messanger__chatslist__item">
                    <div class="chat_icon"><img src="{{ $conv_data->avatar }}" alt=""></div>

                    <div class="chat_info">
                        <h4><span class="chat_name">{{ $conv_data->surname.' '.$conv_data->name }}</span></h4>
                        <span class="last_message">Последнее сообщение</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection