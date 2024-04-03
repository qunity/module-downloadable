define([
  'videojs',
  'ko',
  'mage/template',
  'text!Qunity_Downloadable/template/video/component/title-bar.html'
], function (videojs, ko, template, tplTitleBar) {
  'use strict';

  /**
   * Title bar component for VideoJs player
   */
  class TitleBar extends videojs.getComponent('Component')
  {
    /**
     * Observable title text value
     * @type {Object}
     */
    value = ko.observable('');

    /**
     * Component constructor
     * @public
     *
     * @param {Player} player
     * @param {Object} options
     */
    constructor(player, options = {})
    {
      super(player, options);
      this.value.subscribe(this.setValue.bind(this));
    }

    /**
     * Create HTML element for component
     * @public
     *
     * @returns {HTMLElement}
     */
    createEl()
    {
      return component.createElement();
    }

    /**
     * Set new title into HTML content of component
     * @public
     *
     * @param text
     * @returns {TitleBar}
     */
    setValue(text)
    {
      this.el().firstChild.textContent = text;

      return this;
    }
  }

  /**
   * Component for VideoJs player
   */
  const component = {

    /**
     * Get component class
     * @public
     *
     * @returns {TitleBar}
     */
    getClass: function () {
      return TitleBar;
    },

    /**
     * Create HTML element for component
     * @public
     *
     * @param {String} title
     * @returns {HTMLElement}
     */
    createElement: function (title = '') {
      const tmp = document.createElement('div');
      tmp.innerHTML = template(tplTitleBar, { title: title });

      return tmp.firstElementChild;
    }
  };

  return component
});
