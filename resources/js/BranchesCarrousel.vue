<template>
  <div class="branches-carrousel flex flex-row space-x-14">
    <div class="select-none" v-for="branch in branches">
      <div class="flex flex-col justify-center items-center">
        <div class="mb-3">
          <img class="rounded-full border-[20px] border-white" :src="branch.img" :alt="branch.name">
        </div>
        <div class="flex flex-col items-start justify-center mt-4">
          <div class="text-left w-full text-white">
            <h4 class="font-action text-4xl">{{ branch.name }}</h4>
            <h6 class="text-lg">{{ branch.location }}</h6>
          </div>
          <div class="mt-5 space-y-3 text-xl">
            <div v-for="day in branch.days">
              <span v-if="day.isOpen" class="text-[#F89C24]">{{ day.name }}<br>{{ day.hours }}</span>
              <span v-else class="text-[#BBBBBB]">{{ day.name }}: {{ day.hours }}</span>
            </div>
          </div>
          <div class="flex flex-row space-x-5 mt-10">
            <a :href="branch.mapsUrl" target="_blank" class="flex justify-center items-center bg-white rounded-full p-2 shadow-2xl">
              <svg class="fill-red-600 w-[35px]" viewBox="0 0 24 24"><path d="M18.27 6C19.28 8.17 19.05 10.73 17.94 12.81C17 14.5 15.65 15.93 14.5 17.5C14 18.2 13.5 18.95 13.13 19.76C13 20.03 12.91 20.31 12.81 20.59C12.71 20.87 12.62 21.15 12.53 21.43C12.44 21.69 12.33 22 12 22H12C11.61 22 11.5 21.56 11.42 21.26C11.18 20.53 10.94 19.83 10.57 19.16C10.15 18.37 9.62 17.64 9.08 16.93L18.27 6M9.12 8.42L5.82 12.34C6.43 13.63 7.34 14.73 8.21 15.83C8.42 16.08 8.63 16.34 8.83 16.61L13 11.67L12.96 11.68C11.5 12.18 9.88 11.44 9.3 10C9.22 9.83 9.16 9.63 9.12 9.43C9.07 9.06 9.06 8.79 9.12 8.43L9.12 8.42M6.58 4.62L6.57 4.63C4.95 6.68 4.67 9.53 5.64 11.94L9.63 7.2L9.58 7.15L6.58 4.62M14.22 2.36L11 6.17L11.04 6.16C12.38 5.7 13.88 6.28 14.56 7.5C14.71 7.78 14.83 8.08 14.87 8.38C14.93 8.76 14.95 9.03 14.88 9.4L14.88 9.41L18.08 5.61C17.24 4.09 15.87 2.93 14.23 2.37L14.22 2.36M9.89 6.89L13.8 2.24L13.76 2.23C13.18 2.08 12.59 2 12 2C10.03 2 8.17 2.85 6.85 4.31L6.83 4.32L9.89 6.89Z" /></svg>
            </a>
            <a :href="branch.wazeUrl" target="_blank" class="flex justify-center items-center bg-white rounded-full p-2 shadow-2xl">
              <svg class="fill-cyan-500 w-[35px]" viewBox="0 0 24 24"><path d="M20.54,6.63C21.23,7.57 21.69,8.67 21.89,9.82C22.1,11.07 22,12.34 21.58,13.54C21.18,14.71 20.5,15.76 19.58,16.6C18.91,17.24 18.15,17.77 17.32,18.18C17.73,19.25 17.19,20.45 16.12,20.86C15.88,20.95 15.63,21 15.38,21C14.27,21 13.35,20.11 13.31,19C13.05,19 10.73,19 10.24,19C10.13,20.14 9.11,21 7.97,20.87C6.91,20.77 6.11,19.89 6.09,18.83C6.1,18.64 6.13,18.44 6.19,18.26C4.6,17.73 3.21,16.74 2.19,15.41C1.86,14.97 1.96,14.34 2.42,14C2.6,13.86 2.82,13.78 3.05,13.78C3.77,13.78 4.05,13.53 4.22,13.15C4.46,12.43 4.6,11.68 4.61,10.92C4.64,10.39 4.7,9.87 4.78,9.35C5.13,7.62 6.1,6.07 7.5,5C9.16,3.7 11.19,3 13.29,3C14.72,3 16.13,3.35 17.4,4C18.64,4.62 19.71,5.5 20.54,6.63M16.72,17.31C18.5,16.5 19.9,15.04 20.59,13.21C22.21,8.27 18,4.05 13.29,4.05C12.94,4.05 12.58,4.07 12.23,4.12C9.36,4.5 6.4,6.5 5.81,9.5C5.43,11.5 6,14.79 3.05,14.79C4,16 5.32,16.93 6.81,17.37C7.66,16.61 8.97,16.69 9.74,17.55C9.85,17.67 9.94,17.8 10,17.94C10.59,17.94 13.2,17.94 13.55,17.94C14.07,16.92 15.33,16.5 16.35,17.04C16.5,17.12 16.6,17.21 16.72,17.31M10.97,10.31C10.39,10.34 9.88,9.9 9.85,9.31C9.82,8.73 10.27,8.23 10.85,8.19C11.43,8.16 11.94,8.61 11.97,9.25C12,9.8 11.56,10.27 11,10.29L10.97,10.31M15.66,10.31C15.08,10.34 14.57,9.9 14.54,9.31C14.5,8.73 14.96,8.23 15.54,8.19C16.12,8.16 16.63,8.61 16.66,9.25C16.68,9.8 16.25,10.27 15.66,10.29V10.31M9.71,12.07C9.65,11.79 9.84,11.5 10.12,11.45C10.4,11.4 10.68,11.58 10.74,11.86V11.86C11.09,12.97 12.16,13.69 13.32,13.6C14.46,13.66 15.5,12.96 15.89,11.88C16.03,11.62 16.35,11.5 16.6,11.65C16.78,11.75 16.89,11.92 16.89,12.12C16.7,12.83 16.26,13.45 15.66,13.88C14.97,14.36 14.16,14.63 13.32,14.64H13.21C11.58,14.71 10.11,13.64 9.68,12.06L9.71,12.07Z" /></svg>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent } from 'vue'
import { tns } from 'tiny-slider'
import 'tiny-slider/dist/tiny-slider.css'
export default defineComponent({
  data: () => ({
    slider: null,
    branches: [
      {
        name: 'Alta Plaza Mall',
        img: 'images/branches/altaplaza-mall.png',
        location: 'AltaPlaza Mall, Nivel 1',
        mapsUrl: 'https://maps.app.goo.gl/rHJhbNHzumxXYiCW6',
        wazeUrl: 'waze://?q=Tosto Coffee Altaplaza Mall',
        days: [
          {
            name: 'Lunes a Domingos',
            isOpen: true,
            hours: '9:00 am a 10:00 pm'
          }
        ]
      },
      {
        name: 'Centennial Center',
        img: 'images/branches/centenial.png',
        location: 'Condado del Rey, Plaza de Global Bank, Panamá',
        mapsUrl: 'https://goo.gl/maps/noiTSQbsu8wxAMfv8?coh=178573&entry=tt',
        wazeUrl: 'waze://?q=Tosto Coffee Co. Centennial',
        days: [
          {
            name: 'Lunes',
            isOpen: false,
            hours: 'Cerrado'
          },
          {
            name: 'Martes a Viernes',
            isOpen: true,
            hours: '7:00 am a 10:00 pm'
          },
          {
            name: 'Sábados y Domingos',
            isOpen: true,
            hours: '8:00 am a 10:00 pm'
          }
        ]
      },
      {
        name: 'San Francisco',
        img: 'images/branches/san-francisco.png',
        location: 'Calle 74. Plaza Spot 74, San Francisco, Panamá',
        mapsUrl: 'https://goo.gl/maps/SfzQodhhNq7XCQjW9?coh=178573&entry=tt',
        wazeUrl: 'waze://?q=Tosto Coffee San Francisco',
        days: [
          {
            name: 'Lunes a Viernes',
            isOpen: true,
            hours: '7:00 am a 10:00 pm'
          },
          {
            name: 'Sábados y Domingos',
            isOpen: true,
            hours: '8:00 am a 10:00 pm'
          }
        ]
      },
      {
        name: 'Calle Uruguay',
        img: 'images/branches/calle-uruguay.png',
        location: 'Av Federico Boyd y Calle 49 Este, Bella Vista, Panamá',
        mapsUrl: 'https://goo.gl/maps/zxALbH6mrKbMVs7W8?coh=178573&entry=tt',
        wazeUrl: 'waze://?q=Tosto Coffee Calle Uruguay',
        days: [
          {
            name: 'Lunes',
            isOpen: false,
            hours: 'Cerrado'
          },
          {
            name: 'Martes a Viernes',
            isOpen: true,
            hours: '7:00 am a 10:00 pm'
          },
          {
            name: 'Sábados y Domingos',
            isOpen: true,
            hours: '8:00 am a 10:00 pm'
          }
        ]
      },
    ]
  }),
  mounted () {
    this.slider = tns({
      container: '.branches-carrousel',
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
          items: 1.2,
          edgePadding: 30
        },
        900: {
          items: 2.5,
          edgePadding: 50,
        },
        1440: {
          items: 3.2,
          edgePadding: 100,
        },
        1441: {
          items: 3.1,
          edgePadding: 250,
        },
      }
    })
  }
})
</script>

<style scoped>

</style>
