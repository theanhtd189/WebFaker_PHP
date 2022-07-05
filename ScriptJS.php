<script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>
<script>
  $(document).ready(function() {
    var page = $('.loading');
    var _time = <?php echo $App->logo_time; ?>;
    setTimeout(function() {
      page.css({
        transition: '0.5s',
        visibility: 'hidden',
        opacity: '0'
      })
    }, _time);
  })
</script>
<script>
  $(document).ready(function() {
    var link = "<?php echo $App->destination; ?>";
    var redirect_page = "<?php echo $App->base_url; ?>";
    var times = 0;
    var main_domain = "<?php echo $App->GetDomain(); ?>";

    var Change_Logo = function(selector,img_url) {
      var obj_list = $(selector);
      if(obj_list!=null && obj_list.length>0) {
        obj_list.each(function(){
          var _type = $(this).prop('tagName');
          if(_type=="IMG"){
            $(this).attr('src', img_url);
          }
          else
          {
            var _url = "url("+img_url+")";
            $(this).attr('style','background-image: url("'+img_url+'") !important');         
          }
        })
      }
    }


    var Change_Text = function(elm = $("*"), sample_text, new_text) {
      sample_text.replace(/\s{2,}/g, ' ').trim();
      new_text.replace(/\s{2,}/g, ' ').trim();
      if (elm != null) {
        if (elm.children().length > 0) {
          var e = elm.children();
          e.each(function() {
            var _type = $(this).prop('tagName');
            var _text = $(this).text() || $(this).html();
            _text.replace(/\s{2,}/g, ' ').trim();

            if (
              _text != null &&
              _text != "" &&
              _type != "SCRIPT" && 
              _type != "STYLE" && 
              _type != "NOSCRIPT"
            ) {
              //console.log('find in --> ',_text);
              //console.log(elm)

              Change_Text($(this), sample_text, new_text);

              
                var t = $(this).text();
                if (t != "" && t.includes(sample_text)) {
                  //console.log('FOUND ---> ');                
                  //console.log($(this))
                  var newText = t.replace(sample_text, new_text)
                  //console.log('REPLACE ',sample_text,' ----> ',newText);
                  $(this).text(newText)
                }
              
            }
          })
        }
      }
    }

    var Rewrite_Link = function(_hard_link, _redirect) {
      $("#ta1o9er_contents *[href]").each(function() {
        var l = $(this).attr("href").toLowerCase();
        var _type = $(this).prop('tagName');

      //  //console.log('scan ' + _type + ' ', l);

        if (_type == 'A') {
        //  //console.log('so sanh', l.includes(main_domain))

          if (l == "" || l == "/" || l.substr(0, 5).includes("index")) {
            // //console.log(l)
           // //console.log('Changed link "' + l + '" to "' + _redirect + '"');
            $(this).attr("href", _redirect);
          } else
          if (l.substr(0, 1) == "/" && l.substr(0, 2) != "//") {
            // //console.log(l)
           // //console.log('Changed link "' + l + '" to "' + _redirect + l.substring(1) + '"');
            $(this).attr("href", _redirect + l.substring(1));
          } else
          if (l.substring(0, 2) == "./") {
            $(this).attr("href", _redirect + l.substring(2));
          } else
          if (l.includes(main_domain)) {
           // //console.log(l.split(main_domain));
            arr = l.split(main_domain);
            if (arr[0].includes('http')) {
              if (arr[1] != "") {
                if (arr[1].substring(0, 1) == "/") {
                  $(this).attr("href", redirect_page + arr[1].substring(1));
                } else {
                  $(this).attr("href", redirect_page + arr[1]);
                }
              }
            } else {
              if (arr[0].substring(0, 1) == "/") {
                $(this).attr("href", redirect_page + arr[0].substring(1));
              } else {
                $(this).attr("href", redirect_page + arr[0]);
              }
            }
          }

        } else {
          if (l == "" || l == "/" || l.substr(0, 5).includes("index")) {
            // //console.log(l)
            //console.log('Changed link "' + l + '" to "' + _hard_link + '"');
            $(this).attr("href", _hard_link);
          } else
          if (l.substr(0, 1) == "/" && l.substr(0, 2) != "//") {
            // //console.log(l)
           // //console.log('Changed link "' + l + '" to "' + _hard_link + l.substring(1) + '"');
            $(this).attr("href", _hard_link + l.substring(1));
          } else
          if (l.substring(0, 2) == "./") {
            $(this).attr("href", _hard_link + l.substring(2));
          }
        }
      });

    }

    var Rewrite_Source = function(_link) {
      $("[src]").not("iframe").each(function() {
        var l = $(this).attr("src");
        ////console.log('scan src ',l);
        if (l.substr(0, 4) != "http" && l.substr(0, 2) != "//") {
          if (l.substr(0, 1) == "/") {
            // //console.log('Changed src "'+l+'" to "'+_link + l.substr(1)+'"');
            $(this).attr("src", (_link + l.substr(1)));
          } else
          if (l.substr(0, 2) == "./") {
            // //console.log('Changed src "'+l+'" to "'+_link + l.substr(2)+'"');
            $(this).attr("src", (_link + l.substr(2)));
          }
        }
      });
      times++;
    }

    var Add_Css = function(id,_css) {
      var _style = $('<style id="ta1o9er_style_id'+id+'"></style>');
      _style.text(_css);
      _style.insertAfter($("#ta1o9er_contents style").last())
      
    }

    var Add_Js = function(id,_js) {
      _js = atob(_js);
      var _script = $('<script/>');
      _script.attr('id','ta1o9er_script_id'+id);
      _script.text(_js);
      _script.insertAfter($("#ta1o9er_contents script").last())
      
    }

    Add_Css(234);
    fetch("api")
      .then(response => response.json())
      .then(function(data) {
        var list = data;
        console.log('Fetch API ta1o9er')
        console.log(list);
        if (list.length > 0) {
          list.forEach(function(e) {
            var _type = e.Type.toLowerCase();
            var _value = e.Value;
            var _name = e.Name;
            var _selector = e.Selector;
            var _id = e.ID;
            if (_type == "hide") {            
              $(_selector).hide(400);
              //console.log("cas1")
            } 
            else
            if (_type == "change_text") {
              //console.log(_name,_value);
              Change_Text($("#ta1o9er_contents"), _name, _value);
            }
            else
            if(_type == "change_logo"){
              Change_Logo(_selector,_value);
            }
            else
            if(_type == "css"){
              Add_Css(_id,_value);
            }
            else
            if(_type == "js"){
              Add_Js(_id,_value);
            }
          })
        }
      })
      .catch(error => {
        //console.error('Lỗi fetch API: ' + error);
      });


    Rewrite_Link(link, redirect_page);

    Rewrite_Source(link);


    // $("img").each(function() {
    //   if (typeof $(this).attr('data-src') !== 'undefined' && $(this).attr('data-src') !== false) {
    //     var s = $(this).attr('data-src').substr(0, 4)
    //     if (s == "http" || s == "//") {
    //       ////console.log($(this).attr("src", $(this).attr('data-src')))
    //     }
    //   }
    // })


  })

  
</script>