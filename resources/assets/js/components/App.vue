<template>
<div :class="{ 'toggle-menu' : showMenu, 'toggle-search' : showSearch  }">
  <!-- header -->
  <div id="header">
    <a class="button button-mobile" @click.prevent="toggleMenu">
      <i class="material-icons" v-if="!showMenu">&#xE5D2;</i>
      <i class="material-icons" v-else>&#xE5CD;</i>
    </a>

    <a class="button button-mobile button-right" @click.prevent="toggleSearch" v-if="showSearchButton">
      <i class="material-icons" v-if="!showSearch">&#xE8B6;</i>
      <i class="material-icons" v-else>&#xE5CD;</i>
    </a>

    <a class="button button-mobile button-right" 
        v-if="hasMoviePath"
        v-link="{ path: '/movies' }">
      <i class="material-icons">&#xE02C;</i>
    </a>

    <a class="button button-mobile button-right" 
        v-if="hasShowPath"
        v-link="{ path: '/shows' }">
      <i class="material-icons">&#xE639;</i>
    </a>

    <a id="logo" v-link="{ path: '/' }">
      Airflix
    </a>
  </div>

  <!-- menu -->
  <div id="navigation">
    <a class="button button-mobile" @click.prevent="hideMenu">
      <i class="material-icons">&#xE314;</i>
    </a>

    <ul>
      <li v-link-active>
          <a v-link="{ path: '/movies' }" @click="hideMenu">
            <i class="material-icons">&#xE02C;</i>
            Movies
          </a>
      </li>
      <li v-link-active>
        <a v-link="{ path: '/shows' }" @click="hideMenu">
          <i class="material-icons">&#xE639;</i>
          Shows
        </a>
      </li>
      <li v-link-active>
        <a v-link="{ path: '/settings' }" @click="hideMenu">
          <i class="material-icons">&#xE8B8;</i>
          Settings
        </a>
      </li>
    </ul>
  </div>

  <!-- overlay -->
  <a id="overlay" @click.prevent="toggleMenu"></a>

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
import store from '../vuex/store'
import Toast from './Toast.vue'
import {
  hideMenu,
  toggleMenu, 
  toggleSearch
} from '../vuex/actions/mobile'

export default {
  name: 'App',
  components: { 
    Toast
  },
  store,
  vuex: {
    getters: {
      showMenu: ({ mobile }) => mobile.showMenu,
      showSearch: ({ mobile }) => mobile.showSearch
    },
    actions: {
      hideMenu,
      toggleMenu,
      toggleSearch
    }
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
    }
  }
}
</script>