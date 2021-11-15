// $.getScript('app/js/App.js').done(function(){
$('[nocache]').each(function (i, elt) {
    $(elt).attr('href') ? $(elt).attr('href', $(elt).attr('href') + "?v=" + Date.now()) : null;
    $(elt).attr('src') ? $(elt).attr('src', $(elt).attr('src') + "?v=" + Date.now()) : null;
})
App.init();


// })