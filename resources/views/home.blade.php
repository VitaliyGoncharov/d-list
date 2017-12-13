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
                        <img src="{{ Auth::user()->avatar }}" class="add_post_author_photo" alt="">
                        {{ Auth::user()->name }}
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
                    <div class="add_new_post" id="add_post">
                        <div class="add_text">
                            <img src="{{ Auth::user()->avatar }}" class="add_post_author_photo" alt="">
                            <div class="comment_size" id=""></div>
                            <textarea name="comment_author_textarea" id="comment_author_textarea" rows="1" placeholder="Добавить пост"></textarea>
                            <span id="author_id">{{ Auth::user()->id }}</span>

                            <div class="smileys">
                                <img src="https://image.flaticon.com/icons/svg/187/187142.svg" alt="">
                            </div>
                        </div>
                        

                        <div class="attach_something">
                            <div class="attach_options">
                                <i class="fa fa-camera" aria-hidden="true" onclick="clickUpload(this);"></i>
                                <i class="fa fa-file" aria-hidden="true" onclick="clickUpload(this);"></i>
                                <input type="file" id="upload" accept="" multiple>
                            </div>
                            
                            <div class="send">
                                <button type="submit" id="send_post" onclick="addPost();">Запостить</button>
                            </div>
                        </div>
                    </div>

                    {{ csrf_field() }}

                    @if(isset($posts))
                        @foreach($posts as $post)
                            <div class="post" id="4">
                                <div class="post_head">
                                    <img src="{{ $post->avatar }}" class="author_img" alt="">

                                    <div class="post_info">
                                        <h5><a href="#" class="author">{{ $post->surname }} {{ $post->name }}</a></h5>
                                        <span class="when">{{ $post->creation_date }}</span>
                                    </div>

                                    <div class="option">
                                        <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                    </div>
                                </div>

                                <div class="post_content">
                                    <div class="post_text">
                                        {{ $post->text }}
                                    </div>

                                    @if(isset($post->photos))
                                    <div class="post_photo">
                                        <img src="{{ $post->photos }}" class="post_photo_inner" alt="">
                                    </div>
                                    @endif

                                    <div class="post_media">

                                    </div>

                                    <div class="post_likes">
                                        <div class="likes">
                                            <i id="like" class="fa fa-thumbs-up" aria-hidden="true"></i>
                                            <span>{{ $post->thumb }}</span>
                                        </div>

                                        <div class="share">
                                            <i id="share" class="fa fa-bullhorn" aria-hidden="true"></i>
                                            <span>4</span>
                                        </div>

                                        <div class="comment">
                                            <i id="comment" class="fa fa-comment" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>

                                @if(isset($post->comment))
                                    <div class="post_comments">
                                        <div class="post_comment">
                                            <div id="post_id">2</div>
                                            <div id="user_id">3</div>
                                            <img src="{{ $post->comment->avatar }}" class="post_commenter_photo" alt="">

                                            <div class="post_comment_author">
                                                <h5><a href="#" class="comment_author_a">{{ $post->comment->surname }} {{ $post->comment->name }}</a></h5>

                                                <div class="post_comment_content">
                                                    {{ $post->comment->comment }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="add_comment">
                                    <img src="{{ Auth::user()->avatar }}" class="add_post_author_photo" alt="">
                                    <div class="comment_size" id=""></div>
                                    <textarea name="comment_author_textarea" id="comment_author_textarea" rows="1" placeholder="Оставить комментарий"></textarea>
                                    <span id="test"></span>
                                </div>
                            </div>
                        @endforeach
                    @endif

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

<script src="{{ asset('js/jquery-3.2.1.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
</body>
</html>
