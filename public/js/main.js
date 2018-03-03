const DONE = 4;

$(document).pjax('.leftMenuLinks, .author', '.pjax-container', {
    fragment: '.pjax-container'
});

$(document).on('pjax:success', function () {
    if (location.href.match(/([^/]+)$/)[0] === 'news' && $('.posts_collection[data-resized=""]').length !== 0) {
        resizeImages();
    }
});

$(document).ready(function () {
    if (location.href.match(/([^/]+)$/)[0] === 'news' && $('.posts_collection[data-resized=""]').length !== 0) {
        console.log('READY');
        resizeImages();
    }

    // for search
    let ids = [
        'friends__search__textarea'
    ];

    Function.prototype.delayed = function (ms, selector) {
        let timer = 0;
        let callback = this;

        return function() {
            clearTimeout(timer);
            timer = setTimeout(function () {
                callback(selector);
            }, ms);
        };
    };

    ids.forEach(function(element) {
        let selector = '#' + element;
        $(selector).on('keyup', searchRequest.delayed(500, selector));
    });
});

function searchRequest(selector) {
    let val = $(selector).val();

    $.ajax({
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
        },
        url: '/friend/search',
        data: 'key=' + val,
        success: function (data) {
            $('#friends__list').empty();
            $('#friends__list').append(data);
        }
    });
}

/**
 ********************************************************
 * Click input[type=file] when user click on upload icon
 * and restrict the available extensions
 ********************************************************
 *
 * @param obj
 **/
function clickUpload(obj) {
    let classNamesArray = obj.className.split(' ');
    let className = classNamesArray.pop();
    let attach_options = {
        'fa-camera': 'image/*',
        'fa-file': ' '
    };

    for (let key in attach_options) {
        if (className == key) {
            $('#upload').attr('accept', attach_options[key]).click();
        }
    }
}

/******* END *******/

/**
 **********************************************
 * Upload file when user chose the file('-s'),
 * show upload progress and file itself
 **********************************************
 **/
$(".pjax-container").on("change","#upload",function (e) {
    // get to know how many files user attached
    let files = Object.values(e.target.files);
    let files_num = files.length;
    let uploaded_files_num = $('.addNewPost').find('.uploadingFileProgress').length;
    if (files_num > 8) files_num = 8;


    if (uploaded_files_num > 8 || uploaded_files_num + files_num > 8) {
        console.log('More than 8 is not allowed');
        return;
    }
    if (files_num > 8) files_num = 8;

    function readFile(file) {
        return new Promise(function (res, rej) {
            let reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = (e) => {
                let image = new Image();
                image.src = e.target.result;
                image.onload = function() {
                    console.log('readFile');
                    return res(this.src);
                };
            }
        });
    }

    (function addFile(files) {
        let file = files.shift();
        let extension = file.name.split('.').pop();
        let imageExtensions = ['jpg','jpeg'];
        let checkIfImage = () => {
            let image;
            imageExtensions.forEach((value) => {
                if(value === extension) {
                    image = true;
                }
            });
            return image;
        };

        // if the file is image then read it, show photo preview and progress bar, upload
        if(checkIfImage()) {
            readFile(file)
                .then(
                    src => {
                        return new Promise((res,rej) => {
                            let preview = showPhotoPreview(src);
                            let progress = showProgressBar(file);
                            res({
                                preview: preview,
                                progress: progress
                            });
                        });
                    },
                    error => {

                })
                .then(
                    DOMelems => {
                        return uploadFile(DOMelems,file,true);
                    }
                )
                .then(
                    success => {
                        success.photoWrap.find('.photoPreview').attr('src',success.src);
                        let closeSpan = $('<span></span>')
                            .attr('class','close close__addPost')
                            .html('&times;');
                        success.photoWrap.append(closeSpan);

                        if(files.length === 0) {
                            console.log('End');
                            return;
                        }

                        return addFile(files);
                    }
                );
        }
        else {
            let promise = new Promise((res,rej) => {
                let progress = showProgressBar(file);
                res({
                    progress: progress
                });
            });
            promise
                .then(
                    DOMelems => {
                        return uploadFile(DOMelems,file,false);
                    }
                )
                .then(
                    src => {
                        if($('#uploadedFiles').length == 0) {
                            let uploadedFiles = $('<div></div>')
                                .attr('id','uploadedFiles');
                            uploadedFiles.insertBefore($('.attachSomething'));
                        }

                        let uploadedFile = $('<div></div>')
                            .attr('class','uploadedFileWrap')
                            .attr('data-src',src);
                        let img = $('<img src="">')
                            .attr('src','https://cdn.iconscout.com/public/images/icon/free/png-512/docs-document-file-data-google-suits-39cb6f3f9d29e942-512x512.png')
                            .attr('class','uploadedFileIcon');
                        let filenameSpan = $('<span></span>')
                            .attr('class','uploadedFilename')
                            .html(file.name);
                        let closeSpan = $('<span></span>')
                            .attr('class','close close__File')
                            .html('&times;');
                        uploadedFile.append(img,filenameSpan,closeSpan);
                        $('#uploadedFiles').append(uploadedFile);

                        if(files.length === 0) {
                            console.log('End');
                            return;
                        }

                        return addFile(files);
                    }
                )
        }

    })(files);
});
/******* END *******/

function showProgressBar(file) {
    let progressWrapClass = 'uploadingFileProgress';
    let progressWrap = $('<div></div>');
    progressWrap.attr('class', progressWrapClass);
    $('#addPost').append(progressWrap);

    let progressBarWrap = $('<div></div>')
        .attr('class', 'progressBarWrap');
    progressWrap.append(progressBarWrap);

    let progressBar = $('<div></div>');
    progressBar.attr({
        'class' : 'progress-bar',
    });
    progressBar.append($('<span></span>'));
    progressBarWrap.append(progressBar);

    let progressSpan = $('<span></span>')
        .attr('class', 'uploadingProgressPercents')
        .html('0%');
    progressBarWrap.append(progressSpan);

    let filenameSpan = $('<span></span>')
        .attr('class', 'uploadingFilename')
        .html(file.name);
    progressWrap.append(filenameSpan);

    return {
        progressWrap: progressWrap,
        progressBar: progressBar,
        progressSpan: progressSpan,
        progressBarWrap: progressBarWrap
    }
}

/**
 ************************************
 * Insert photo preview in post block
 ************************************
 *
 * @return object
 * @param src
 **/
function showPhotoPreview(src) {
    let photosPreviewSelector = '.attachedPhotosPreview';
    let photoWrapClass = 'attachedPhotoWrap';
    let photosPreviewDiv = $(photosPreviewSelector);

    if (photosPreviewDiv.length === 0) {
        photosPreviewDiv = $('<div></div>')
            .attr('class',photosPreviewSelector.substr(1));
        photosPreviewDiv.insertAfter('.add_text');
    }

    let attachedphotoWrap = $('<div></div>')
        .attr('class', photoWrapClass);
    photosPreviewDiv.append(attachedphotoWrap);

    let photoPrevImg = $('<img src="">')
        .css('opacity', '0.5')
        .attr('class', 'photoPreview')
        .attr('src', src);
    attachedphotoWrap.append(photoPrevImg);

    let preloader = $('<img src="">')
        .attr('class', 'preloader')
        .attr('src', '/ajax-loader.gif');
    attachedphotoWrap.append(preloader);

    return {
        img: photoPrevImg,
        preloader: preloader,
        attachedphotoWrap: attachedphotoWrap
    }
}

/******* END *******/

/**
 **********************************************************************
 *  Send request to server for uploading file
 *
 **********************************************************************
 */
function uploadFile(DOMelems,file,ifImage) {
    return new Promise(function (res, rej) {
        // if it's image then there must be an image and preloader in DOMelems
        let img,preloader;
        if(ifImage) {
            img = DOMelems.preview.img;
            preloader = DOMelems.preview.preloader;
        }

        // progress bar is actually a span, which width we change from 1% to 100%
        let progressBar = DOMelems.progress.progressBar.find('span');
        let progressSpan = DOMelems.progress.progressSpan;

        let formData = new FormData();
        formData.append('userfile',file);

        let xhr = new XMLHttpRequest();
        xhr.upload.onprogress = function (e) {
            if (e.lengthComputable) {
                let loaded = e.loaded / e.total;
                if (loaded < 1) {
                    progressBar.css('width',((loaded * 100) | 0) + '%');
                    progressSpan.html(((loaded * 100) | 0) + '%');
                }
            }
        };
        xhr.upload.onload = function () {
            progressBar.css('width','100%');
            progressSpan.html('100%');

            // if it's an image then make it opacity, remove preloader, remove progress bar in 0,5s
            // if it's not an image leave progress bar untouched
            if(ifImage) {
                img.css('opacity', '1');
                preloader.remove();
                setTimeout(function () {
                    DOMelems.progress.progressWrap.remove();
                    return res({
                        'photoWrap':DOMelems.preview.attachedphotoWrap,
                        'src':xhr.responseText
                    });
                },500);
            }
            else {
                setTimeout(function () {
                    DOMelems.progress.progressWrap.remove();
                    return res(xhr.responseText);
                },500);
            }
        };
        xhr.open("POST", "/uploadfiles", true);
        xhr.setRequestHeader('X-CSRF-TOKEN', $('input[name="_token"]').attr('value'));
        xhr.send(formData);

        xhr.onreadystatechange = function () {
            if (this.readyState != DONE) return;


        }
    });
}


/**
 **********************************************************************
 *  Change the color of thumbs-up and -down on click and hover
 *
 *  Call sendLikeOrDislike() function that makes request to the server
 *  to set like or dislike
 **********************************************************************
 */
$('.pjax-container').on('click', '.likes', function () {
    let dislikes = $(this).siblings('.dislikes');
    let action;

    // learn what action user did
    if ($(this).children().hasClass('green')) {
        this.url = '/like/delete';
    }
    else {
        this.url = '/like/add';
    }

    if (dislikes.children().hasClass('red')) {
        dislikes.children().toggleClass('red');
    }
    $(this).children().toggleClass('green');

    // send request to the server to set or unset like or dislike
    sendLikeOrDislike.call(this);
});

$('.pjax-container').on('click', '.dislikes', function () {
    let likes = $(this).siblings('.likes');
    let action;

    // learn what action user did
    if ($(this).children().hasClass('red')) {
        this.url = '/dislike/delete';
    }
    else {
        this.url = '/dislike/add';
    }

    if (likes.children().hasClass('green')) {
        likes.children().toggleClass('green');
    }
    $(this).children().toggleClass('red');

    // send request to the server to set or unset like or dislike
    sendLikeOrDislike.call(this);
});

/**
 **************************************************************
 *  Send request to the server to set or unset like or dislike
 **************************************************************
 */
function sendLikeOrDislike() {
    let formData = new FormData();
    let post_id = $(this).parents('.post').attr('data-post-id');

    formData.append('post_id', post_id);

    $.ajax({
        type: 'POST',
        url: this.url,
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            console.log(data);
        }
    });
}

$('.share').click(function () {
    $('.share span').toggleClass('blue');
    $('#share').toggleClass('blue');
});
/******* END *******/

/**
 *************************************************
 *  Cancel sending data on click on submit button
 *************************************************
 */
/*$(function () {
    $('form').submit(function () {
        return false;
    });
});*/
/******* END *******/

/**
 ************************************************************************************************
 *  Resize textarea
 *
 *  User comment is pulled from textarea and is put into the invisible div('.comment_size'),
 *  then the height of the textarea is set equal to the height of invisible div('.comment_size')
 ************************************************************************************************
 */
let resizable_textarea = '.autoresizable';
let calculateHeightDiv = '.comment_size';
let oldVal = '';

$(resizable_textarea).on('change keyup keydown paste click', function (e) {
    let target = e.target;
    let curVal = $(target).val();

    if (curVal === oldVal) {
        return;
    }

    oldVal = curVal;
    $(calculateHeightDiv).html(curVal);
    let height = $(calculateHeightDiv).height();

    if (height !== 0) {
        $(target).height(height);
    }
});

/******* END *******/

/**
 ****************
 *  Add new post
 ****************
 */
function addPost() {
    let formData = new FormData();
    formData.append('message', $('#addPostTextarea').val());
    console.log('+++');

    let xhr = new XMLHttpRequest();
    xhr.open('post', '/addpost', true);
    xhr.setRequestHeader('X-CSRF-TOKEN', $('input[name="_token"]').attr('value'));
    xhr.send(formData);

    xhr.onreadystatechange = function () {
        let addNewPost = '#addPost';
        let progressBlock = '.uploadingFileProgress';
        let msg = '#addPostTextarea';
        let photosPreview = '.attachedPhotosPreview';
        let files = '#uploadedFiles';

        if (this.readyState !== DONE) return;

        if (this.status === 200) {
            // clean the inputed data (text, photos preview and progress)
            $(addNewPost).find(msg).val('');
            $(addNewPost).find(photosPreview).remove();
            $(addNewPost).find(progressBlock).remove();
            $(addNewPost).find(files).remove();

            // convert new post "string type" from server to object
            // and insert post to the website page
            let post_collection = $(this.responseText);
            let photosExist = $(this.responseText).find('.post_photo').length > 0;
            post_collection.insertAfter($(addNewPost));

            // if photos exist than resize them and make post visible
            // else immediately make it visible
            if (photosExist) {
                resizeImages(true);
            } else {
                post_collection.css('visibility', 'visible');
            }
        }
    };

    return true;
}

$('.pjax-container').on('keydown','#addPostTextarea',function (e) {
    if (e.keyCode == 13) {
        addPost();
    }
});

$('.pjax-container').on('click','#addPostBut',function () {
    addPost();
});
/******* END *******/

function loadImage(url) {
    return new Promise(function (res, rej) {
        let img = new Image();
        img.src = url;
        img.onload = function () {
            let size = {
                height: this.height,
                width: this.width
            };
            return res(size);
        };
        img.onerror = function () {
            return rej(url);
        }
    });
}

function applyGallery(post_photo, images) {
    let imgSrc = images.shift();

    if (!imgSrc) {
        $(post_photo).gpGallery('img');
        return;
    }

    return loadImage(imgSrc)
        .then(function () {
            return applyGallery(post_photo, images);
        });
}

function resizeImages(addPost = false) {
    let post_collections = $('.centerCol_inner .posts_collection');
    var post_collection, posts;

    if (addPost === false) {
        post_collection = $(post_collections[post_collections.length - 1]);
        posts = post_collection.find('.post');
    }
    else {
        post_collection = $(post_collections[0]);
        posts = post_collection.find('.post');
    }


    $.each(posts, function (post_num, post) {
        let post_photo = $(post).find('.post_photo');
        let images = [];

        $.each($(post_photo).find('img'), function (img_num, img) {
            images.push($(img).attr('src'));
        });

        applyGallery(post_photo, images);
    });

    post_collection.css('visibility', 'visible');
}

/**
 *********************************************
 * Get data on reaching the bottom of the site
 *********************************************
 */
$(window).scroll(function () {
    if ($(window).scrollTop() == $(document).height() - $(window).height()) {
        if (location.href.match(/([^/]+)$/)[0] === 'news') {
            let lastPostId = $('.centerCol_inner .posts_collection:last-child').attr('data-lastPost-id');

            $.ajax({
                type: 'POST',
                url: '/loadpostscol',
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
                },
                data: 'lastPostId=' + lastPostId,
                success: function (data) {
                    if(!data) {
                        console.log('Вы просмотрели все посты!');
                        return;
                    }

                    $('.centerCol_inner').append(data);
                    resizeImages();
                }
            });
        }
    }
});
/******* END *******/

/**
 *************************
 * Add comment to the post
 *************************
 */
$('.pjax-container').on('keydown', '.addCommentTextarea',function (e) {
    if (e.keyCode == 13) {
        let target = e.target;
        let val = $(target).val();
        let post_id = $(target).parents('.post').attr('data-post-id');

        let formData = new FormData();
        formData.append('text', val);
        formData.append('post_id', post_id);

        $.ajax({
            type: 'POST',
            url: '/addcomment',
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
            },
            data: formData,
            processData: false,
            contentType: false,
            success: function (data) {
                console.log('Comment!');
                let curPost = $(target).parents('.post');
                let post_comments = curPost.find('.post_comments');

                if (post_comments.length > 0) {
                    post_comments.append(data);
                } else {
                    post_comments = $('<div class="post_comments"></div>');
                    post_comments.append(data);
                    post_comments.insertAfter(curPost.find('.post_content'));
                }
                $(target).val('').blur();
            }
        });
    }
});
/******* END *******/

/**
 *********************************************************
 * Delete attached photo when user want to CREATE new post
 *********************************************************
 */
$('.pjax-container').on('click','.close__addPost',function (e) {
    let photo = $(e.target).siblings('.photoPreview');
    let src = $(photo).attr('src');
    let formData = new FormData();
    formData.append('src',src);

    let photos = photo.parents('.attachedPhotosPreview').find('.attachedPhotoWrap');
    if(photos.length === 1) {
        $(photo).parents('.attachedPhotosPreview').remove();
    }
    else {
        photo.parent('.attachedPhotoWrap').remove();
    }

    $.ajax({
        type: 'POST',
        url: '/deleteAttachedPhoto',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            console.log(data);
        }
    });
});
/******* END *******/

/**
 *********************************************************
 * Delete attached file when user want to CREATE new post
 *********************************************************
 */
$('.pjax-container').on('click','.close__File',function (e) {
    let fileWrap = $(e.target).parents('.uploadedFileWrap');
    let src = fileWrap.attr('data-src');
    let name = fileWrap.find('.uploadedFileName').html();
    let formData = new FormData();
    formData.append('src',src);

    if(fileWrap.siblings('.uploadedFileWrap').length == 0) {
        $('#uploadedFiles').remove();
    }
    else {
        fileWrap.remove();
    }

    $.ajax({
        type: 'POST',
        url: '/deleteAttachedFile',
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
        },
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            console.log(data);
        }
    });
});

/**
 * Send friend request
 */
function sendFriendRequest(e) {
    e.preventDefault();
    let elem = $(e.target);
    let link = elem.attr('href');

    $.ajax({
        type: 'POST',
        url: link,
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
        },
        success: function (data) {
            let wasSent = $('<div></div>')
                .attr('class', 'request_sended')
                .html('Request was sended');
            elem.replaceWith(wasSent);
        }
    });

    console.log('Request was sent');
}

/**
 * Cancel friend request
 *
 * @param e
 */
function cancelFriendRequest(e) {
    e.preventDefault();
    let elem = $(e.target);
    let link = elem.attr('href');

    $.ajax({
        type: 'POST',
        url: link,
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
        },
        success: function (data) {
            elem.parents('.friend__list__item').remove();
            console.log('Request was deleted');
        }
    });
}

/**
 * Cancel friend request
 *
 * @param e
 */
function acceptFriendRequest(e) {
    e.preventDefault();
    let elem = $(e.target);
    let link = elem.attr('href');

    $.ajax({
        type: 'POST',
        url: link,
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
        },
        success: function (data) {
            elem.parents('.friend__list__item').remove();
            console.log('Friend was added!');
        }
    });
}

/**
 * Send message using websockets
 */
(function () {
    var socket = io(':3000');

    socket.on('message', function (data) {
        console.log('From server:', data);
    }).on('server-info', function (data) {
        console.info('From server!:', data);
    });

    socket.on('error', function (error) {
        console.warn('Error', error);
    });

    socket.on('chat:message', function (data) {
        let msg = $('<div>');
        msg.attr('class','messagePartner')
            .html(data);
        let msgWrap = $('<div>');
        msgWrap.attr('class','messageInChat')
            .html(msg);
        $('#msgsWrap').append(msgWrap);
    });

    $('#sendMessageButton').on('click', function () {
        let to = location.href.match(/([^/]+)$/)[0];
        let msg = $('#messageTextfield').html();

        let msgDiv = $('<div>');
        msgDiv.attr('class','messageUser')
            .html(msg);
        let msgWrap = $('<div>');
        msgWrap.attr('class','messageInChat')
            .html(msgDiv);
        $('#msgsWrap').append(msgWrap);

        socket.emit('userMessage', {
            'msg': msg,
            'to': to
        });
    });

    /*
    conn.onmessage = function(e) {
        let msg = $('<div>');
        msg.attr('class','messagePartner')
            .html(e.data);
        let msgWrap = $('<div>');
        msgWrap.attr('class','messageInChat')
            .html(msg);
        $('#msgsWrap').append(msgWrap);

        console.log(e.data);
    };*/
})();

