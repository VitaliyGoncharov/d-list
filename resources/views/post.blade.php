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
                    @if($post->photos != null)
                        <div class="post_photo clearfix" data-resized="">
                            @foreach($post->photos as $photo)
                                <img src="{{ $photo }}" class="post_photo_inner" alt="">
                            @endforeach
                        </div>
                    @endif
                @endif


                <div class="post_media">

                </div>

                <div class="post_likes">
                    <div class="likes">
                        <i id="like" class="fa fa-thumbs-up" aria-hidden="true"></i>
                        
                        @if(isset($post->thumb))
                            <span>{{ $post->thumb }}</span>
                        @endif
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
                <img src="{{ Auth::user()->avatar }}" class="user_avatar_mini" alt="">
                <div class="comment_size" id=""></div>
                <textarea name="comment_author_textarea" id="comment_author_textarea" rows="1" placeholder="Оставить комментарий"></textarea>
                <span id="test"></span>
            </div>
        </div>
    @endforeach
@endif