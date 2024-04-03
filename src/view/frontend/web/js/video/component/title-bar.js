define([
  'videojs',
  'mage/template',
  'text!Qunity_Downloadable/template/video/component/title-bar.html',
  'ko'
], function (videojs, template, tplTitleBar, ko) {
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
      this.value.subscribe(this._updateTitle.bind(this));
    }

    /**
     * Create HTML element for component
     * @public
     *
     * @returns {HTMLElement}
     */
    createEl()
    {
      return component.createElement('');
    }

    /**
     * Update title value in component
     * @private
     *
     * @param {String} text
     */
    _updateTitle(text)
    {
      component.updateTitleElement(this.el(), text);
    }
  }

  /**
   * Component for VideoJs player
   */
  const component = {
    contentSelector: 'h2',

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
    createElement: function (title) {
      const tmp = document.createElement('div');
      tmp.innerHTML = template(tplTitleBar, { title: title });

      return tmp.firstElementChild;
    },

    /**
     * Update title value in another HTML element
     * @public
     *
     * @param {HTMLElement} element
     * @param {String} text
     */
    updateTitleElement: function (element, text) {
      element.querySelector(this.contentSelector).textContent = text;
      element.setAttribute('aria-hidden', (!text).toString());
    }
  };

  return component;
});
