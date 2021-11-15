class App {
    static navigate(link) {

        // $('#test').data("test", link);
        //let user = localStorage.getItem("user");

        let params = {
            action: 'navigate'
        };

        let linkArray = link.split("&");
        linkArray.forEach((item, i) => {
            if (i == 0) {
                params['page'] = item.substring(1);
            } else {
                let param = item.split('=');
                params[param[0]] = param[1]
            }

        });

        //loader
        $(".loader-in").fadeIn();
        $("#preloder-in").fadeIn();

        // breadcrump ?

        $.post("app/request.php", params).done(function (resp) {
            $('main').html(resp);
            //loader
            $(".loader-in").fadeOut();
            $("#preloder-in").fadeOut();

            $('.carousel').carousel();

            $("form").on("submit", function (event) {
                event.preventDefault();
                $.get("app/request.php", $(this).serialize()).done(function (resp) {
                    //console.log(resp);
                    if(resp == 1){//resp == 1
                        $('#modal-contact-success').on('hidden.bs.modal', function (e) {
                            $("form")[0].reset()
                          })
                        $('#modal-contact-success .modal-body p').append('<b>'+$('input[name="email"]').val()+'</b>');
                        $('#modal-contact-success').modal('show');

                    }
                    else{
                        $('#modal-contact-error').modal('show');
                    }

                    
                });
            });

            $('main').on('click', '.spa-link', function () {

                $('.active').each(function (i, elt) {
                    $(elt).removeClass('active');
                })
                $(this).parent().addClass('active');
                if ($(this).closest('ul').hasClass('dropdown')) {
                    $(this).closest('ul').parent().addClass('active');
                }


                //App.navigate($(this).attr("href").substr(1));
                window.location.hash != $(this).attr("href") ? window.location.hash = $(this).attr("href") : null;
            });
        });

        // console.log($('#test').data("test"))
    }

    static init() {

        // $(".loader-in").hide();
        // $("#preloder-in").hide();
        // $(".loader").fadeOut();
        // $("#preloder").delay(200).fadeOut("slow");

        if (window.location.hash) {
            App.navigate(window.location.hash)
        } else {
            App.navigate("#accueil")
        }
        window.onpopstate = function () {
            //console.log(window.location.hash);
            App.navigate(window.location.hash ? window.location.hash : "#accueil")
        };
        $('.spa-link').on('click', function () {

            $('.active').each(function (i, elt) {
                $(elt).removeClass('active');
            })
            $(this).parent().addClass('active');
            if ($(this).closest('ul').hasClass('dropdown')) {
                $(this).closest('ul').parent().addClass('active');
            }


            //App.navigate($(this).attr("href").substr(1));
            window.location.hash != $(this).attr("href") ? window.location.hash = $(this).attr("href") : null;
        })

        $(window).on("scroll", function () {

            var scrollTop = $(this).scrollTop(); //console.log("scroll top", scrollTop)
            let mainTop = $('main').offset().top; //console.log("main offset top", mainTop)
            if (scrollTop > mainTop && !$('header').hasClass('fixed-top')) {
                // if(scrollTop >= 0 && !$('header').hasClass('fixed-top')) {
                $('header').hide();
                $('header').addClass("fixed-top");

                $('header').slideDown(600, 'swing');
            }
            if (scrollTop <= mainTop && $('header').hasClass('fixed-top')) {
                // if(scrollTop < 0 && $('header').hasClass('fixed-top')){
                //$('header').slideUp(600, 'swing');
                $('header').removeClass("fixed-top");
                //$('header').show();

            }

        });

        // $('[data-toggle="popover"]').popover()
        // $('[data-toggle="tooltip"]').tooltip()
    }
}