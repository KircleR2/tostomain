<template>
  <div class="menu-carrousel-container">
    <menu-item v-for="menu in menuData" v-bind:menuData="menu" />
  </div>
  <div @click.prevent="slider.goTo('prev')" class="select-none ml-10 absolute top-56 left-0 cursor-pointer">
    <svg class="fill-black w-[40px]" viewBox="0 0 20 20">
      <circle class="fill-white" cx="10" cy="10" r="7.91"/>
      <path class="shadow-2xl" d="M10,0C4.48,0,0,4.48,0,10s4.48,10,10,10,10-4.48,10-10S15.52,0,10,0Zm6,11H8l3.5,3.5-1.42,1.42-5.92-5.92,5.92-5.92,1.42,1.42-3.5,3.5h8v2Z"/>
    </svg>
  </div>
  <div @click.prevent="slider.goTo('next')" class="select-none mr-10 absolute top-56 right-0 cursor-pointer">
    <svg class="fill-black w-[40px]" viewBox="0 0 20 20">
      <circle class="fill-white" cx="10" cy="10" r="7.91"/>
      <path class="shadow-2xl" d="M20,10c0,5.52-4.48,10-10,10S0,15.52,0,10,4.48,0,10,0s10,4.48,10,10m-16,1H12l-3.5,3.5,1.42,1.42,5.92-5.92-5.92-5.92-1.42,1.42,3.5,3.5H4v2Z"/>
    </svg>
  </div>
</template>

<script>
import {defineComponent} from 'vue'
import {tns} from 'tiny-slider'
import 'tiny-slider/dist/tiny-slider.css'
import MenuItem from './MenuItem.vue'
import menuData from './menuData.json'

export default defineComponent({
  components: {
    MenuItem
  },
  data: () => ({
    slider: null,
    menuData: menuData
  }),
  mounted () {
    this.slider = tns({
      container: '.menu-carrousel-container',
      items: 1,
      slideBy: 1,
      loop: false,
      mouseDrag: true,
      swipeAngle: 15,
      preventScrollOnTouch: 'auto',
      speed: 400,
      controls: false,
      autoplayButton: false,
      responsive: {
        300: {
          items: 1,
          gutter: 10,
          edgePadding: 30
        },
        600: {
          items: 1.5,
          gutter: 10,
          edgePadding: 50
        },
        900: {
          items: 2.5,
          gutter: 20,
        },
        1300: {
          items: 3.5,
          gutter: 25,
          edgePadding: 57
        },
        1400: {
          items: 2.5,
          gutter: 25,
          edgePadding: 109
        },
        1440: {
          items: 3.5,
          gutter: 25,
          edgePadding: 125
        },
        1920: {
          items: 3.8,
          gutter: 25,
          edgePadding: 239
        },
        1921: {
          items: 4.5,
          gutter: 30,
          edgePadding: 250
        },
      }
    })
  },
  unmounted () {
    // this.slider.destroy()
  },
  methods: {}
})
</script>

<style scoped>

</style>
