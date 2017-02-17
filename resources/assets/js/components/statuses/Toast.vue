<template>
  <div class="toast" 
      :class="[ position, toastContext, activeToast ? 'active' : '', hideProgress ? '' : 'has-progress' ]" 
      @mouseover="pause" 
      @mouseout="animate">
    <div class="action">
      <a class="close" 
          aria-label="Close" 
          @click.prevent="clear">
        <span aria-hidden="true">
          <i class="material-icons">&#xE5CD;</i>
        </span>
      </a>
    </div>
    <div v-html="message"></div>
    <div class="progress-bar" 
        :class="{ active: activeProgressBar }" 
        :style="style" 
        v-show="!hideProgress">
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'

// this delays trigger of the first toast (queue)
const DEBOUNCE = 300 // in ms

// hide toast after default duration
const DURATION = 3000 // in ms

// this transition time is set in scss and defines how long it takes to animate in/out the toast element
const TOAST_ANIMATION = 300 // in ms

export default {
  name: 'Toast',
  replace: true,

  watch: {
    'toast': function (options) {
      this.addToQueue(options)
    }
  },

  computed: {
    ...mapState({
      toast: state => state.toasts.current
    }),

    toastContext: function () {
      return !this.context ? `` : `toast-${this.context}`
    }
  },

  data: function () {
    return {
      activeToast: false,
      activeProgressBar: false,
      animation: null,
      animationInProgress: false,
      queue: [],
      style: {
        transition: 'width 0s'
      }
    }
  },

  props: {
    context: {
      type: String,
      default: '',
    },

    duration: {
      type: Number,
      default: DURATION,
    },

    message: {
      type: String,
      default: 'Done!',
    },

    onAjaxErrors: {
      type: Boolean,
      default: false,
    },

    position: {
      type: String,
      default: 'bottom left',
    },

    hideProgress: {
      type: Boolean,
      default: false,
    },

    debounce: {
      type: Number,
      default: DEBOUNCE,
    }
  },

  methods: {
    pause: function () {
      this.activeProgressBar = false
      clearTimeout(this.animation)
      this.style.transition = 'width 0.1s'
    },

    clear: function () {
      this._toastAnimation = setTimeout(() => {
        this.activeProgressBar = false
        this.animationInProgress = false
        this.style.transition = 'width 0s'
        this.activeToast = false
        clearTimeout(this.animation)
        // show next toast from the queue
        if (this.queue.length > 0) {
          this._toastAnimation = setTimeout(() => {
            let toast = this.queue.shift()
            this.show(toast)
          }, 0) // this set to 0 instead of TOAST_ANIMATION in purpose, so queued messages pop a little bit quicker, so user can close them off quickly
        }
      }, TOAST_ANIMATION) // we need to wait till toast is gone off the screen to clear it and then call next toast
    },

    animate: function () {
      this.style.transition = 'width ' + this.duration / 1000 + 's'
      this.activeProgressBar = true
      this.animation = setTimeout(this.clear, this.duration)
    },

    show: function (options) {
      this.context = 'default'
      this.animationInProgress = true
      this.message = options.message || this.message
      this.context = options.context || this.context
      this.debounce = options.debounce || this.debounce
      this.duration = options.duration || this.duration
      this.hideProgress = options.hideProgress || this.hideProgress
      this.position = options.position || this.position
      if (options.success) {
        this.context = 'success'
        this.message = options.success
      }
      if (options.info) {
        this.context = 'info'
        this.message = options.info
      }
      if (options.warning) {
        this.context = 'warning'
        this.message = options.warning
      }
      if (options.error) {
        this.context = 'danger'
        this.message = options.error
      }
      // wait for dom element (so that position class can take effect when triggered via event)
      setTimeout(() => {
        this.activeToast = true
        this.animate()
      }, 100)
    },

    addToQueue: function (options) {
      if (this.animationInProgress || this.queue.length > 0) {
        // if some other toast is currently animating, add it to the queue
        this.queue.push(options)
      } else {
        // if first toast, show it
        setTimeout(() => {
          this.show(options)
        }, this.debounce)
      }
    }
  },

  events: {
    'end::ajax': function (options) {
      if (this.onAjaxErrors && options && options.error) {
        this.addToQueue(options)
      }
    },
    'show::toast': function (options) {
      this.addToQueue(options)
    }
  },

  destroyed: function () {
    clearTimeout(this._animation)
    clearTimeout(this._toastAnimation)
  }
}
</script>