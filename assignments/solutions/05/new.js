$(document).ready(function () {
    const searchURL = 'https://newsapi.org/v2/';
    const apiKey = 'c14a1b7c3a48476c91ed11170d0aa481';


    // source request
    $.get({
        type: 'get',
        url: searchURL + 'sources',
        data: {
            apiKey: apiKey,
            language: 'de'
        },
        dataType: 'json',
        success: function (data) {
            // do something with the data
            const news = $('#news');
            news.empty();

            data.sources.forEach(function (source) {
                var divEl = $('<div>');
                divEl.attr('class', 'source');

                var h3El = $('<h3>');
                h3El.text(source.name + ' ' + source.url);
                h3El.attr('id', source.id);

                divEl.append(h3El);
                news.append(divEl);
            });

            // Set up onclick listener
            $('.source h3').on('click', function () {
                var id = $(this).attr('id');
                displaySourceNewsOf(id);
            });
        },
        error: function (e) {
            console.error(e);
        }
    });


    // function to display news feed
    function displaySourceNewsOf(id) {
        // https://newsapi.org/v2/top-headlines?sources=bbc-news&apiKey=c14a1b7c3a48476c91ed11170d0aa481
        $.get({
            type: 'get',
            url: searchURL + 'top-headlines',
            data: {
                apiKey: apiKey,
                sources: id
            },
            dataType: 'json',
            success: function (data) {
                console.log(data);

                const feed = $('#feed');
                feed.empty();

                var h1El = $('<h1>');
                h1El.text(id);
                feed.append(h1El);

                data.articles.forEach(function (article) {
                    var divEl = $('<div>');
                    divEl.attr('class', 'article');

                    var h2El = $('<h2>');
                    h2El.text(article.title);

                    var pEl = $('<p>');
                    pEl.text(article.description);

                    var pEl2 = $('<p>');
                    pEl2.text(article.author);

                    var imgEl = $('<img>');
                    imgEl.attr('src', article.urlToImage);

                    var aEl = $('<a>');
                    aEl.attr('href', article.url);
                    aEl.attr('target', '_blank');
                    aEl.text('>>');


                    divEl.append(h2El);
                    divEl.append(imgEl);
                    divEl.append(pEl);
                    divEl.append(pEl2);
                    divEl.append(aEl);
                    feed.append(divEl);
                });


            },
            error: function (e) {
                console.error(e);
            }
        });
    }
});