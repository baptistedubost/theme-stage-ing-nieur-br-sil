jQuery('document').ready(function ($) {
    console.log('Hello World !');
    var elem = document.querySelector('.grid');
    var msnry = new Masonry(elem, {
        // options
        itemSelector: '.grid-item',
        columnWidth: 200
    });

    // element argument can be a selector string
    //   for an individual element
    var msnry = new Masonry('.grid', {
        // options
    });
});
