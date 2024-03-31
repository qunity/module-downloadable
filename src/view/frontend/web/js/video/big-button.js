define([
  'videojs'
], function (videojs) {
  'use strict';

  /**
   * Big play/pause button component for VideoJs player
   */
  return class extends videojs.getComponent('Component') {
    constructor(player, options = {})
    {
      super(player, options);
      this.on('click', this._playPauseToggle.bind(this));
    }

    createEl()
    {
      this.play = this._createBtnElement('play');
      this.pause = this._createBtnElement('pause');

      const el = document.createElement('div');
      el.classList.add('vjs-big-button');

      el.appendChild(this.play);
      el.appendChild(this.pause);

      return el;
    }

    _createBtnElement(type)
    {
      const btn = document.createElement('button');
      btn.className = `vjs-${type}-button`;
      btn.setAttribute('type', 'button');

      const span = document.createElement('span');
      span.className = 'vjs-icon-placeholder vjs-svg-icon';

      const nsUri = 'http://www.w3.org/2000/svg',
        svg = document.createElementNS(nsUri, 'svg'),
        use = document.createElementNS(nsUri, 'use');

      svg.setAttribute('viewBox', '0 0 512 512');
      use.setAttribute('href', `#vjs-icon-${type}`);

      btn.appendChild(span).appendChild(svg).appendChild(use);
      btn.setAttribute('aria-hidden', 'true');

      return btn;
    }

    _playPauseToggle()
    {
      const tech = this.player().tech(), isPaused = tech.paused();
      isPaused ? tech.play() : tech.pause();

      this.play.setAttribute('aria-hidden', isPaused.toString());
      this.pause.setAttribute('aria-hidden', (!isPaused).toString());
    }
  }
});
