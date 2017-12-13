<div class="post" id="4">
    <div class="post_head">
        <img src="{{ $new_post['author']->avatar }}" class="author_img" alt="">
           
        <div class="post_info">
            <h5><a href="#" class="author">{{ $new_post['author']->surname }} {{ $new_post['author']->name }}</a></h5>
            <span class="when">Менее минуты назад</span>
        </div>
               
        <div class="option">
            <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
        </div>
    </div>

	<div class="post_content">
        <div class="post_text">
            {{ $new_post['message'] }}
        </div>

        @if(isset($new_post['photos']))
        <div class="post_photo">
            <img src="{{ $new_post['photos'] }}" class="post_photo_inner" alt="">
        </div>
        @endif
		
		@if(isset($new_post['media']))
        <div class="post_media">

        </div>
        @endif

        <div class="post_likes">
            <div class="likes">
                <i id="like" class="fa fa-thumbs-up" aria-hidden="true"></i>
                <span></span>
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
</div>