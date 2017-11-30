(function () {
  // see npm `build:modules` script
  var queryString = modules['querystring'];

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
    maxlength: $.validator.format("Пожалуйста, введите не больше {0} символов."),
    minlength: $.validator.format("Пожалуйста, введите не меньше {0} символов."),
    rangelength: $.validator.format("Пожалуйста, введите значение длиной от {0} до {1} символов."),
    range: $.validator.format("Пожалуйста, введите число от {0} до {1}."),
    max: $.validator.format("Пожалуйста, введите число, меньшее или равное {0}."),
    min: $.validator.format("Пожалуйста, введите число, большее или равное {0}."),
    // additional-methods.js rules
    require_from_group: $.validator.format("Пожалуйста, заполните как минимум {0} из этих полей.")
  });

  $.validator.setDefaults({
    ignore: ':hidden:parent:not(.wrap-radio)',
    errorPlacement: function($error, $el) {
      if ($el.attr('data-error-container')) {
        var sel = $el.attr('data-error-container');
        return $(sel).html($error);
      }
      if ($el.closest('.right .grid').length) {
        return $error.insertAfter($el.closest('.right .grid'));
      }

      if (_.includes(['radio', 'checkbox'], $el.attr('type'))) {
        var name = $el.attr('name');
        if (name) {
          // mutate. for a group of radios/checkboxes target the last one.
          $el = $('[name="'+name+'"]:last');
        }
      }

      if ($el.hasClass('fs-dropdown-element')) {
        $error.insertAfter($el.closest('.fs-dropdown'));
      } else if ($el.parent().is('.wrap-radio, .wrap-checkbox, .wrap-file, .label_textarea')) {
        $error.insertAfter($el.parent());
      } else {
        $error.insertAfter($el);
      }
    }
  });

  function initComponents($scope) {
    Mockup.initComponents($scope);
    cleanUpEditable($('.editable-area', $scope));
    $('form.validate', $scope).validate();

    $('[data-fancybox-items]', $scope).on('click', function (evt) {
      evt.preventDefault();
      var items = JSON.parse($(this).attr('data-fancybox-items'));
      $.fancybox.open(items);
    });

    $('[data-tab]', $scope).on('click', function () {
      var $trigger = $(this);
      var $tabs = $(this).siblings('[data-tab]').addBack();
      var $target = $('#' + $(this).attr('data-tab'));
      var $panes = $target.siblings('.tab-pane').addBack();
      $tabs.each(function () {
        $(this).toggleClass('active', $(this).is($trigger));
      });
      $panes.each(function () {
        $(this).toggleClass('active', $(this).is($target));
      });
    });

    $('.image-uploads .image-uploads__item', $scope).each(function () {
      var $item = $(this);
      $item.find('input[type=file]').on('change', function () {
        if (this.files) {
          var mb = 1000 * 1000;
          if (window.FileReader && this.files[0].size < 5 * mb) {
            // thumbnail
            var reader = new FileReader();
            reader.readAsDataURL(this.files[0]);
            reader.onload = function (evt) {
              $item.find('.image-uploads__item-img').css('background-image', 'url('+evt.target.result+')');
            };
          } else {
            $item.find('.image-uploads__item-img')
              .text(this.files[0].name)
              .addClass('has-text');
          }
        }
      });
    });
  }

  function replaceElement($elem, url, init) {
    init = init || _.noop;
    $.get(url, function(html) {
      var $new = $(html);
      $elem.replaceWith($new);
      // order matters: init jquery-validate first
      initComponents($new);
      init($new);
      history.replaceState({}, '', url);
    });
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
        mode: 'header_cart'
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

    initComponents($(document));

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

    $('.signup-form').each(function () {
      function init($component) {
        $('.tab-link', $component).on('click', function (evt) {
          evt.preventDefault();
          replaceElement($component, $(this).attr('href'), init);
        });
      }

      init($(this));
    });

    // region

    $('.regional-service-centers').each(function () {
      var $section = $(this);
      function selectedValue($selects) {
        return $selects
          .filter(function () { return $(this).val() !== ''; })
          .map(function () {
            return $(this).attr('data-brand')+':'+this.value;
          }).get().join(',');
      }
      function init($section) {
        $section.find('.rsc-item').matchHeight();
        $section.find('.city').on('change', function () {
          var selected = selectedValue($section.find('.city'));
          var query = '?'+updateQuery(_.partialRight(_.set, 'selected', selected));
          replaceElement($section, query, init)
        });
      }
      init($section);
    });

    // partner

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

        function replaceSection(url, cb) {
          cb = cb || _.noop;
          $.get(url, function(html) {
            var $new = $(html);

            var x = $('.nav', $section).scrollLeft();
            $section.replaceWith($new);
            $('.nav', $new).scrollLeft(x);

            // order matters: init jquery-validate first
            initComponents($new);
            init($new);
            cb();
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
        $('.nav .tab-link', $section).on('click', function (evt) {
          evt.preventDefault();
          replaceSection($(this).attr('href'));
        });
        $('.paginator a', $section).on('click', function (evt) {
          evt.preventDefault();
          replaceSection($(this).attr('href'), function () {
            window.scrollTo(0, 0);
          });
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

    // partner report

    $('.technical-conclusion-form').each(function () {
      var $form = $(this);

      (function () {
        function init($deps) {
          $('.product-name', $deps).on('change', function () {
            $.get('', $form.serializeArray(), function (html) {
              var $new = $(html).find('.model-dependencies');
              $deps.replaceWith($new);
              initComponents($new);
              init($new);
            });
          });
        }
        $('.model-dependencies', $form).each(function () {
          init($(this));
        });
      })();

      $('.defect', $form).on('change', function () {
        $.get('', $form.serializeArray(), function (html) {
          var $new = $(html).find('.defect-description');
          $('.defect-description', $form).replaceWith($new);
        });
      });

      $('input[name="SC[PHONE]"], input[name="VLADELEC[PHONE]"]', $form)
        .mask('+7 (999) 999-99-99', {autoclear: false});
      $('input[name="SC[DATA_ZAKL]"], input[name="IZDEL[DATA_PRODAJI]"], input[name="IZDEL[DATA_POSTUP]"]', $form)
        .mask('99.99.9999', {autoclear: false});
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

    var $headings = $('.catalog-about .editable-area').children('h1, h2, .h1, .h2');
    var $parent = $('.catalog-about').parent();
    function align() {
      // align with .section-title headings
      $headings.each(function () {
        var pageWidth = $parent.width();
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