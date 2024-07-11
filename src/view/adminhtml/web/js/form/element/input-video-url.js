define([
  'Magento_Ui/js/form/element/abstract',
  'uiRegistry'
], function (Abstract, registry) {
  'use strict';

  /**
   * Input element for links with additional video functionality
   */
  // noinspection JSUnresolvedReference
  return Abstract.extend({
    defaults: {
      filterComponents: 'ns = ${ $.ns }, parentName = ${ $.parentName }'
    },

    /**
     * Component initialization
     * @public
     *
     * @return {uiElement}
     */
    initialize: function () {
      this._super()
        .initSubscriber();

      return this;
    },

    /**
     * Initializes subscription properties
     * @public
     *
     * @return {uiElement}
     */
    initSubscriber: function () {
      this._processVideoValidation(component =>
        component.value.subscribe(this.validate.bind(this)));

      return this;
    },

    /**
     * Validates itself by its validation rules using validator object
     * @public
     *
     * @return {Object} Validate information
     */
    validate: function () {
      this._processVideoValidation((component, validationName, verifyValue) =>
        this._changeValidation(validationName, verifyValue, component.value()));

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

          registry.get(`${this.filterComponents}, index=${componentIndex}`, component =>
            callback(component, validationName, verifyValue))));
    },

    /**
     * Change validation status for video URL
     * @private
     *
     * @param {String} validationName
     * @param {String|Number} verifyValue
     * @param {String|Number} checkValue
     */
    _changeValidation: function (validationName, verifyValue, checkValue) {
      this.validation[validationName] = (verifyValue === checkValue);
    }
  });
});
