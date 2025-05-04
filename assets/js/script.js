$(document).ready(function() {
  $(".result").on("click", function () {
    const id = $(this).attr('data-linkId');
    const url = $(this).attr('href');
    
    if (!id) alert('data-linked attribute is not found');

    increaseLinkClicks(id, url);

    return false;
  });
});

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

