$(document).ready(function() {
    var _time = <?php echo $delay_logo_time; ?>;
    var link = "<?php echo $link; ?>"
    var page = $('.loading')
    setTimeout(function() {
      page.css({
        transition: '0.5s',
        visibility: 'hidden',
        opacity: '0'
      })
    }, _time)

    var logo = $('.header-logo.flex-jcc>a>img')
    logo.attr('src', '')
    logo.attr('src', '<?php echo $link_img; ?>')

    $("#desktop-home-top-page").hide();
    var list = $("[href]")
    var list2 = $("[src]").not("iframe")
    list.each(function() {
      var l = $(this).attr("href")
      //console.log(l.substr(0,4))
      if (l.substr(0, 4) != "http" && l.substr(0, 2) != "//") {
        if (l.substr(0, 1) == "/") {
          l = l.substr(1)
        }
        //console.log($(this).attr("href",link+l))
        $(this).attr("href", "r.php?link=" + link + l)
      }
    })

    list2.each(function() {
      var l = $(this).attr("src")
      //console.log(l.substr(0,4))
      if (l.substr(0, 4) != "http" && l.substr(0, 2) != "//") {
        //console.log($(this).attr("src",link+l))
        if (l.substr(0, 1) == "/") {
          l = l.substr(1)
        }

        $(this).attr("src", "r.php?link=" + link + l)
      }
    })

    $("img").each(function() {
      if (typeof $(this).attr('data-src') !== 'undefined' && $(this).attr('data-src') !== false) {
        var s = $(this).attr('data-src').substr(0, 4)
        if (s == "http" || s == "//") {
          //console.log($(this).attr("src", $(this).attr('data-src')))
        }
      }
    })

  })