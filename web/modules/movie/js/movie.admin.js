/**
 * @file
 * Defines JavaScript behaviors for the movie module.
 */

(function ($, Drupal) {
  Drupal.behaviors.movieForm = {
    attach: function attach(context, settings) {
      // Get form element.
      const $form = $(context).find('.movie-form');

      $form.on('submit', function(event) {
        // Get release date field element.
        const $releaseDateField = $(this).find('.form-item--release-date-0-value-date .form-element--type-date');

        if ($releaseDateField.length) {
          // Get movie's release date.
          const releaseDate = new Date($releaseDateField.val());
          // Get current date.
          const today = new Date();

          if (releaseDate > today) {
            // Prevent form to be submitted.
            event.preventDefault();
            // Highlight error in release date field.
            $releaseDateField.addClass('error');
            // Show error message.
            $releaseDateField.after('<div class="release-date-error-message">' + Drupal.t('Release date can not be in the future.') + '</div>');
          }
          else {
            // Remove error class and message.
            $releaseDateField.removeClass('error');
            $('.release-date-error-message').remove();
          }
        }
      });
    }
  }
})(jQuery, Drupal);
