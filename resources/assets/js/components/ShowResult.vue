<template>
  <li>
    <a :class="{ active : isActive }" @click.prevent="selectResult()">
      <img :src="result.attributes.poster_url" />
      <span>{{ result.attributes.name }}</span>
    </a>
  </li>
</template>

<script>
import { 
  patchShow 
} from '../vuex/actions/shows'

export default {
  name: 'ShowResult',
  props: ['show', 'result'],
  vuex: {
  	actions: {
      patchShow
    }
  },
  methods: {
    selectResult: function () {
      if (this.isActive) {
        return;
      }

      const data = {
        'data': {
          'type': 'shows',
          'id': this.show.id,
          'attributes': {
            'tmdb_show_id': this.result.id
          }
        }
      }

      this.patchShow(
        this.show.id, data, this.$route.router
      )
    }
  },
  computed: {
    isActive: function () {
      return this.result.id == this.show.attributes.tmdb_show_id
    }
  }
}
</script>