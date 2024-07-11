define(function () {
  'use strict';

  /**
   * Component-mixin of link type selection for downloadable product
   */
  return function (UploadTypeHandler) {

    // noinspection JSUnusedGlobalSymbols
    return UploadTypeHandler.extend({
      defaults: {
        isOnline: 'extension_attribute_qunity_is_online',
        filterComponents: 'ns = ${ $.ns }, parentName = ${ $.parentName }'
      },

      /**
       * Change visibility for typeUrl/typeFile based on current value
       * @public
       *
       * @param {String} currentValue
       */
      changeTypeUpload: function (currentValue) {
        this._super();

        /** @var {String} componentIsOnline */
        const componentIsOnline = `${this.filterComponents}, index=${this.isOnline}`;

        // noinspection JSUnresolvedReference
        this.changeVisible(componentIsOnline, currentValue === 'url');
      }
    });
  };
});
