<template>
  <div class="card poster">
    <router-link :to="{ path: link }">
      <img :src="movie.attributes.poster_url" />
      <span>{{ movie.attributes.title }}</span>
    </router-link>
  </div>
</template>

<script>
export default {
  name: 'MovieCard',

  props: ['movie'],

  computed: {
    link: function () {
      return this.movie.attributes.tmdb_movie_id == 0 ? 
        '/movies/' + this.movie.id + '/edit' : 
        '/movies/' + this.movie.id
    }
  }
}
</script>