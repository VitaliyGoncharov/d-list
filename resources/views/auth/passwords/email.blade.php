<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>DEVVIT — Join us</title>
    <link rel="stylesheet" href="{{ asset('css/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <script src="{{ asset('js/jquery-3.2.1.js') }}"></script>
</head>
<body>
<div class="wrap">

    <!-- Head -->
    <div class="head">
        <div class="head_inner">
            <div class="hlogo">
                <div class="hlogo_inner">
                    <h1>D-chat</h1>
                </div>
            </div>

            <div class="search">
                <form action="#" class="search_form" method="POST">
                    <input type="text" class="input" placeholder="Поиск">
                    <button type="submit" class="button"><i class="fa fa-search" aria-hidden="true"></i></button>
                </form>
            </div>
        </div> <!-- End of the head wrapper -->
    </div> <!-- End of the head -->

    <!-- Content -->
    <div class="content">
        <div class="content_inner">

            <!-- Center column -->
            <div class="alignCenter">

                <form class="reset_pwd" method="POST" action="{{ route('password.email.POST') }}">
                    {{ csrf_field() }}

                    <p style="margin-bottom: 10px; font-size: 1.1em;">Введите свой email</p>
                    <input id="username" type="email" class="form-control" name="email" value="" required>

                    
                    <button type="submit" class="btn btn-primary">
                        Отправить ссылку
                    </button>
                </form>
            </div> <!-- End of the center column -->

        </div> <!-- End of the content_inner -->
    </div> <!-- End of the content -->
@include('layouts.foot')
