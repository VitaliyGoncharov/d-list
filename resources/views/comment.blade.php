@foreach($comments as $comment)
<div class="post_comment">
    <img src="{{ $comment->author->avatar }}" class="post_commenter_photo" alt="">

    <div class="post_comment_author">
        <h5><a href="{{ '/profile/'.$comment->author->link }}" class="comment_author_a">{{ $comment->author->surname }} {{ $comment->author->name }}</a></h5>

        <div class="post_comment_content">
            {{ $comment->comment }}
        </div>
    </div>
</div>
@endforeach
