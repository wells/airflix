<template>
  <li>
    <a :class="{ active : isActive }" 
        @click.prevent="selectResult()">
      <img :src="result.attributes.poster_url" />
      <span>{{ result.attributes.name }}</span>
    </a>
  </li>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  name: 'ShowResult',

  props: ['show', 'result'],

  methods: {
    ...mapActions([
      'patchShow'
    ]),

    selectResult: function () {
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
              tmdb_show_id: this.result.id
            }
          }
        },
        redirect: '/shows/' + this.show.id
      }

      this.patchShow(payload)
    }
  },

  computed: {
    isActive: function () {
      return this.result.id == this.show.attributes.tmdb_show_id
    }
  }
}
</script>