define([
  'videojs',
  'json!videojs/lang/ru',
  'video/component/title-bar',
  'video/component/big-button'
], function (videojs, ru, titleBar, bigButton) {
  'use strict';

  /**
   * Component for general configuration of VideoJs player
   * @see https://videojs.com
   */
  return {
    config: {
      languages: {
        'ru': ru
      },
      components: {
        'titleBar': titleBar,
        'bigButton': bigButton
      }
    },
    initialized: false,

    /**
     * Component initialization
     * @public
     */
    initialize: function () {
      if (this.initialized) {
        return this;
      }

      this._configure();
      this.initialized = true;

      return this;
    },

    /**
     * Create new video object by VideoJs player
     * @public
     *
     * @param {String} id
     * @param {Object} options
     *
     * @returns {Player}
     */
    create: function (id, options) {
      if (!this.initialized) {
        throw new Error('VideoJs library is not initialized');
      }

      return videojs(id, options);
    },

    /**
     * Get language dictionary
     * @public
     *
     * @param {String} name
     * @returns {String[]}
     */
    getLanguage: function (name) {
      if (!this.config.languages.hasOwnProperty(name)) {
        throw new Error(`VideoJs language dictionary not found: ${name}`);
      }

      return this.config.languages[name];
    },

    /**
     * Get player component
     * @public
     *
     * @param {String} name
     * @returns {Object}
     */
    getComponent: function (name) {
      if (!this.config.components.hasOwnProperty(name)) {
        throw new Error(`VideoJs component not found: ${name}`);
      }

      return this.config.components[name];
    },

    /**
     * VideoJs player configuration process
     * @private
     */
    _configure: function () {
      this._addLanguages();
      this._registerComponents();
    },

    /**
     * Add languages to VideoJs player
     * @private
     */
    _addLanguages: function () {
      Object.entries(this.config.languages).forEach(([name, language]) =>
        videojs.addLanguage(name, language));
    },

    /**
     * Register components into VideoJs player
     * @private
     */
    _registerComponents: function () {
      Object.entries(this.config.components).forEach(([name, component]) =>
        videojs.registerComponent(name, component.getClass()));
    }
  }.initialize();
});
