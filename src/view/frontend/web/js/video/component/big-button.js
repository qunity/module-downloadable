define([
  'videojs',
  'mage/template',
  'text!Qunity_Downloadable/template/video/component/big-button.html'
], function (videojs, template, tplBigButton) {
  'use strict';

  /**
   * Big button component of play/pause states for VideoJs player
   */
  class BigButton extends videojs.getComponent('Component')
  {
    /**
     * Sub-buttons HTML element list
     * @type {Object}
     */
    subElements = {}

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
      this.on('click', this._onPlayPauseToggle.bind(this));
    }

    /**
     * Create HTML element for component
     * @public
     *
     * @returns {HTMLElement}
     */
    createEl()
    {
      return component.createElement(['play', 'pause']);
    }

    /**
     * Get sub-button HTML element
     * @private
     *
     * @param {String} type
     * @returns {HTMLElement}
     */
    _getSubElement(type)
    {
      if (this.subElements[type]) {
        return this.subElements[type];
      }

      this.subElements[type] = component.searchSubElement(this.el(), type);
      if (!this.subElements[type]) {
        throw new Error(`Sub-button HTML element not found: ${type}`);
      }

      return this.subElements[type];
    }

    /**
     * Process of toggle play/pause states
     * @private
     */
    _onPlayPauseToggle()
    {
      const player = this.player();
      if (!player.isReady_) {
        return;
      }

      const tech = player.tech(player.techName_);
      this._callPlayPauseToggle(tech);
    }

    /**
     * Call process of toggle play/pause states for tech instance
     * @private
     */
    _callPlayPauseToggle(tech)
    {
      const isPaused = tech.paused();
      isPaused ? tech.play() : tech.pause();

      const play = this._getSubElement('play'),
        pause = this._getSubElement('pause');

      component.updateStatusSubElement(play, !isPaused);
      component.updateStatusSubElement(pause, !!isPaused);
    }
  }

  /**
   * Component for VideoJs player
   */
  const component = {
    tplSubBtnSelector: 'button[data-action-type="{type}"]',

    /**
     * Get component class
     * @public
     *
     * @returns {BigButton}
     */
    getClass: function () {
      return BigButton;
    },

    /**
     * Create HTML element for component
     * @public
     *
     * @param {String[]} types
     * @returns {HTMLElement}
     */
    createElement: function (types) {
      const el = document.createElement('div');
      el.classList.add('vjs-big-button');

      types.forEach(type => el.appendChild(this.createSubElement(type)));

      return el;
    },

    /**
     * Create sub-button HTML element by action type
     * @public
     *
     * @param {String} type
     * @returns {HTMLElement}
     */
    createSubElement(type) {
      const tmp = document.createElement('div');
      tmp.innerHTML = template(tplBigButton, { type: type });

      return tmp.firstElementChild;
    },

    /**
     * Searching sub-button HTML element in another HTML element
     * @public
     *
     * @param {HTMLElement} element
     * @param {String} type
     *
     * @returns {HTMLElement|null}
     */
    searchSubElement(element, type) {
      const selector = this.tplSubBtnSelector.replace('{type}', type);

      return element.querySelector(selector);
    },

    /**
     * Update sub-button HTML element visibility in another HTML element
     * @public
     *
     * @param {HTMLElement} element
     * @param {Boolean} status
     */
    updateStatusSubElement(element, status) {
      element.setAttribute('aria-hidden', status.toString());
    }
  };

  return component;
});
