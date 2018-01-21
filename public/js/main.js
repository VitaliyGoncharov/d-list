console.log('В код не смотри, глаза побереги!');

const DONE = 4;
addPostFiles = [];

$(document).pjax('.leftMenuLinks, .author', '.pjax-container', {
    fragment: '.pjax-container'
});

$(document).on('pjax:success', function () {
    if (location.href === 'http://devvit.ru/news' && $('.posts_collection[data-resized=""]').length !== 0) {
        resizeImages();
    }
});

$(document).ready(function () {
    if (location.href === 'http://devvit.ru/news' && $('.posts_collection[data-resized=""]').length !== 0) {
        resizeImages();
    }
});

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
    let className = '.' + classNamesArray.pop();
    let attach_options = {
        '.fa-camera': 'image/jpg,image/jpeg,image/png',
        '.fa-file': ''
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
$("#upload").change(function (e) {
    // get to know how many files user attached
    let files = e.target.files;
    let files_num = files.length;
    let uploaded_files_num = $('.addNewPost').find('.uploadingFileProgress').length;
    if (files_num > 8) files_num = 8;


    if (uploaded_files_num > 8 || uploaded_files_num + files_num > 8) {
        console.log('More than 8 is not allowed');
        return;
    }
    if (files_num > 8) files_num = 8;

    // show preview of each file if it's a picture,
    // show preloader to each file if it's a picture,
    // send request for uploading file,
    // show uploading progress with percents and initial filename
    /*window.mytest = function forEachFiles(i = 0) {
        if(i<files_num) {
            let file, reader, extension;
            file = e.target.files[i];
            extension = file.name.split('.').pop();
            reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = function (e) {
                console.log(file.name+' - '+i);
                showUploadProgress(reader,file,extension,e,i);
            }
        }
    };
    mytest();*/

    $.each(files, function (num, file) {
        console.log(file.name);
    });
});

/******* END *******/

/**
 ************************************
 * Insert photo preview in post block

 * @param reader Array
 * @param file Array
 * @param extension Array
 * @param e
 * @param i
 ************************************
 **/
function showUploadProgress(reader, file, extension, e, i) {
    // if it's a picture then show its preview and rotating preloader
    if (extension == 'jpg' || extension == 'jpeg' || extension == 'png') {
        let image = new Image();
        image.src = e.target.result;
        image.onload = function (e) {
            let preview = insertPhotoPreview.call(this, i);
            let infForUpload = showProgressBar();
            infForUpload.preview = preview;
            uploadFileRequest(
                infForUpload.formData,
                infForUpload.progressBar,
                infForUpload.progressSpan,
                infForUpload.extension,
                infForUpload.preview,
                i
            );
        }
    } else {
        let infForUpload = showProgressBar(); // не загружать, пока индикатор бара не достигнет 100 (не фото) РЕАЛИЗОВАТЬ!!!
        uploadFileRequest(
            infForUpload.formData,
            infForUpload.progressBar,
            infForUpload.progressSpan,
            infForUpload.extension
        );
    }

    function showProgressBar() {
        let progressWrapClass = 'uploadingFileProgress';
        let progressWrap = $('<div></div>');
        progressWrap.attr('class', progressWrapClass);
        $('.addNewPost').append(progressWrap);

        console.log(progressWrap);

        let progressBarWrap = $('<div></div>');
        progressBarWrap.attr('class', 'progressBarWrap');
        progressWrap.append(progressBarWrap);

        let progressBar = $('<progress></progress>');
        progressBar.attr({
            'value': '0',
            'max': '100'
        });
        progressBarWrap.append(progressBar);

        let progressSpan = $('<span></span>');
        progressSpan.attr('class', 'uploadingProgressPercents');
        progressSpan.html('0%');
        progressBarWrap.append(progressSpan);

        let filenameSpan = $('<span></span>');
        filenameSpan.attr('class', 'uploadingFilename');
        filenameSpan.html(file.name);
        progressWrap.append(filenameSpan);

        let formData = new FormData();
        formData.append('userfile', file);
        formData.append('user_id', $('.addNewPost #user_id').html());

        return {
            formData: formData,
            progressBar: progressBar,
            progressSpan: progressSpan,
            extension: extension
        }
    }
}

/******* END *******/

/**
 ************************************
 * Insert photo preview in post block
 ************************************
 *
 * @param i
 * @return object
 **/
function insertPhotoPreview(i) {
    let photosPreviewDivClass = 'attachedPhotosPreview';
    let photoWrapClass = 'attachedPhotoWrap';

    if ($('.' + photosPreviewDivClass).length == 0) {
        let photosPreviewDiv = $('<div></div>');
        photosPreviewDiv.attr('class', photosPreviewDivClass);
        photosPreviewDiv.insertBefore('.attachSomething');
    }

    let attachedphotoWrap = $('<div></div>');
    attachedphotoWrap.attr('class', photoWrapClass);
    $('.' + photosPreviewDivClass).append(attachedphotoWrap);

    let photoPrevImg = $('<img>');
    photoPrevImg
        .css('opacity', '0.5')
        .attr('id', 'photoPreview_' + i)
        .attr('src', this.src);
    attachedphotoWrap.append(photoPrevImg);

    let preloader = $('<img>');
    preloader
        .attr('class', 'preloader')
        .attr('id', 'photoPreloader_' + i)
        .attr('src', '/ajax-loader.gif');
    attachedphotoWrap.append(preloader);

    return {
        img: $(photoPrevImg),
        preloader: $(preloader)
    }
}

/******* END *******/

/**
 **********************************************************************
 *  Send request to server for uploading file
 *
 *  @return filePath (from the server)
 **********************************************************************
 */
function uploadFileRequest(formData, progressBar, progressSpan, extension, preview = null, i) {
    let xhr = new XMLHttpRequest();
    xhr.upload.onprogress = function (e) {
        if (e.lengthComputable) {
            let loaded = e.loaded / e.total;
            if (loaded < 1) {
                progressBar.val((loaded * 100) | 0);
                progressSpan.html(((loaded * 100) | 0) + '%');
                console.log(((loaded * 100) | 0) + '%');
            }
        }
    }
    xhr.upload.onload = function (e) {
        if (progressBar.val() < 100) {
            let progressBarValue = progressBar.val();
            let timerId = setTimeout(function tick() {
                if (progressBarValue < 100) {
                    progressBarValue++;
                    progressBar.val(progressBarValue);
                    progressSpan.html(progressBarValue + '%');
                    setTimeout(tick, 5);
                }
                else {
                    if (extension == 'jpg' || extension == 'jpeg' || extension == 'png') {
                        preview.img.css('opacity', '1');
                        preview.preloader.remove();
                    }

                    i++;
                    mytest(i);
                }
            }, 1000);
        }
    };
    xhr.open("POST", "/uploadfiles", true);
    xhr.setRequestHeader('X-CSRF-TOKEN', $('input[name="_token"]').attr('value'));
    xhr.send(formData);

    xhr.onreadystatechange = function () {
        if (this.readyState != DONE) return;

        addPostFiles.push(this.responseText);
    }
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
        action = 'delete like';
    }
    else {
        action = 'like';
    }

    if (dislikes.children().hasClass('red')) {
        dislikes.children().toggleClass('red');
    }
    $(this).children().toggleClass('green');
    this.action = action;

    // send request to the server to set or unset like or dislike
    sendLikeOrDislike.call(this);
});

$('.pjax-container').on('click', '.dislikes', function () {
    let likes = $(this).siblings('.likes');
    let action;

    // learn what action user did
    if ($(this).children().hasClass('red')) {
        action = 'delete dislike';
    }
    else {
        action = 'dislike';
    }

    if (likes.children().hasClass('green')) {
        likes.children().toggleClass('green');
    }
    $(this).children().toggleClass('red');
    this.action = action;

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
    formData.append('action', this.action);

    $.ajax({
        type: 'POST',
        url: '/likes',
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
$(function () {
    $('form').submit(function () {
        return false;
    });
});
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

$(resizable_textarea).on('change keyup paste click', function (e) {
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

    if (addPostFiles.length !== 0) {
        formData.append('files', JSON.stringify(addPostFiles));
    }

    let xhr = new XMLHttpRequest();
    xhr.open('post', '/addpost', true);
    xhr.setRequestHeader('X-CSRF-TOKEN', $('input[name="_token"]').attr('value'));
    xhr.send(formData);

    console.log('READY');

    xhr.onreadystatechange = function () {
        let addNewPost = '#addPost';
        let progressBlock = '.uploadingFileProgress';
        let msg = '#addPostTextarea';
        let photosPreview = '.attachedPhotosPreview';

        if (this.readyState != DONE) return;

        if (this.status === 200) {
            // clean the array of files (if user attached files)
            if ($(addNewPost).find(progressBlock).length > 0) {
                addPostFiles = [];
            }

            // clean the inputed data (text, photos and other files)
            $(addNewPost).find(progressBlock).remove();
            $(addNewPost).find(msg).val('');
            $(addNewPost).find(photosPreview).remove();

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

$('#addPostTextarea').on('keydown', function (e) {
    if (e.keyCode == 13) {
        addPost();
    }
});

$('#addPostBut').on('click', function () {
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
        if (location.href === 'http://devvit.ru/news') {
            let lastPostId = $('.centerCol_inner .posts_collection:last-child').attr('data-lastPost-id');
            $.ajax({
                type: 'POST',
                url: '/loadpostscol',
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
                },
                data: 'lastPostId=' + lastPostId,
                success: function (data) {
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
$('.addCommentTextarea').on('keydown', function (e) {
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

