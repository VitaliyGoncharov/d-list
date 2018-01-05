<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>D-LIST — Join us</title>
    <link rel="stylesheet" href="{{ asset('css/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body>
<div class="wrap">
    <!-- Head -->
    <div class="head">
        <div class="head_inner">
            <div class="hlogo">
                <div class="hlogo_inner">

                    <h1>
                        <img src="{{ Auth::user()->avatar }}" class="user_avatar_mini" alt="">
                        <span class="username">{{ Auth::user()->name }}</span>
                    </h1>
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

            <!-- Left column -->
            <div class="leftCol">
                <div class="leftCol_inner">
                    <a href="#">Моя страница</a><br>
                    <a href="#">Messanger</a><br>
                    <a href="#">Друзья</a><br>
                    <a href="#">Аудио</a><br>
                    <a href="#">Видео</a><br>
                    <a href="#">Блокнот</a><br>
                    <a href="#">Настройки</a>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">Выход</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </div>
            </div> <!-- End of the left column -->

            <!-- Center column -->
            <div class="centerCol">
                <div class="centerCol_inner" id="az">
                    <div class="addNewPost">
                        <div class="add_text">
                            <img src="{{ Auth::user()->avatar }}" class="user_avatar_mini" alt="">
                            <div class="comment_size" id=""></div>
                            <textarea name="comment_author_textarea" id="comment_author_textarea" class="newPostMessage" rows="1" placeholder="Добавить пост"></textarea>
                            <span id="user_id">{{ Auth::user()->id }}</span>

                            <div class="smileys">
                                <img src="https://image.flaticon.com/icons/svg/187/187142.svg" alt="">
                            </div>
                        </div>
                        

                        <div class="attachSomething">
                            <div class="attachOptions">
                                <i class="fa fa-camera" aria-hidden="true" onclick="clickUpload(this);"></i>
                                <i class="fa fa-file" aria-hidden="true" onclick="clickUpload(this);"></i>
                                <input type="file" id="upload" accept="" multiple>
                            </div>
                            
                            <div class="addPostBut">
                                <button type="submit" id="addPostBut" onclick="">Запостить</button>
                            </div>
                        </div>
                    </div>

                    {{ csrf_field() }}

                    @include('post')

                </div>
            </div>

            <!-- Right column -->
            <div class="rightCol">
                <div class="rightCol_inner">
                    <div class="rightCol_links">
                        <a href="#">Новости</a><br>
                        <a href="#">Фото</a><br>
                        <a href="#">Аудио</a><br>
                        <a href="#">Рекомендации</a><br>
                        <a href="#">Закладки</a><br>
                        <a href="#">Группы</a><br>
                        <a href="#">Комментарии</a><br>
                        <a href="#">Обновления</a>
                    </div>
                </div> <!-- End of the right column content -->
            </div> <!-- End of the right column -->
        </div> <!-- End of the content_inner -->
    </div> <!-- End of the content -->
</div> <!-- End of the wrap [100vh] -->

<!-- Footer -->
<div class="footer">
    <div class="footer_inner">
        <div class="flogo">
            <a href="#">Devvit © 2017</a>
        </div>

        <div class="links">
            <div class="links_inner">
                <a href="#">О нас</a>
                <a href="#">Правила</a>
                <a href="#">Реклама</a>
                <a href="#">Разработчикам</a>
            </div>
        </div>

        <div class="lang">
            <div class="lang_inner">
                <p>Язык:</p>
                <a href="#">EN</a>
                <a href="#">RU</a>
            </div>
        </div>
    </div> <!-- End of the footer_inner -->
</div> <!-- End of the footer -->

<script src="{{ asset('js/jquery-3.2.1.js') }}" defer></script>
<script src="{{ asset('js/gallery.js') }}" defer></script>
<script src="{{ asset('js/main.js') }}" defer></script>
</body>
</html>
