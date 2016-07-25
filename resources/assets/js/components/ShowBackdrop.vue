<template>
  <li>
    <a :class="{ active : isActive }" @click.prevent="selectBackdrop()">
      <img style="width: 300px" :src="backdrop.attributes.file_url" />
    </a>
  </li>
</template>

<script>
import { 
  patchShow 
} from '../vuex/actions/shows'

export default {
  name: 'ShowBackdrop',
  props: ['show', 'backdrop'],
  vuex: {
  	actions: {
      patchShow
    }
  },
  methods: {
    selectBackdrop: function () {
      if (this.isActive) {
        return;
      }

      const data = {
        'data': {
          'type': 'shows',
          'id': this.show.id,
          'attributes': {
            'backdrop_path': this.backdrop.attributes.file_path
          }
        }
      }

      this.patchShow(
        this.show.id, data
      )
    }
  },
  computed: {
    isActive: function () {
      var currentBackdrop = this.show.attributes.backdrop_url
      var backdrop = this.backdrop.attributes.file_path
      return currentBackdrop.indexOf(backdrop) > -1
    }
  }
}
</script>