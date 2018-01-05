<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <title>D-LIST — Join us</title>
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
                                        <option value="" selected="1">День</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option>
                                    </select>

                                    <select name="month" class="month" required>
                                        <option value="" selected="1">Месяц</option><option value="1">Янв</option><option value="2">Фев</option><option value="3">Март</option><option value="4">Апр</option><option value="5">Май</option><option value="6">Июнь</option><option value="7">Июль</option><option value="8">Авг</option><option value="9">Сен</option><option value="10">Окт</option><option value="11">Ноя</option><option value="12">Дек</option>
                                    </select>

                                    <select name="year" class="year" required>
                                        <option value="" selected="1">Год</option><option value="2017">2017</option><option value="2016">2016</option><option value="2015">2015</option><option value="2014">2014</option><option value="2013">2013</option><option value="2012">2012</option><option value="2011">2011</option><option value="2010">2010</option><option value="2009">2009</option><option value="2008">2008</option><option value="2007">2007</option><option value="2006">2006</option><option value="2005">2005</option><option value="2004">2004</option><option value="2003">2003</option><option value="2002">2002</option><option value="2001">2001</option><option value="2000">2000</option><option value="1999">1999</option><option value="1998">1998</option><option value="1997">1997</option><option value="1996">1996</option><option value="1995">1995</option><option value="1994">1994</option><option value="1993">1993</option><option value="1992">1992</option><option value="1991">1991</option><option value="1990">1990</option><option value="1989">1989</option><option value="1988">1988</option><option value="1987">1987</option><option value="1986">1986</option><option value="1985">1985</option><option value="1984">1984</option><option value="1983">1983</option><option value="1982">1982</option><option value="1981">1981</option><option value="1980">1980</option><option value="1979">1979</option><option value="1978">1978</option><option value="1977">1977</option><option value="1976">1976</option><option value="1975">1975</option><option value="1974">1974</option><option value="1973">1973</option><option value="1972">1972</option><option value="1971">1971</option><option value="1970">1970</option><option value="1969">1969</option><option value="1968">1968</option><option value="1967">1967</option><option value="1966">1966</option><option value="1965">1965</option><option value="1964">1964</option><option value="1963">1963</option><option value="1962">1962</option><option value="1961">1961</option><option value="1960">1960</option><option value="1959">1959</option><option value="1958">1958</option><option value="1957">1957</option><option value="1956">1956</option><option value="1955">1955</option><option value="1954">1954</option><option value="1953">1953</option><option value="1952">1952</option><option value="1951">1951</option><option value="1950">1950</option><option value="1949">1949</option><option value="1948">1948</option><option value="1947">1947</option><option value="1946">1946</option><option value="1945">1945</option><option value="1944">1944</option><option value="1943">1943</option><option value="1942">1942</option><option value="1941">1941</option><option value="1940">1940</option>
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
                $('.g-recaptcha').css({'display':'block'});
            });
        });
    </script>
@endif
</body>
</html>
