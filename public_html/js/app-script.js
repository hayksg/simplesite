$(function(){
    /////   For scroll top   //////////////////////////////////////////////////

    $(window).scroll(function () {
        if ($(this).scrollTop() > 500) {
            $('#back-to-top').fadeIn();
        } else {
            $('#back-to-top').fadeOut();
        }
    });

    // scroll body to 0px on click
    $('#back-to-top').click(function () {
        $('#back-to-top').tooltip('hide');
        $('body,html').animate({
            scrollTop: 0
        }, 500);
        return false;
    });

    $('#back-to-top').tooltip('show');

    /////   If in breadcrumbs only one element then remove breadcrumbs  and show search block //////

    if ($('ul.breadcrumb li').length == 0) {
        $('ul.breadcrumb').remove();
        $('.app-search').css('display', 'block');
        $('.breadcrumb-box').css('marginTop', '18px');
    }

    /////   For read-more button   ////////////////////////////////////////////

    if ($(document).width() < 400) {
        $('span.read-more').removeClass('pull-right');
        $('span.read-more').css('display', 'block');
    }

    /////   For register form   ///////////////////////////////////////////////

    if ($(document).width() > 768) {
        $('#form-register div.col-sm-3').addClass('text-right');

        $('#form-register .app-captcha img').addClass('col-sm-6');
        $('#form-register .app-captcha input[type="text"]').addClass('col-s-6');
        $('.captcha-box').addClass('row');
    } else {
        $('#form-register .app-captcha img').css('marginBottom', '10px');
    }

    $('#form-register .app-captcha input[type="text"]').addClass('form-control')
                                                       .attr('id', 'captcha')
                                                       .css('width', 'auto');

    /////   For form checkbox   ///////////////////////////////////////////////

    $('#login-form label:eq(2)').removeClass('control-label').css('textAlign', 'right');
    $('#login-form div.form-group:eq(2) input[type="checkbox"]').removeClass('form-control');

    /////   For confirm plugin   //////////////////////////////////////////////

    $('.confirm-plugin-delete-category').jConfirmAction({
        question: 'All nested categories and articles will be deleted. Are You sure?',
        noText: 'Cancel'
    });

    $('.confirm-plugin-delete-article').jConfirmAction({
        question: 'All nested comments will be deleted. Are You sure?',
        noText: 'Cancel'
    });

    $('.confirm-plugin-delete-comment').jConfirmAction({
        question: 'Are You sure?',
        noText: 'Cancel'
    });

    /////   For admin user form   /////////////////////////////////////////////

    if ($(document).width() < 768) {
        $('.admin-user-form-box form .form-group').css('marginBottom', '5px');
        $('.admin-user-form-box form').eq(0).css('marginBottom', '15px');
        $('.admin-user-form-box form').eq(1).removeClass('text-right');
    }

    /////   For user delete modal   ///////////////////////////////////////////

    $('#delete-user').click('on', function(){
        $('.modal-form').submit();
    });

    /////   For user search   /////////////////////////////////////////////////

    $('#user-search').click('on', function () {
            var formData = $('form#user-search-form input').val();
            var pageNumber = $('form#user-search-form').attr('data-page'); //to go back to the same page

            $('#user-search-result').html('');
            $('#user-search-result').css('display', 'none');

            $.ajax({
                url: '/admin/users/search',
                type: 'post',
                dataType: 'json',
                data: {formData: formData, pageNumber: pageNumber},
                success: function (data) {
                    if (data[0]) {
                        for (var key in data[0]) {
                            var value = '<li><a href="/admin/users/edit/' + data[0][key]['id'] + '/' + data[1] + '">' + data[0][key]['name'] + '</a></li>';
                            $('#user-search-result').append(value);
                        }
                        if (($('#user-search-result').is(':visible')) === false) {
                            $('#user-search-result').slideToggle(300);
                        }
                    } else {
                        $('#user-search-result').append('<li>User not found</li>');

                        if (($('#user-search-result').is(':visible')) === false) {
                            $('#user-search-result').slideToggle(300);
                        }
                    }
                }
            });

            return false;
        })


    $(document).on('click', function(){
        $('#user-search-result').css('display', 'none');
    });

    if ($(document).width() < 768) {
        $('.user-search-box').css({
            float: 'inherit',
            width: '100%'
        });
        $('.user-search-box form').css({
            width: '100%'
        });
    }

    /////   For menu vert   ///////////////////////////////////////////////////

    if ($(document).width() < 768) {
        $('ul.menu_vert').removeClass('menu_vert').addClass('topnav');
    } else {
        $('.menu_vert').liMenuVert({
            delayShow:300,		//Задержка перед появлением выпадающего меню (ms)
            delayHide:300	    //Задержка перед исчезанием выпадающего меню (ms)
        });
    }

    //  In order did not work parent element a  //

    $('ul.menu_vert li a').on('click', function(){
        if ($(this).parent('li').has('ul').length != 0) {
            return false;
        }
    });

    /////   For add category button   /////////////////////////////////////////

    if ($(document).width() < 224) {
        $('div.add-category-button-box').removeClass('col-xs-6').addClass('float', 'left');
        $('div.add-category-button-box div').removeClass('pull-right').addClass('pull-left');
    }

    /////   For admin add-article checkbox   //////////////////////////////////

    $('#article label:eq(5)').removeClass('control-label').css('textAlign', 'right');

    /////   For button input file   ///////////////////////////////////////////

    $(":file").jfilestyle({inputSize: "50%"});

    /////   For big image in edit article   ///////////////////////////////////

    $('#article p img').hover(
        function(){
            $(this).stop();
            $(this).animate({
                width: '50%',
                height: '50%'
            }, 500);
        },
        function(){
            $(this).stop();
            $(this).animate({
                width: '100px',
                height: '50%'
            }, 500);
        }
    );

    $('.article-image-box-admin-page img').hover(
        function(){
            $(this).css('position', 'absolute').css('z-index', '100');
            $(this).stop();
            if ($(document).width() < 550) {
                $(this).animate({
                    width: '30%',
                    height: '10%'
                }, 500);
            } else {
                $(this).animate({
                    width: '20%',
                    height: '35%'
                }, 500);
            }
        },
        function(){
            $(this).css('z-index', '0');
            $(this).stop();
            $(this).animate({
                width: '30px',
                height: '25px'
            }, 500);
        }
    );

    /////   If the words do not fit in the box   //////////////////////////////

    if ($(document).width() < 584) {
        $('.articles-count-per-page input:eq(0)').attr('title', 'Number of articles on the home page');
        $('.articles-count-per-page input:eq(1)').attr('title', 'Number of articles on the category page');
    }

    /////   For tooltip   /////////////////////////////////////////////////////

    $('[data-toggle="tooltip"]').tooltip();

    /////   For add like and dislike   ////////////////////////////////////////

    if ($(document).width() < 400) {
        $('.like-block, .dislike-block').css('display', 'block');
        $('#like-error').css('top', '65px');
    }

    var likeMessage = function (message) {
        $('#like-error').text(message).fadeIn(300);
        setTimeout(function() {
            $('#like-error').text(message).fadeOut(300);
        }, 3000);
    };

    var addLike = function () {
        var identity = $('#addLike').attr('data-identity');
        if (identity == 'false') {
            likeMessage('Sign in to make your opinion count.');
            return false;
        }

        var articleId = $('#addLike').attr('data-id');

        $.ajax({
            url: '/article/add-like',
            type: 'post',
            dataType: 'json',
            data: {articleId: articleId},
            success: function (data) {
                if (data['success']) {
                    $('#addLike span').text(data['articleLikeNew']);
                    $('#addDislike span').text(data['articleDislikeNew']);
                    $('#addLike').css('color', '#369');
                    $('#addDislike').css('color', '#999');
                    $('#like-error').text('');
                } else {
                    likeMessage('You already liked it.');
                }
            },
        });
        return false;
    };

    var addDislike = function () {
        var identity = $('#addLike').attr('data-identity');
        if (identity == 'false') {
            likeMessage('Sign in to make your opinion count.');
            return false;
        }

        var articleId = $('#addDislike').attr('data-id');

        $.ajax({
            url: '/article/add-dislike',
            type: 'post',
            dataType: 'json',
            data: {articleId: articleId},
            success: function (data) {
                if (data['success']) {
                    $('#addDislike span').text(data['articleDislikeNew']);
                    $('#addLike span').text(data['articleLikeNew']);
                    $('#addDislike').css('color', '#369');
                    $('#addLike').css('color', '#999');
                    $('#like-error').text('');
                } else {
                    likeMessage('You already disliked it.');
                }
            },
        });
        return false;
    }

    $('#addLike').on('click', addLike);
    $('#addDislike').on('click', addDislike);

    ///////////////   For add comment   ///////////////////////////////////////

    var nl2br = function (str) {
        return str.replace(/([^>])\n/g, '$1<br/>');
    }

    var commentsCount = $('.comment-block h3.panel-title').length;
    $('.comments-count').text('(' + commentsCount + ')');

    var getFullDate = function () {
        var date = new Date();
        var monthNames = ["January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];

        return date.getDate() + ' ' +  monthNames[date.getMonth()] + ' ' + date.getFullYear();
    };

    var addComment = function () {
        var formValue = $('.comment-form-block form#comment').serialize();

        $.ajax({
            url: '/article/add-comment',
            type: 'post',
            dataType: 'json',
            data: formValue,
            success: function (data) {
                if (data.commentText) {
                    var comment = nl2br(data.commentText);
                    $('#comment-text').html(comment);

                    $('#new-comment span').append("&nbsp;&nbsp;<span>" + getFullDate() + "</span>");
                    $('#new-comment').show();
                    $('.comment-form-block').hide('slow');
                    $('#form-for-comment-button').attr('disabled', 'disabled');

                    commentsCount++;
                    $('.comments-count').text('(' + commentsCount + ')');
                } else if (data.errors) {
                    $('#comment-form-errors').html('');

                    for (var key in data.errors) {
                        $('#comment-form-errors').append("<li>" + data.errors[key] + "</li>");
                    }
                }
            }
        });

        return false;
    };

    $('.comment-form-block form#comment').on('submit', addComment);

    $('#form-for-comment-button').on('click', function(){
        $('.comment-form-block #userComment').val('');
        $('#comment-form-errors li').remove();
    });

    /////   Admin article search for comments /////////////////////////////////

    var adminArticleSearch = function () {
        var formData = $('.admin-article-search-block form').serialize();

        $('ul.admin-article-search-result li').remove();
        $('ul.admin-article-search-result').css('display', 'none');

        $.ajax({
            url: '/admin/comments/search',
            type: 'post',
            dataType: 'json',
            data: formData,
            success: function(data){
                if (data) {
                    for (var key in data) {
                        var appendData = '<li><a href="/admin/comments/' + data[key]['id'] + '" class="hover-underline">' + data[key]['title'] +  '</a></li>'
                        $('ul.admin-article-search-result').append(appendData);
                    }

                    if (($('ul.admin-article-search-result').is(':visible')) === false) {
                        $('ul.admin-article-search-result').slideToggle(300);
                    }
                } else {
                    $('ul.admin-article-search-result').append('<li>Nothing found</li>');

                    if (($('ul.admin-article-search-result').is(':visible')) === false) {
                        $('ul.admin-article-search-result').slideToggle(300);
                    }
                }
            }
        });

        return false;
    };

    $('.admin-article-search-block form').on('submit', adminArticleSearch);

    $(document).on('click', function(){
        $('ul.admin-article-search-result').css('display', 'none');
    });

    if ($(document).width() < 768) {
        $('ul.admin-article-search-result').css({
            width: '50%',
            left: '50%',
            top: '53px',
            maxHeight: '150px',
        });
    }

    /////   Application article search ////////////////////////////////////////

    var appArticleSearch = function () {
        var formData = $('.app-search form').serialize();

        $('ul.app-article-search-result li').remove();
        $('ul.app-article-search-result').css('display', 'none');

        $.ajax({
            url: '/article/search',
            type: 'post',
            dataType: 'json',
            data: formData,
            success: function(data){
                if (data) {
                    for (var key in data) {
                        var appendData = '<li><a href="/article/' + data[key]['id'] + '" class="hover-underline">' + data[key]['title'] +  '</a></li>'
                        $('ul.app-article-search-result').append(appendData);
                    }

                    if (($('ul.app-article-search-result').is(':visible')) === false) {
                        $('ul.app-article-search-result').slideToggle(300);
                    }
                } else {
                    $('ul.app-article-search-result li').remove();
                    $('ul.app-article-search-result').append('<li>Nothing found</li>').css('padding', '5px 5px 5px 10px');

                    if (($('ul.app-article-search-result').is(':visible')) === false) {
                        $('ul.app-article-search-result').slideToggle(300);
                    }
                }

            }
        });

        return false;
    };

    $('.app-search form').on('submit', appArticleSearch);

    $(document).on('click', function(){
        $('ul.app-article-search-result').css('display', 'none');
    });

    if ($(document).width() < 768) {
        $('ul.app-article-search-result').css({
            right: 0,
            top: '105px',
            maxHeight: '150px',
        });
    }

    /////   for soc icons div   ///////////////////////////////////////////////

    if ($(document).width() < 768) {
        $('div.share42init').attr('data-margin', 0);
    }

    /////////   menu accordion   //////////////////////////////////////////////

    if ($(".topnav").length > 0) {
        $(".topnav").accordion({
            accordion:true,
            speed: 500,
            closedSign: '<span class="caret"></span>',
            openedSign: '<span class="dropup"><span class="caret"></span></span>'
        });
    }

    // In order don't work link witch has children

    $('ul.topnav li a').on('click', function(){
        if ($(this).parent('li').has('ul').length != 0) {
            return false;
        }
    });

    /////   For portfolio   ///////////////////////////////////////////////////

    if ($(document).width() < 768) {
        $('.portfolio').css('marginBottom', '20px');
    }

    /////   END   /////////////////////////////////////////////////////////////
});
