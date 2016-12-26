<template>
  <li>
    <a :class="{ active : isActive }" 
        @click.prevent="selectPoster()">
      <img :src="poster.attributes.file_url" />
    </a>
  </li>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  name: 'ShowPoster',

  props: ['show', 'poster'],

  methods: {
    ...mapActions([
      'patchShow'
    ]),

    selectPoster: function () {
      if (this.isActive) {
        return;
      }

      let payload = {
        url: '/api/shows/' + this.show.id,
        json: {
          data: {
            type: 'shows',
            id: this.show.id,
            attributes: {
              poster_path: this.poster.attributes.file_path
            }
          }
        }
      }

      this.patchShow(payload)
    }
  },

  computed: {
    isActive: function () {
      let currentPoster = this.show.attributes.poster_url
      let poster = this.poster.attributes.file_path

      return currentPoster.indexOf(poster) > -1
    }
  }
}
</script>