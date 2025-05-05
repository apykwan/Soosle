let timer, grid;

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

  grid = $('.imageResults').masonry({
    itemSelector: ".gridItem",
    columnWidth: 200,
    gutter: 5,
    isInitLayout: false
  });

  grid.on('layoutComplete', function() {
    $('.gridItem img').css('visibility', 'visible');
  });

  $("[data-fancybox]").fancybox({
    caption: function(instance, item) {
      let caption = $(this).data('caption') || '';
      const siteUrl = $(this).data('siteurl') || '';

      if (item.type === 'image') {
        caption = (caption.length ? `${caption} <br />` : '') + `
          <a href='${item.src}' target='_blank'>View image</a>
          <br />
          <a href='${siteUrl}'>Visit page</a>
        `;
      }

      return caption;
    },
    afterShow: function (instance, item) {
      increaseImageClicks(item.src);
    }
  });
});

function loadImages(src, className) {
  // create img tag
  const image = $('<img>');

  image.on('load', function() {
    $(`.${className}`).append(image);

    clearTimeout(timer);

    timer = setTimeout(function() {
      grid.masonry();
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

function increaseImageClicks(imageUrl) {
  $
    .post('ajax/updateImageCount.php', { imageUrl })
    .done(function(result) {
      if (result !== '') {
        alert(result);
        return;
      }
    });
}
