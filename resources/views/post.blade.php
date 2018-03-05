<div class="posts_collection" data-resized="" data-lastPost-id="{{ isset($posts->lastPostId) ? $posts->lastPostId : '' }}">
@foreach($posts as $post)
    <div class="post" data-post-id="{{ $post->id }}">
        <div class="post_head">
            <img src="{{ $post->author->avatar }}" class="author_img" alt="">

            <div class="post_info">
                <h5><a href="{{ '/profile/'.$post->author->link }}" class="author">{{ $post->author->surname.' '.$post->author->name }}</a></h5>
                <span class="when">{{ $post->creation_date }}</span>
            </div>

            <div class="option">
                <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
            </div>
        </div>

        <div class="post_content">
            @if($post->text)
                <div class="post_text">
                    {!! $post->text !!}
                </div>
            @endif


            @if(isset($post->attachments->photos))
                @if($post->attachments->photos)
                    <div class="post_photo clearfix" data-resized="">
                        @foreach($post->attachments->photos as $photo)
                            <img src="{{ $photo }}" class="post_photo_inner" alt="">
                        @endforeach
                    </div>
                @endif
            @endif

            @if(!empty($post->attachments->files))
                @if($post->attachments->files)
                    <div class="post_attachments">
                        @foreach($post->attachments->files as $file)
                            <div class="uploadedFileWrap" data-src="{{ $file['src'] }}">
                                <img class="uploadedFileIcon" src="https://cdn.iconscout.com/public/images/icon/free/png-512/docs-document-file-data-google-suits-39cb6f3f9d29e942-512x512.png" alt="">
                                <span class="uploadedFilename">{{ $file['name'] }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif

            <div class="post_likes">
                <a class="likes" href="#" onclick="likeAction(event)" data-set="/like/add" data-unset="/like/delete" data-pid="{{ $post->id }}">
                    <i class="fa fa-thumbs-up {{ $post->liked ? 'green' : '' }}" aria-hidden="true"></i>

                    <span class="{{ $post->liked ? 'green' : '' }}">{{ $post->likes ?: '' }}</span>
                </a>

                <a class="dislikes">
                    <i class="fa fa-thumbs-down {{ !($post->disliked) ?: 'red' }}" aria-hidden="true"></i>

                    @if($post->dislikes)
                        <span class="{{ !($post->disliked) ?: 'red' }}">{{ $post->dislikes }}</span>
                    @endif
                </a>

                <div class="share">
                    <i id="share" class="fa fa-bullhorn" aria-hidden="true"></i>
                    <span></span>
                </div>

                <div class="comment">
                    <i id="comment" class="fa fa-comment" aria-hidden="true"></i>
                </div>
            </div>
        </div>

        @if(!empty($post->comments))
            @foreach($post->comments as $comment)
            <div class="post_comments">
                <div class="post_comment">
                    <img src="{{ $comment->author->avatar }}" class="post_commenter_photo" alt="">

                    <div class="post_comment_author">
                        <h5><a href="{{ '/profile/'.$comment->author->link }}" class="comment_author_a">{{ $post->comment->author->surname }} {{ $post->comment->author->name }}</a></h5>

                        <div class="post_comment_content">
                            {{ $post->comment->comment }}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif

        <div class="add_comment">
            <img src="{{ request()->user()->avatar }}" class="user_avatar_mini" alt="">
            <div class="comment_size" id=""></div>
            <textarea class="addCommentTextarea autoresizable" rows="1" placeholder="Оставить комментарий"></textarea>
            <span id="test"></span>
        </div>
    </div>
@endforeach
</div>