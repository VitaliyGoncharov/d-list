<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>D-LIST — Join us</title>
    <noscript>
        <!-- Redirect to https://support.google.com/adsense/answer/12654?hl=ru -->
        <meta http-equiv="refresh" content="0; url=https://support.google.com/adsense/answer/12654?hl=ru">
    </noscript>
    <link rel="stylesheet" href="{{ asset('css/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <script src="{{ asset('js/jquery-3.2.1.js') }}"></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
<div class="wrap">

    <!-- Head -->
    <div class="head">
        <div class="head_inner">
            <div class="hlogo">
                <div class="hlogo_inner">
                    <h1>D-list</h1>
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
                    {{--https://i.ytimg.com/vi/YH5ul5R7Jc0/maxresdefault.jpg--}}
                    <h2>Be in contact with your friends</h2>
                    <img class="img-fluid" src="{{ asset('/rus.jpg') }}" alt="">
                </div>
            </div> <!-- End of the left column -->

            <!-- Right column -->
            <div class="rightCol">
                <div class="rightCol_inner">
                    <div class="logIn">
                        <div>
                            @if(session('incorrect_data'))
                                <div class="alert-danger">{{ session('incorrect_data') }}</div>
                            @endif
                        </div>

                        <form action="{{ route('login') }}" method="POST">
                            {{ csrf_field() }}
                            
                            <input type="text" id="username" name="email" value="{{ session('last.logIn.email') }}" placeholder="Email" required><br>
                            <input type="password" id="password" name="password" placeholder="Пароль" required><br>

                            <div class="last_panel">
                                <a href="{{ route('password.email.GET') }}">Забыли пароль?</a> <br>

                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" class="checkbox_box" name="remember"> Запомнить
                                    </label>
                                </div>
                            </div>

                            <input type="hidden" id="utc" name="utc">

                            <button type="submit" class="button">Войти</button>
                        </form>
                    </div>



                    <div class="signIn">
                        <form action="{{ route('register') }}" method="POST">
                            {{ csrf_field() }}
        
                            <div class="relative">
                                <div style="margin-bottom: 3px;">
                                    <label for="name"><b>Имя</b></label>
                                </div>
                            
                                <div>
                                    <input type="text" id="name" name="name" placeholder="Имя" value="{{ session('last.name')}}" required><br>
                                </div>
                                
                                <div class="error">
                                    <!-- if user offed javascript -->
                                    @if( session('last.name') )
                                        <div class='validation-message'>
                                            <div class='float-right'>
                                                <img src="/success-mark.png">
                                            </div>
                                        </div>
                                    @endif

                                    @if($errors->register->has('name'))
                                        <div class="validation-message">
                                            <div class="alert-danger float-right">
                                                <p>{{ $errors->register->first('name') }}</p>
                                            </div>
                                        </div>
                                        
                                        <script>
                                            $('#name').css("border-color", "red");
                                        </script>
                                    @endif
                                    <!-- end -->
                                </div>
                                
                            </div>

                            <div class="relative">
                                <div style="margin-bottom: 3px;">
                                    <label for="surname"><b>Фамилия</b></label>
                                </div>
                            
                                <div>
                                    <input type="text" id="surname" name="surname" placeholder="Фамилия" value="{{ session('last.surname')}}" required><br>
                                </div>
                                
                                <div class="error">
                                    <!-- if user offed javascript -->
                                    @if( session('last.surname') )
                                        <div class='validation-message'>
                                            <div class='float-right'>
                                                <img src="/success-mark.png">
                                            </div>
                                        </div>
                                    @endif

                                    @if($errors->register->has('surname'))
                                        <div class="validation-message">
                                            <div class="alert-danger float-right">
                                                <p>{{ $errors->register->first('surname') }}</p>
                                            </div>
                                        </div>

                                        <script>
                                            $('#surname').css("border-color", "red");
                                        </script>
                                    @endif
                                    <!-- end -->
                                </div>
                            </div>

                            <div class="relative">
                                <div style="margin-bottom: 3px;">
                                    <label for="email"><b>Email</b></label>
                                </div>
                            
                                <div>
                                    <input type="text" id="reg_email" class="hiddenEmail" name="email" placeholder="Email" value="{{ session('last.email')}}" required><br>
                                </div>
                                
                                <div class="error">
                                    <!-- if user offed javascript -->
                                    @if( session('last.email') )
                                        <div class='validation-message'>
                                            <div class='float-right'>
                                                <img src="/success-mark.png">
                                            </div>
                                        </div>
                                    @endif

                                    @if($errors->register->has('email'))
                                        <div class="validation-message">
                                            <div class="alert-danger float-right">
                                                <p>{{ $errors->register->first('email') }}</p>
                                            </div>
                                        </div>

                                        <script>
                                            $('#reg_email').css("border-color", "red");
                                        </script>
                                    @endif
                                    <!-- end -->
                                </div>
                            </div>

                            
                            <div class="relative">
                                <div style="margin-bottom: 3px;">
                                    <label for="password"><b>Пароль</b></label>
                                </div>
                            
                                <div>
                                    <input type="password" id="reg_password" name="password" placeholder="Пароль" required><br>
                                </div>

                                <div class="error">
                                    <!-- if user offed javascript -->
                                    @if($errors->register->has('password'))
                                        <div class="validation-message">
                                            <div class="alert-danger float-right">
                                                <p>{{ $errors->register->first('password') }}</p>
                                            </div>
                                        </div>

                                        <script>
                                            $('#reg_password').css("border-color", "red");
                                        </script>
                                    @endif
                                    <!-- end -->
                                </div>

                            </div>
                    

                            <div class="relative">
                                <div class="birthday">
                                    <select name="day" class="day" required>
                                        <option value="" selected="1">День</option>
                                        @for($i=1;$i<=31;$i++)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>

                                    <select name="month" class="month" required>
                                        <option value="" selected="1">Месяц</option><option value="1">Янв</option><option value="2">Фев</option><option value="3">Март</option><option value="4">Апр</option><option value="5">Май</option><option value="6">Июнь</option><option value="7">Июль</option><option value="8">Авг</option><option value="9">Сен</option><option value="10">Окт</option><option value="11">Ноя</option><option value="12">Дек</option>
                                    </select>

                                    <select name="year" class="year" required>
                                        <option value="" selected="1">Год</option>
                                        @for($i=2017;$i>=1940;$i--)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select><br>
                                </div> <!-- End of the birthday form -->

                                <div class="last_birth">
                                    <input type="hidden" id="last_birth_day" value="{{ session('last.day') }}">
                                    <input type="hidden" id="last_birth_month" value="{{ session('last.month') }}">
                                    <input type="hidden" id="last_birth_year" value="{{ session('last.year') }}">
                                </div>

                                <script>
                                    var day = $('#last_birth_day').val();
                                    $('.day option[value="' + day + '"]').attr('selected', '1');

                                    var month = $('#last_birth_month').val();
                                    $('.month option[value="' + month + '"]').attr('selected', '1');

                                    var year = $('#last_birth_year').val();
                                    $('.year option[value="' + year + '"]').attr('selected', '1');
                                </script>

                                @if($errors->register->has('day') || $errors->register->has('month') || $errors->register->has('year'))
                                    <div class="validation-message for_birth">
                                        <div class="alert-danger float-right">
                                            <p>Поле обязательно для заполнения</p>
                                        </div>
                                    </div> 
                                @endif
                            </div>
                            
                            <div class="relative">
                                <div class="gender">
                                    <div class="male">
                                        <input type="radio" name="gender" value="male"
                                        @if(session('last.gender') == 'male' || !session('last.gender'))
                                            checked
                                        @endif> Мужчина
                                    </div>

                                    <div class="female">
                                        <input type="radio" name="gender" value="female"
                                        @if(session('last.gender') == 'female')
                                            checked
                                        @endif> Женщина
                                    </div>
                                </div> 
                            </div><!-- End of the gender form -->

                            <div class="relative">
                                <div class="g-recaptcha" data-sitekey="6LcpYToUAAAAADStXAbu7BBoF-iJziV1c1VV-a6Z" style="transform: scale(0.88);transform-origin:0 0;max-width: 268px"></div>

                                @if( $errors->register->has('captcha') )
                                    <div class="error">
                                        <div class="validation-message">
                                            <div class="alert-danger float-right">
                                                <p>{{ $errors->register->first('captcha') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <button type="submit" class="button">Создать аккаунт</button>
                        </form>
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


<script>
    $(document).ready(function(){
        var date = new Date();
        var utc = -date.getTimezoneOffset()/60;
        $('#utc').attr('value',utc);


        $('.checkbox').on('click', function() {
            $('.checkbox_box').attr('checked');
        });

    });
</script>

<script>

    var ids = [
        'name',
        'surname',
        'reg_email',
        'reg_password',
    ];

    function validate() {
        var parent = $('#' + input_id).parent().parent().find('.error');
        
        $.ajax({
            type: 'POST',
            async: true,
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
            },
            url: '/checkuserinput',
            data: input_id + '=' + $('#' + input_id).val(),
            success: function(data) {

                if(data == 'success')
                {
                    $('#' + input_id).css('border-color', 'grey');
                    parent.find('.validation-message').remove();
                    

                    $(parent).append(
                        "<div class='validation-message'><div class='float-right'><img src=\"/success-mark.png\"></div></div>"
                    );
                }
                else
                {
                    $('#' + input_id).css('border-color', 'red');
                    parent.find('.validation-message').remove();

                    $(parent).append(
                        "<div class='validation-message'><div class='alert-danger float-right'><p></p></div></div>"
                    );

                    $(parent.find('p')).html(data);
                }
 

            }
        });
    }

    Function.prototype.delayed = function (ms) {
        var timer = 0;
        var callback = this;
        return function() {
            input_id = this.id;
            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    };
    
    for(i=0;i<ids.length;i++)
    {
        document.getElementById(ids[i]).addEventListener('keyup', validate.delayed(500));
        document.getElementById(ids[i]).addEventListener('focusout', validate.delayed(500));
    }
    
</script>

@if( !$errors->register->has('captcha') )
    <script>
        $(document).ready(function() {
            $('.g-recaptcha').css({'display':'none'});

            $('#reg_password').on('focus', function() {
                $('.g-recaptcha').slideDown("slow");
            });
        });
    </script>
@endif
</body>
</html>
