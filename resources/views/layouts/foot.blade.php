</div> <!-- End of the wrap [100vh] -->

<!-- Footer -->
<div class="footer">
    <div class="footer_inner">
        <div class="flogo">
            <a class="inDevelopment" href="#">Devvit © 2017</a>
        </div>

        <div class="links">
            <div class="links_inner">
                <a class="inDevelopment" href="#">О нас</a>
                <a class="inDevelopment" href="#">Правила</a>
                <a class="inDevelopment" href="#">Реклама</a>
                <a href="https://github.com/VitaliyGoncharov/d-list">Разработчикам</a>
            </div>
        </div>

        <div class="lang">
            <div class="lang_inner">
                <p>Язык:</p>
                <a class="inDevelopment" href="#">EN</a>
                <a class="inDevelopment" href="#">RU</a>
            </div>
        </div>
    </div> <!-- End of the footer_inner -->
</div> <!-- End of the footer -->

@if($url === '/')
    <script src="{{ asset('js/jquery-3.2.1.js') }}" defer></script>
    <script src="{{ asset('js/home.js') }}" defer></script>
    <script src="https://www.google.com/recaptcha/api.js" defer></script>
@else
    <script src="{{ asset('js/jquery-3.2.1.js') }}" defer></script>
    <script src="{{ asset('js/jquery.pjax.js') }}" defer></script>
    <script src="{{ asset('js/gallery.js') }}" defer></script>
    <script src="{{ asset('js/main.js') }}" defer></script>
@endif

</body>
</html>