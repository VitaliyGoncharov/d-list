@extends('layouts.content')

@section('content')
    <div id="messanger">
        <div id="chatWrap">
            <div id="chat__header">
                <a href="{{ $profileLink }}"><img src="{{ $userRec->avatar }}" alt=""></a>
                <a href="{{ $profileLink }}">{{ $userRec->surname.' '.$userRec->name }}</a>
            </div>

            <div id="msgsWrap">
                <div class="messageInChat">
                    <div class="messagePartner">Здравствуйте. Когда заказ будет готов?</div>
                </div>

                <div class="messageInChat">
                    <div class="messageUser">Через 2 дня.</div>
                </div>

                <div class="messageInChat">
                    <div class="messageUser">Через 2 дня.</div>
                </div>
            </div>


            <div id="writeMsg">
                <div id="msgTextfieldWrap">
                    <div id="messageTextfield" contenteditable="true" data-text="Написать сообщение"></div>
                </div>

                <button id="sendMessageButton">Отправить</button>
            </div>
        </div>
    </div>
@endsection