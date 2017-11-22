(function () {
  // see npm `build:modules` script
  var queryString = modules['querystring'];

  // TODO better browser support
  if (!window.history) {
    // noinspection JSValidateTypes
    window.history = {
      pushState: _.noop,
      replaceState: _.noop
    };
  }

  $.extend($.validator.messages, {
    required: "Это поле необходимо заполнить.",
    remote: "Пожалуйста, введите правильное значение.",
    email: "Пожалуйста, введите корректный адрес электронной почты.",
    url: "Пожалуйста, введите корректный URL.",
    date: "Пожалуйста, введите корректную дату.",
    dateISO: "Пожалуйста, введите корректную дату в формате ISO.",
    number: "Пожалуйста, введите число.",
    digits: "Пожалуйста, вводите только цифры.",
    creditcard: "Пожалуйста, введите правильный номер кредитной карты.",
    equalTo: "Пожалуйста, введите такое же значение ещё раз.",
    extension: "Пожалуйста, выберите файл с правильным расширением.",
    maxlength: $.validator.format( "Пожалуйста, введите не больше {0} символов." ),
    minlength: $.validator.format( "Пожалуйста, введите не меньше {0} символов." ),
    rangelength: $.validator.format( "Пожалуйста, введите значение длиной от {0} до {1} символов." ),
    range: $.validator.format( "Пожалуйста, введите число от {0} до {1}." ),
    max: $.validator.format( "Пожалуйста, введите число, меньшее или равное {0}." ),
    min: $.validator.format( "Пожалуйста, введите число, большее или равное {0}." )
  });

  $.validator.setDefaults({
    // TODO don't ignore our fancy hidden checkboxes
    ignore: ':hidden'
  });

  function initComponents($scope) {
    Mockup.initComponents($scope);
    $('form.validate', $scope).validate();
  }

  function updateQuery(f) {
    // drop leading "?"
    return queryString.stringify(f(queryString.parse(location.search.substr(1))));
  }

  // TODO refactor component dependencies. see `cartUpdate` usages.
  function cartUpdate(){
    $.ajax({
      type: 'POST',
      url: '/ajax/ajax.php',
      data: {
        mode: 'cartUpdate'
      },
      dataType: 'html',
      success: function (result) {
        $('#mini-cart').html(result)
      }
    });
  }

  function cleanUpEditable($scope) {
    function isEmpty(str) {
      return str.replace(/\s|&nbsp;/g, '').length === 0;
    }
    // clean up after the bitrix editor
    $scope.find('[style]').each(function () {
      $(this)
        .css('font-family', '')
        .css('font-size', '');
    });
    $scope.find('p').each(function () {
      if (isEmpty($(this).html())) {
        $(this).remove();
      }
    });
  }

  $(document).ready(function () {

    initComponents($('body'));

    // autofocus the first input field
    $('.default-page, .modal').find('input:text:not([placeholder]):visible:first').focus();

    var $globalLoader = $('#global-loader');
    var delay = 200; // ms
    var timer = null;
    $(document)
      .ajaxStart(function () {
        timer = setTimeout(function () {
          $globalLoader.show();
        }, delay);
      })
      .ajaxStop(function () {
        if (timer !== null) {
          clearTimeout(timer);
        }
        $globalLoader.hide();
      });

    cleanUpEditable($('.editable-area'));

    $('section.lk').each(function () {
      var $section = $(this);
      init($section);

      function init($section) {
        function initProfile($profile) {
          $profile.find('.change-password-shortcut').on('click', function () {
            // hack
            $profile.find('.edit-btn').click();
            $profile.find('.change-password').click();
          });
          var $form = $('form', $profile);
          $form.data('validator').settings.submitHandler = function () {
            var formData = $form.serializeArray();
            formData.push({name: 'mode', value: 'partner/profile'});
            $.post('/ajax/ajax.php', formData, function (html) {
              var $new = $(html);
              $profile.replaceWith($new);
              // order matters: init jquery-validate first
              initComponents($new);
              initProfile($new);
            });
          };
        }
        function initNewsletter($component) {
          var $form = $('form', $component);
          $('.toggle', $component).on('change', function () {
            $(this).closest('form').submit();
          });
          function onSubmit() {
            var formData = $form.serializeArray();
            formData.push({name: 'mode', value: 'partner/newsletter_sub'});
            var attempt = 0;
            $.post('/ajax/ajax.php', formData, function cb(html) {
              attempt += 1;
              if (attempt > 2) return;
              if (html === '') {
                // bitrix:subscribe.edit component will do a (malformed) redirect
                // and there is nothing you can do about it ¯\_(ツ)_/¯
                return $.get('/ajax/ajax.php', {mode: 'partner/newsletter_sub'}, cb);
              }
              var $new = $(html);
              $component.replaceWith($new);
              // order matters: init jquery-validate first
              initComponents($new);
              initNewsletter($new);
            });
          }
          if (!_.isUndefined($form.data('validator'))) {
            $form.data('validator').settings.submitHandler = onSubmit;
          } else {
            $form.on('submit', function (evt) {
              evt.preventDefault();
              onSubmit();
            })
          }
        }

        function replaceSection(url) {
          $.get(url, function(html) {
            var $new = $(html);
            $section.replaceWith($new);
            // order matters: init jquery-validate first
            initComponents($new);
            init($new);
            history.replaceState({}, '', url);
          });
        }

        $('.profile', $section).each(function () {
          initProfile($(this));
        });
        $('.newsletter-sub', $section).each(function () {
          initNewsletter($(this));
        });
        // ajaxify links
        $('.nav .tab-link, .paginator a', $section).on('click', function (evt) {
          evt.preventDefault();
          replaceSection($(this).attr('href'));
        });
        $('.brand-filter', $section).on('change', function () {
          var query = '?'+updateQuery(_.partialRight(_.set, 'SECTION_ID', this.value));
          replaceSection(query);
        });
        $('.wrap-documents .brand, .helpful-information .brand', $section).on('click', function (evt) {
          evt.preventDefault();
          var query = '?'+updateQuery(_.partialRight(_.set, 'SECTION_ID', $(this).attr('data-id')));
          replaceSection(query);
        });
      }
    });

    // catalog

    $('.buy-button').on('click', function(evt){
      evt.preventDefault();
      $.ajax({
        type: 'POST',
        url: '/ajax/ajax.php',
        data: {
          mode: 'buy',
          id: $(this).data('id'),
          quantity: $(this).closest('form').find('.quantity').val()
        },
        dataType: 'html',
        success: function (result) {
          cartUpdate();
          Mockup.openModal($('#product-added-to-cart'))
        }
      });
    });

    function initCartPage($component) {
      function updateCart() {
        var formData = $component.serializeArray();
        formData.push({name: 'mode', value: 'cart/index'});
        $.post('/ajax/ajax.php', formData, function (html) {
          var $new = $(html);
          $component.replaceWith($new);
          initCartPage($new);
          cartUpdate();
          initComponents($new);
        });
      }

      $component.find('.quantity').on('change', _.debounce(updateCart, 500, true));
    }

    $('.cart-page').each(function () {
      initCartPage($(this));
    });
    $('.sort-block .per-page').on('change', function () {
      location.search = updateQuery(_.partialRight(_.set, 'per_page', this.value));
    });
    $('.sort-block .sort').on('change', function () {
      location.search = updateQuery(_.partialRight(_.set, 'sort', this.value));
    });

    function align() {
      // align with .section-title headings
      $('.catalog-about .editable-area').children('h1, h2, .h1, .h2').each(function () {
        var pageWidth = $('.catalog-about').parent().width();
        if (pageWidth < 1024) { // see media query
          return $(this).css('padding-left', 0);
        }
        var maxWidth = 1200;
        var padding = 220;
        var margin = (pageWidth - maxWidth) / 2;
        var m = (pageWidth - $(this).parent().width()) / 2;
        var left = margin + padding - m;
        $(this).css('padding-left', Math.max(left, padding - m));
      });
    }

    align();
    $(window).on('resize', _.throttle(align, 1000 / 30));
  });
})();