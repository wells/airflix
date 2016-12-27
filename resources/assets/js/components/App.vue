<template>
<div id="app" 
    :class="{ 'toggle-menu' : showMenu, 'toggle-search' : showSearch  }">
  <!-- header -->
  <div id="header">
    <a class="button button-mobile" 
        @click.prevent="toggleMenu">
      <i class="material-icons" v-if="!showMenu">&#xE5D2;</i>
      <i class="material-icons" v-else>&#xE5CD;</i>
    </a>

    <a class="button button-mobile button-right" 
        v-if="showSearchButton"
        @click.prevent="toggleSearch">
      <i class="material-icons" v-if="!showSearch">&#xE8B6;</i>
      <i class="material-icons" v-else>&#xE5CD;</i>
    </a>

    <router-link class="button button-mobile button-right" 
        v-if="hasMoviePath"
        to="/movies">
      <i class="material-icons">&#xE02C;</i>
    </router-link>

    <router-link class="button button-mobile button-right" 
        v-if="hasShowPath"
        to="/shows">
      <i class="material-icons">&#xE639;</i>
    </router-link>

    <router-link id="logo" to="/">
      Airflix
    </router-link>
  </div>

  <!-- menu -->
  <div id="navigation">
    <a class="button button-mobile" 
        @click.prevent="hideMenu">
      <i class="material-icons">&#xE314;</i>
    </a>

    <ul>
      <router-link tag="li" 
          to="/movies" 
          @click.native="hideMenu">
        <a>
          <i class="material-icons">&#xE02C;</i>
          Movies
        </a>
      </router-link>
      <router-link tag="li" 
          to="/shows" 
          @click.native="hideMenu">
        <a>
          <i class="material-icons">&#xE639;</i>
          Shows
        </a>
      </router-link>
      <router-link tag="li" 
          to="/settings" 
          @click.native="hideMenu">
        <a>
          <i class="material-icons">&#xE8B8;</i>
          Settings
        </a>
      </router-link>
    </ul>
  </div>

  <!-- overlay -->
  <a id="overlay" 
      @click.prevent="toggleMenu">
  </a>

  <!-- main view -->
  <div id="content">
    <router-view
      class="view"
      transition
      transition-mode="out-in">
    </router-view>
  </div>

  <!-- footer -->
  <div id="footer">
    Created by Brian Wells
  </div>

  <!-- toasts -->
  <toast></toast>
</div>
</template>

<script>
import Toast from './Toast.vue'
import { mapActions } from 'vuex'

export default {
  name: 'App',

  components: { 
    Toast
  },

  computed: {
    hasMoviePath: function () {
      return this.$route.path.indexOf('/movies/') > -1
    },

    hasShowPath: function () {
      return this.$route.path.indexOf('/shows/') > -1 ||
        this.$route.path.indexOf('/seasons/') > -1
    },

    showSearchButton: function () {
      return this.$route.path == '/movies' ||
        this.$route.path == '/shows';
    },

    showMenu: function () {
      return this.$store.state.interfaces.showMenu
    },

    showSearch: function () {
      return this.$store.state.interfaces.showSearch
    }
  },

  methods: {
    ...mapActions([
      'hideMenu',
      'toggleMenu',
      'toggleSearch'
    ])
  }
}
</script>