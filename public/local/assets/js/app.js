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

  });
})();