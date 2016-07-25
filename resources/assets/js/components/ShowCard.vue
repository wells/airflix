<template>
  <div class="card poster">
    <a v-link="{ path: link }">
      <img :src="show.attributes.poster_url" />
      <span>{{ show.attributes.name }}</span>
    </a>
  </div>
</template>

<script>
export default {
  name: 'ShowCard',
  props: ['show'],
  computed: {
    link: function () {
      return this.show.attributes.tmdb_show_id == 0 ? 
        '/shows/' + this.show.id + '/edit' : 
        '/shows/' + this.show.id
    }
  }
}
</script>