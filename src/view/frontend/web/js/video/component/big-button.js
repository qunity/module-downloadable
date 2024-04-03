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
     * Selector string template for sub-button HTML element
     * @type {string}
     */
    tplSubBtnSelector = 'button[data-action-type="{type}"]'

    /**
     * Sub-buttons HTML element list
     * @type {{ play: HTMLElement, pause: HTMLElement }}
     */
    subBtnElements = {
      play: undefined,
      pause: undefined
    }

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
    _getSubBtnElement(type)
    {
      if (this.subBtnElements[type]) {
        return this.subBtnElements[type];
      }

      const selector = this.tplSubBtnSelector.replace('{type}', type);
      this.subBtnElements[type] = this.el().querySelector(selector);

      if (!this.subBtnElements[type]) {
        throw new Error(`Sub-button HTML element not found: ${type}`);
      }

      return this.subBtnElements[type];
    }

    /**
     * Process of toggle play/pause states
     * @private
     */
    _onPlayPauseToggle()
    {
      const tech = this.player().tech(), isPaused = tech.paused();
      isPaused ? tech.play() : tech.pause();

      const play = this._getSubBtnElement('play'),
        pause = this._getSubBtnElement('pause');

      play.setAttribute('aria-hidden', isPaused.toString());
      pause.setAttribute('aria-hidden', (!isPaused).toString());
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

      types.forEach(type => el.appendChild(this._createSubBtnElement(type)));

      return el;
    },

    /**
     * Create sub-button HTML element by action type
     * @private
     *
     * @param {String} type
     * @returns {HTMLElement}
     */
    _createSubBtnElement(type) {
      const tmp = document.createElement('div');
      tmp.innerHTML = template(tplBigButton, { type: type });

      return tmp.firstElementChild;
    }
  };

  return component;
});
