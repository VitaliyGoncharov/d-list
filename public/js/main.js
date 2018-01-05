
console.log('В код не смотри, глаза побереги!');

const DONE = 4;
addPostFiles = [];

/**
 ********************************************************
 * Click input[type=file] when user click on upload icon
 * and restrict the available extensions
 ********************************************************
 * @param obj 
 *
 **/
function clickUpload(obj)
{
    let classNamesArray = obj.className.split(' ');
    let className = '.' + classNamesArray.pop();
    let attach_options = {
        '.fa-camera':'image/jpg,image/jpeg,image/png',
        '.fa-file':''
    }

    for(let key in attach_options) {
        if(className == key) {
            $('#upload').attr('accept',attach_options[key]).click();
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
$("#upload").change(function(e) {
    // get to know how many files user attached
    let files_num = e.target.files.length;
    let uploaded_files_num = $('.addNewPost').find('.uploadingFileProgress').length;
    if(files_num > 8) files_num = 8;
    

    if(uploaded_files_num > 8 || uploaded_files_num+files_num > 8) {
        console.log('More than 8 is not allowed');
        return;
    }
    if(files_num > 8) files_num = 8;

    // show preview of each file if it's a picture,
    // show preloader to each file if it's a picture,
    // send request for uploading file,
    // show uploading progress with percents and initial filename
    window.mytest = function forEachFiles(i = 0) {
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
    mytest();
});
/******* END *******/

/**
 ************************************
 * Insert photo preview in post block

 * @param reader Array
 * @param file Array
 * @param extension Array
 ************************************
 **/
function showUploadProgress(reader,file,extension,e,i)
{
    // if it's a picture then show its preview and rotating preloader
    if(extension == 'jpg' || extension == 'jpeg' || extension == 'png') {
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

    function showProgressBar()
    {
        let progressWrapClass = 'uploadingFileProgress';
        let progressWrap = $('<div></div>');
        progressWrap.attr('class',progressWrapClass);
        $('.addNewPost').append(progressWrap);

        let progressBarWrap = $('<div></div>');
        progressBarWrap.attr('class','progressBarWrap');
        progressWrap.append(progressBarWrap);

        let progressBar = $('<progress></progress>');
        progressBar.attr({
            'value':'0',
            'max':'100'
        });
        progressBarWrap.append(progressBar);

        let progressSpan = $('<span></span>');
        progressSpan.attr('class','uploadingProgressPercents');
        progressSpan.html('0%');
        progressBarWrap.append(progressSpan);

        let filenameSpan = $('<span></span>');
        filenameSpan.attr('class','uploadingFilename');
        filenameSpan.html(file.name);
        progressWrap.append(filenameSpan);

        let formData = new FormData();
        formData.append('userfile',file);
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

 * @param reader Array
 * @param file Array
 * @param extension Array
 ************************************
 **/
function insertPhotoPreview(i)
{
    let photosPreviewDivClass = 'attachedPhotosPreview';
    let photoWrapClass = 'attachedPhotoWrap';
    let div = '<div></div>';

    if($('.'+photosPreviewDivClass).length == 0) {
        let photosPreviewDiv = $(div);
        photosPreviewDiv.attr('class',photosPreviewDivClass);
        photosPreviewDiv.insertBefore('.attachSomething');
    }

    let attachedphotoWrap = $(div);
    attachedphotoWrap.attr('class',photoWrapClass);
    $('.'+photosPreviewDivClass).append(attachedphotoWrap);

    let photoPrevImg = $('<img>');
    photoPrevImg.css('opacity','0.5');
    photoPrevImg.attr('id','photoPreview_'+i);
    photoPrevImg.attr('src',this.src);
    attachedphotoWrap.append(photoPrevImg);

    let preloader = $('<img>');
    preloader.attr('class','preloader');
    preloader.attr('id','photoPreloader_'+i);
    preloader.attr('src','/ajax-loader.gif');
    attachedphotoWrap.append(preloader);

    return {
        img: $(photoPrevImg),
        preloader: $(preloader)
    }
}
/******* END *******/

/**
 **********************************************************************
 *  Script send request to server for uploading file
 *  
 *  @return filePath (from the server) 
 **********************************************************************
 */
function uploadFileRequest(formData, progressBar, progressSpan, extension, preview = null,i)
{
    let xhr = new XMLHttpRequest();
    xhr.upload.onprogress = function(e) {
        if(e.lengthComputable) {
            let loaded = e.loaded / e.total;
            if(loaded < 1) {
                progressBar.val((loaded * 100)|0);
                progressSpan.html(((loaded * 100)|0) + '%');
                console.log(((loaded * 100)|0) + '%');
            }
        }
    }
    xhr.upload.onload = function (e) {
        if(progressBar.val() < 100) {
            let progressBarValue = progressBar.val();
            let timerId = setTimeout(function tick() {
                if(progressBarValue < 100) {
                    progressBarValue++;
                    progressBar.val(progressBarValue);
                    progressSpan.html(progressBarValue + '%');
                    setTimeout(tick, 5);
                }
                else {
                    if(extension == 'jpg' || extension == 'jpeg' || extension == 'png') {
                        preview.img.css('opacity','1');
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

    xhr.onreadystatechange = function() {
        if (this.readyState != DONE) return;

        addPostFiles.push(this.responseText);
    }
}

/**
 **********************************************************************
 *  Script changes the color of thumbs-up and -down on click and hover
 **********************************************************************
 */
$('.likes').click(function () {
    $(this).children().toggleClass('green');
});

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
$('#comment_author_textarea').keyup(function() {
    let comment = $('#comment_author_textarea').val();
    $('.comment_size').html(comment);
    let height = $('.comment_size').height();
    $('#test').html(height);
    if(height != 0) {
        $('#comment_author_textarea').height(height);
    }
});

// If commenter hold key for long time, it will resize comment_textarea
$('#comment_author_textarea').keydown(function() {
    let comment = $('#comment_author_textarea').val();
    $('.comment_size').html(comment);
    let height = $('.comment_size').height();
    $('#test').html(height);

    if(height != 0) {
        $('#comment_author_textarea').height(height);
    }
});
/******* END *******/

/**
 ****************
 *  Add new post
 ****************
 */
function addPost()
{
    let formData = new FormData();
    formData.append('user_id',$('#user_id').html());
    formData.append('files',JSON.stringify(addPostFiles));
    formData.append('message',$('.newPostMessage').val());

    let xhr = new XMLHttpRequest();
    xhr.open('post','/addpost',true);
    xhr.setRequestHeader('X-CSRF-TOKEN',$('input[name="_token"]').attr('value'));
    xhr.send(formData);

    console.log('READY');

    xhr.onreadystatechange = function () {
        if (this.readyState != DONE) return;

        if(this.status == 200) {
            // clean the array of files (if user attached files)
            addPostFiles = [];
            
            $('.addNewPost').find('.uploadingFileProgress').remove();
            $('.addNewPost').find('.newPostMessage').val('');

            $(this.responseText).insertAfter($('.addNewPost'));
            let post_photo = $(this.responseText).find('.post_photo')[0];

            if(post_photo) {
                foreachPostPhoto();
                $('.addNewPost').find('.attachedPhotosPreview').remove();
            }
        }
    }
    
    return true;
}

$('#comment_author_textarea').on('keydown', function(e) {
    if(e.keyCode == 13) {
        addPost();
    }
});

$('#addPostBut').on('click', function (e) {
    addPost();
});
/******* END *******/

function loadImage (url) {
    return new Promise(function (res, rej) {
        let img = new Image();
        img.src = url;
        img.onload = function () {
            return res(url);
        }
        img.onerror = function () {
            return rej(url);
        }
    });
}

function displayPhotoBlock (post_num, post_photo, images) {
    let imgSrc = images.shift();

    if(!imgSrc) {
        $(post_photo).gpGallery('img');
        $(post_photo).attr('data-resized','done');
        return;
    }

    return loadImage(imgSrc)
    .then(function () {
        return displayPhotoBlock(post_num, post_photo, images);
    });
}

$(document).ready(function() {
    foreachPostPhoto();
});

function foreachPostPhoto() {
    $.each($('.post_photo[data-resized=""]'), function (post_num, post_photo) {
        foreachImgs(post_num,post_photo);
    });
}

function foreachImgs(post_num,post_photo) {
    let images = [];

    $.each($(post_photo).find('img'), function (img_num, img) {
        images.push($(img).attr('src'));
    });

    displayPhotoBlock(post_num, post_photo, images);
}