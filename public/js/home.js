$(document).ready(function(){
    var date = new Date();
    var utc = -date.getTimezoneOffset()/60;
    $('#utc').attr('value',utc);


    $('.checkbox').on('click', function() {
        $('.checkbox_box').attr('checked');
    });

    $('.g-recaptcha').css({'display':'none'});

    $('#reg_password').on('focus', function() {
        $('.g-recaptcha').slideDown("slow");
    });

    /**
     * For validator
     *
     */
    let ids = [
        'name',
        'surname',
        'reg_email',
        'reg_password',
    ];

    Function.prototype.delayed = function (ms) {
        let timer = 0;
        let callback = this;
        return function() {
            input_id = this.id;
            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    };

    for(let i=0;i<ids.length;i++)
    {
        document.getElementById(ids[i]).addEventListener('keyup', validate.delayed(500));
        document.getElementById(ids[i]).addEventListener('focusout', validate.delayed(500));
    }

});


/**
 * Input validator
 *
 */
function validate() {
    let parent = $('#' + input_id).parent().parent().find('.error');

    $.ajax({
        type: 'POST',
        async: true,
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').attr('value')
        },
        url: '/checkuserinput',
        data: input_id + '=' + $('#' + input_id).val(),
        success: function(data) {

            if(data == 'success')
            {
                $('#' + input_id).css('border-color', 'grey');
                parent.find('.validation-message').remove();


                $(parent).append(
                    "<div class='validation-message'><div class='float-right'><img src=\"/success-mark.png\"></div></div>"
                );
            }
            else
            {
                $('#' + input_id).css('border-color', 'red');
                parent.find('.validation-message').remove();

                $(parent).append(
                    "<div class='validation-message'><div class='alert-danger float-right'><p></p></div></div>"
                );

                $(parent.find('p')).html(data);
            }


        }
    });
}

/**
 * Carousel
 *
 */
var slideIndex = 1;
showSlides(slideIndex);

setInterval(function () {
    plusSlides(1);
},6000);

function plusSlides(n) {
    showSlides(slideIndex += n);
}

function currentSlide(n) {
    showSlides(slideIndex = n);
}

function showSlides(n) {
    let i;
    let slides = document.getElementsByClassName("mainSlides");
    let dots = document.getElementsByClassName("dot");

    if(n > slides.length) {
        slideIndex = 1;
    }
    if(n < 1) {
        slideIndex = slides.length;
    }

    for(i=0;i<slides.length;i++) {
        slides[i].style.display = "none";
    }

    for(i=0;i<dots.length;i++) {
        dots[i].className = dots[i].className.replace("active","");
    }
    console.log(slideIndex);

    slides[slideIndex - 1].style.display = "block";
    dots[slideIndex - 1].className += " active";
}
