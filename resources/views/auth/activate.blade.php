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

            <!-- Left column -->
            <div class="leftCol">
                <div class="leftCol_inner">
                    @empty(!$status)                        
                      <div class="alert-danger">{{ $status }}</div>
                    @endempty
                </div>
                
                <a href="/" id="toHome">Перейти на главную</a>
            </div> <!-- End of the left column -->

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

{{--<script>
    $(function () {
        $('form').submit(function () {
            return false;
        });
    });
</script>--}}


<script>
    $(document).ready(function(){
        var date = new Date();
        var utc = -date.getTimezoneOffset()/60;
        var utc_in_input = $('#utc').val();


        $('.checkbox').on('click', function() {
            $('.checkbox_box').attr('checked');
        });

        $('.logIn button').on('click', function() {

            if(!utc_in_input)
            {
                $.ajax({
                    type: 'POST',
                    async: true,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
                    },
                    url: '/saveutc',
                    data: 'utc=' + utc,
                    success: function(data) {
                        data = JSON.parse(data);
                        console.log(data);
                    }
                });
            }
        });
    });
</script>
</body>
</html>
