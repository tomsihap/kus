   var $winner = jQuery('#contest_player');
   // When winner gets selected ...
   $winner.change(function () {
       console.log
       var $form = jQuery(this).closest('form');
       // Simulate form data, but only include the selected winner value.
       var data = {};
       data[$winner.attr('name')] = $winner.val();
       // Submit data via AJAX to the form's action path.
       jQuery.ajax({
           url: $form.attr('action'),
           type: $form.attr('method'),
           data: data,
           success: function (html) {
               console.log(html)
               jQuery('#contest_loser').replaceWith(
                   // ... with the returned one from the AJAX response.
                   jQuery(html).find('#contest_loser')
               );
               // Position field now displays the appropriate loser(s).
           }
       });
   });


      var $tournament = jQuery('#player_tournament');
      // When winner gets selected ...
      $tournament.change(function () {
          console.log
          var $form = jQuery(this).closest('form');
          // Simulate form data, but only include the selected winner value.
          var data = {};
          data[$tournament.attr('name')] = $tournament.val();
          // Submit data via AJAX to the form's action path.
          jQuery.ajax({
              url: $form.attr('action'),
              type: $form.attr('method'),
              data: data,
              success: function (html) {
                  console.log(html)
                  jQuery('#player_team').replaceWith(
                      // ... with the returned one from the AJAX response.
                      jQuery(html).find('#player_team')
                  );
                  // Position field now displays the appropriate loser(s).
              }
          });
      });