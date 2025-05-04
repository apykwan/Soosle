let timer;

$(document).ready(function() {
  $(".result").on("click", function () {
    const id = $(this).attr('data-linkId');
    const url = $(this).attr('href');
    
    if (!id) alert('data-linked attribute is not found');

    increaseLinkClicks(id, url);

    return false;
  });

  $('.imageLink').each(function() {
    const className = $(this).attr('class').split(' ')[1];
    loadImages($(this).data('img'), className);
  });

  const grid = $('.imageResults').masonry();

  grid.on('layoutComplete', function() {
    $('.gridItem img').css('visibility', 'visible');
  });

  grid.masonry({
    itemSelector: ".gridItem",
    columnWidth: 200,
    gutter: 5,
    isInitLayout: false
  });
});


function loadImages(src, className) {
  // create img tag
  const image = $('<img>');

  image.on('load', function() {
    $(`.${className}`).append(image);

    clearTimeout(timer);

    timer = setTimeout(function() {
      $('.imageResults').masonry();
    }, 500);
  });

  image.on('error', function() {
    $(`.${className}`).remove();

    $.post('ajax/setBroken.php', { src });
  });

  image.attr('src', src);
}

function increaseLinkClicks(linkId, url) {
  $
    .post('ajax/updateLinkCount.php', { linkId })
    .done(function(result) {
      if (result !== '') {
        alert(result);
        return;
      }

      window.location.href = url;
    });
}

