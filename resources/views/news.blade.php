@extends('layouts.content')

@section('content')
<!-- Center column -->
<div class="centerCol">
    <div class="centerCol_inner">
        <div id="addPost">
            <div class="add_text">
                <img src="{{ Auth::user()->avatar }}" class="user_avatar_mini" alt="">
                <div class="comment_size" id=""></div>
                <textarea id="addPostTextarea" class=" autoresizable" rows="1" placeholder="Добавить пост"></textarea>
                <span id="user_id">{{ Auth::user()->id }}</span>

                <div class="smileys">
                    <img src="https://image.flaticon.com/icons/svg/187/187142.svg" alt="">
                </div>
            </div>

            @if(!empty($addPostInfo))
                @if(!empty($addPostInfo['images']))
                    <div class="attachedPhotosPreview">
                        @foreach($addPostInfo['images'] as $image)
                            <div class="attachedPhotoWrap">
                                <span class="close close__addPost">&times;</span>
                                <img class="photoPreview" src="{{ $image }}" alt="">
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif

            @if(!empty($addPostInfo))
                @if(!empty($addPostInfo['files']))
                    <div id="uploadedFiles">
                        @foreach($addPostInfo['files'] as $file)
                            <div class="uploadedFileWrap" data-src="{{ $file['src'] }}">
                                <img class="uploadedFileIcon" src="https://cdn.iconscout.com/public/images/icon/free/png-512/docs-document-file-data-google-suits-39cb6f3f9d29e942-512x512.png" alt="">
                                <span class="uploadedFilename">{{ $file['name'] }}</span>
                                <span class="close close__File">&times;</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif

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
</div> <!-- End of the center column -->

<!-- Right column -->
<div class="rightCol">
    <div class="rightCol_inner">
        <div class="rightCol_links">
            <a href="#" style="color: #7cfc0036">Фото</a><br>
            <a href="#" style="color: #7cfc0036">Аудио</a><br>
            <a href="#" style="color: #7cfc0036">Закладки</a><br>
            <a href="#" style="color: #7cfc0036">Группы</a><br>
            <a href="#" style="color: #7cfc0036">Комментарии</a><br>
        </div>
    </div> <!-- End of the right column content -->
</div> <!-- End of the right column -->
@endsection