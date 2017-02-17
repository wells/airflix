<template>
<li>
  <img v-if="episode.attributes.still_url" :src="episode.attributes.still_url" />

  <div class="summary">
    <h3>
      S{{ episode.attributes.season | zeroPad(2) }}E{{ episode.attributes.episode | zeroPad(2) }} 
      &ndash; 
      {{ episode.attributes.name }}
    </h3>
    <p>
      <a class="button" 
          :class="{ disabled : isDisabled }" 
          :href="'/downloads/episodes/' + episode.id">
        <i class="material-icons">&#xE039;</i> Watch
      </a>
    </p>
    <p v-if="episode.attributes.overview">
      {{ episode.attributes.overview }}
    </p>
    <ul class="tags">
      <li v-if="episode.attributes.air_date">
        <div class="key">Air Date:</div>
        <div class="value">
          {{ episode.attributes.air_date | moment("MMMM Do Y") }}
        </div>
      </li>
      <li>
        <div class="key">Total Views:</div>
        <div class="value">
          {{ episode.attributes.total_views }}
        </div>
      </li>
    </ul>
  </div>
</li>
</template>

<script>
export default {
  name: 'Episode',

  props: ['episode'],

  computed: {
    isDisabled: function () {
      return ! this.episode.attributes.has_file
    }
  }
}
</script>