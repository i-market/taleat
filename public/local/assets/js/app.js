// depends on: lodash

(function () {
  // TODO refactor component update dependencies. see `cartUpdate` usages.
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

    (function () {
      var $register = $('#register-modal');

      function update(html) {
        // TODO content selector
        var $content = $(html);//.find('.sidecenter > *:gt(1)');
        $register.find('.modal__content').html($content);
        return $content;
      }

      function init($content) {
        var $form = $content.find('form');
        $form.on('submit', function (evt) {
          evt.preventDefault();
          $.ajax({
            type: $form.attr('method') || 'POST',
            url: $form.attr('action') || '',
            data: $form.serialize(),
            success: function (html) {
              var $content = update(html);
              init($content);
            }
          });
        })
      }

      // TODO
      $.get($register.attr('data-path'), function (html) {
        var $content = update(html);
        init($content);
      });
    })();

    // catalog

    $('.buy-button').on('click', function(evt){
      evt.preventDefault();
      $.ajax({
        type: 'POST',
        url: '/ajax/ajax.php',
        // TODO product qty
        data: {
          mode: 'buy',
          id: $(this).data('id')
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
        });
      }

      $component.find('.quantity').on('change', _.debounce(updateCart, 500, true));
    }

    $('.cart-page').each(function () {
      initCartPage($(this));
    });

    $('.sort-block .per-page').on('change', function () {
      location.replace('?per_page='+this.value);
    });
    $('.sort-block .sort').on('change', function () {
      location.replace('?sort='+this.value);
    });

  });
})();