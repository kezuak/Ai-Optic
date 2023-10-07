(function () {
  'use strict';

  /**
   * @copyright  (C) 2018 Open Source Matters, Inc. <https://www.joomla.org>
   * @license    GNU General Public License version 2 or later; see LICENSE.txt
   */
  var formElements;
  var activated = false;

  // Update image
  var resize = function resize(width, height, image) {
    // The canvas where we will resize the image
    var canvas = document.createElement('canvas');
    canvas.width = width;
    canvas.height = height;
    canvas.getContext('2d').drawImage(image, 0, 0, width, height);

    // The format
    var format = Joomla.MediaManager.Edit.original.extension === 'jpg' ? 'jpeg' : Joomla.MediaManager.Edit.original.extension;

    // The quality
    var quality = formElements.resizeQuality.value;

    // Creating the data from the canvas
    Joomla.MediaManager.Edit.current.contents = canvas.toDataURL("image/" + format, quality);

    // Updating the preview element
    image.width = width;
    image.height = height;
    image.src = Joomla.MediaManager.Edit.current.contents;

    // Update the width input box
    formElements.resizeWidth.value = parseInt(width, 10);

    // Update the height input box
    formElements.resizeHeight.value = parseInt(height, 10);

    // Notify the app that a change has been made
    window.dispatchEvent(new Event('mediaManager.history.point'));
    canvas = null;
  };
  var addListeners = function addListeners(image) {
    // The listeners
    formElements.resizeWidth.addEventListener('change', function (_ref) {
      var target = _ref.target;
      resize(parseInt(target.value, 10), parseInt(target.value, 10) / (image.width / image.height), image);
    });
    formElements.resizeHeight.addEventListener('change', function (_ref2) {
      var target = _ref2.target;
      resize(parseInt(target.value, 10) * (image.width / image.height), parseInt(target.value, 10), image);
    });
  };
  var initResize = function initResize(image) {
    // Update the input boxes
    formElements.resizeWidth.value = image.naturalWidth;
    formElements.resizeHeight.value = image.naturalHeight;
    if (!activated) {
      activated = true;
      addListeners(image);
    }
  };
  window.addEventListener('media-manager-edit-init', function () {
    // Get the form elements
    formElements = {
      resizeWidth: document.getElementById('jform_resize_width'),
      resizeHeight: document.getElementById('jform_resize_height'),
      resizeQuality: document.getElementById('jform_resize_quality')
    };

    // Register the Events
    Joomla.MediaManager.Edit.plugins.resize = {
      Activate: function Activate(image) {
        return new Promise(function (resolve /* , reject */) {
          // Initialize
          initResize(image);
          resolve();
        });
      },
      Deactivate: function Deactivate() {
        return new Promise(function (resolve /* , reject */) {
          resolve();
        });
      } /* image */
    };
  }, {
    once: true
  });

})();
