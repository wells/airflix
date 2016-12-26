<template>
  <li>
    <a :class="{ active : isActive }" 
        @click.prevent="selectBackdrop()">
      <img :src="backdrop.attributes.file_url" />
    </a>
  </li>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  name: 'ShowBackdrop',

  props: ['show', 'backdrop'],

  methods: {
    ...mapActions([
      'patchShow'
    ]),

    selectBackdrop: function () {
      if (this.isActive) {
        return;
      }

      let payload = {
        url: '/api/shows/' +  this.show.id,
        json: {
          data: {
            type: 'shows',
            id: this.show.id,
            attributes: {
              backdrop_path: this.backdrop.attributes.file_path
            }
          }
        }
      }

      this.patchShow(payload)
    }
  },

  computed: {
    isActive: function () {
      let currentBackdrop = this.show.attributes.backdrop_url
      let backdrop = this.backdrop.attributes.file_path

      return currentBackdrop.indexOf(backdrop) > -1
    }
  }
}
</script>