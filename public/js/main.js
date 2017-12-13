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
    var classNamesArray = obj.className.split(' ');
    var className = '.' + classNamesArray.pop();
    var attach_options = {
        '.fa-camera':'image/jpg,image/jpeg,image/png',
        '.fa-file':'application/msword,application/pdf'
    }

    for(var key in attach_options) {
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
    var file, reader;
    
    var files_num = e.target.files.length;
    for(i=0;i<files_num;i++) {
        file = e.target.files[i];
        var extension = file.name.split('.').pop();
        reader = new FileReader();
        reader.readAsDataURL(file);
        if(extension == 'jpg' || extension == 'jpeg' || extension == 'png') {
            reader.onload = function (e) {
                var image = new Image();
                image.src = e.target.result;
                image.onload = function () {
                    if(!document.getElementsByClassName('attached_files')[0]) {
                        var attached_files = $('<div></div>');
                        attached_files.attr('class','attached_files');
                        attached_files.insertBefore(".attach_something");
                    }
                    img_wrapper = $('<div></div>');
                    img_wrapper.attr('class','img_wrapper');
                    $(".attached_files").append(img_wrapper);
                    tag_img = $('<img>');
                    tag_img.css('opacity','0.5');
                    img_wrapper.append(tag_img);
                    preloader = $('<img>');
                    preloader.attr('class','preloader');
                    preloader.attr('src','/ajax-loader.gif');
                    img_wrapper.append(preloader);
                    tag_img.attr('src',this.src);
                };
            };
        }

        var div_progress = $('<div></div>');
        div_progress.attr('class','uploading_file_progress');
        $('.add_new_post').append(div_progress);

        var progress_bar_wrapper = $('<div></div>');
        progress_bar_wrapper.attr('class','progress_bar_wrapper');
        div_progress.append(progress_bar_wrapper);

        var progress_bar = $('<progress></progress>');
        progress_bar.attr({
            'value':'0',
            'max':'100'
        });
        progress_bar_wrapper.append(progress_bar);

        var span_progress = $('<span></span>');
        span_progress.attr('class','uploading_progress_percents');
        progress_bar_wrapper.append(span_progress);

        var span_filename = $('<span></span>');
        span_filename.attr('class','uploading_filename');
        span_filename.html(file.name);
        div_progress.append(span_filename);

        var formData    = new FormData();
        formData.append('userfile', file);
        formData.append('user_id', $('.add_new_post #author_id').html());
        
        var xhr = new XMLHttpRequest();
        xhr.upload.onprogress = function(e) {
            if(e.lengthComputable) {
                var loaded = e.loaded / e.total;
                if(loaded < 1) {
                    progress_bar.val((loaded * 100)|0);
                    span_progress.html(((loaded * 100)|0) + '%');
                    console.log(((loaded * 100)|0) + '%');
                }
            }
        }
        xhr.upload.onload = function (e) {
            console.log(progress_bar.val());
            if(progress_bar.val() < 100) {
                var progressBarValue = progress_bar.val();
                var timerId = setTimeout(function tick() {
                    if(progressBarValue < 100) {
                        progressBarValue++;
                        progress_bar.val(progressBarValue);
                        span_progress.html(progressBarValue + '%');
                        setTimeout(tick, 5);
                    }
                    else if(extension == 'jpg' || extension == 'jpeg' || extension == 'png') {
                        tag_img.css('opacity','1');
                        preloader.remove();
                    }
                }, 1000);
            }
        };
        xhr.open("POST", "/uploadfiles", true);
        xhr.setRequestHeader('X-CSRF-TOKEN', $('input[name="_token"]').attr('value'));
        xhr.send(formData);

        xhr.onreadystatechange = function() {
            if (this.readyState != 4) return;

            var text = this.responseText;
            $('.uploading_filename').on('click', function () {
                location.href = text;
            });
            console.log( this.responseText );
        }
    }
});
/******* END *******/

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
    var comment = $('#comment_author_textarea').val();
    $('.comment_size').html(comment);
    var height = $('.comment_size').height();
    $('#test').html(height);
    if(height != 0) {
        $('#comment_author_textarea').height(height);
    }
});

// If commenter hold key for long time, it will resize comment_textarea
$('#comment_author_textarea').keydown(function() {
    var comment = $('#comment_author_textarea').val();
    $('.comment_size').html(comment);
    var height = $('.comment_size').height();
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
    var message     = $('#comment_author_textarea').val();
    var author_id   = $('#author_id').html();
    var photos      = $('#upload');
    var photos_num  = photos[0].files.length;


    var data_send = {
        "message"   : message,
        "author_id" : author_id
    };

    // if some photos were attached
    if(photos_num > 0)
    {
        for(i=0;i<photos_num;i++)
        {
            
        }
    }

    // showUploadProgress();
}

$('#comment_author_textarea').on('keydown', function(e) {
    if(e.keyCode == 13) {
        addPost();
    }
});
/******* END *******/

