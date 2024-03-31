define([
  'videojs',
  'ko'
], function (videojs, ko) {
  'use strict';

  /**
   * Title bar component for VideoJs player
   */
  return class extends videojs.getComponent('Component') {
    value = ko.observable('');

    constructor(player, options = {})
    {
      super(player, options);
      this.value.subscribe(this.setValue.bind(this));
    }

    createEl()
    {
      const el = document.createElement('div');
      el.className = 'vjs-title-bar';
      el.appendChild(document.createElement('h2'));

      return el;
    }

    setValue(text)
    {
      this.el().firstChild.textContent = text;
    }
  }
});
