// noinspection JSUnresolvedReference,JSUnusedGlobalSymbols

define(function () {
  'use strict';

  return function (UploadTypeHandler) {
    return UploadTypeHandler.extend({
      defaults: {
        isOnline: 'extension_attribute_qunity_is_online'
      },

      /**
       * Change visibility for typeUrl/typeFile based on current value
       *
       * @param {String} currentValue
       */
      changeTypeUpload: function (currentValue) {
        this._super();
        this.changeVisible('index=' + this.isOnline, currentValue === 'url');
      }
    });
  };
});
