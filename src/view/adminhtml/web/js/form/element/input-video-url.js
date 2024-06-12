// noinspection JSUnresolvedReference

define([
  'uiRegistry',
  'Magento_Ui/js/form/element/abstract'
], function (uiRegistry, uiAbstract) {
  'use strict';

  return uiAbstract.extend({
    defaults: {
      filterComponents: 'ns = ${ $.ns }, parentName = ${ $.parentName }'
    },

    /**
     * Component initialization
     * @public
     *
     * @return {uiComponent}
     */
    initialize: function () {
      this._super();
      this.initSubscriber();

      return this;
    },

    /**
     * Initializes subscription properties
     * @public
     *
     * @return {uiComponent}
     */
    initSubscriber: function () {
      this._processVideoValidation((uiComponent, validationName, verifyValue) =>
        uiComponent.value.subscribe(this._changeDependentValue.bind(this, validationName, verifyValue)));

      return this;
    },

    /**
     * Validates itself by it is validation rules using validator object
     *
     * @returns {Object} Validate information
     */
    validate: function () {
      this._processVideoValidation((uiComponent, validationName, verifyValue) =>
        this._changeValidation(validationName, verifyValue, uiComponent.value()));

      return this._super();
    },

    /**
     * Executing callback function for each setting video validations
     * @private
     *
     * @param {Function} callback
     */
    _processVideoValidation: function (callback) {
      /** @var {{String:Object}} videoValidation */

      Object.entries(this.videoValidation).forEach(([validationName, mapping]) =>
        Object.entries(mapping).forEach(([componentIndex, verifyValue]) =>

          uiRegistry.get(`${this.filterComponents}, index=${componentIndex}`, uiComponent =>
            callback(uiComponent, validationName, verifyValue))));
    },

    /**
     * Change dependent value for video URL validation
     * @private
     *
     * @param {String} validationName
     * @param {*} verifyValue
     * @param {*} checkValue
     */
    _changeDependentValue: function (validationName, verifyValue, checkValue) {
      this._changeValidation(validationName, verifyValue, checkValue);
      this.validate();
    },

    /**
     * Change validation status for video URL
     * @private
     *
     * @param {String} validationName
     * @param {*} verifyValue
     * @param {*} checkValue
     */
    _changeValidation: function (validationName, verifyValue, checkValue) {
      this.validation[validationName] = (verifyValue === checkValue);
    }
  });
});
