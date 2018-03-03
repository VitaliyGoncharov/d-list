@include('layouts.head')

<!-- Content -->
<div class="content">
    <div class="content_inner">
        @include('layouts.leftMenu')

        <div class="pjax-container">
            @yield('content')
        </div>
    </div> <!-- End of the content_inner -->
</div> <!-- End of the content -->

@include('layouts.foot')