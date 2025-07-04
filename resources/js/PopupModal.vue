<script>
import {defineComponent} from 'vue'
import { OnClickOutside } from '@vueuse/components'
export default defineComponent({
  name: 'LangModal',
  props: {
    isShow: {
      required: true,
      default: false,
      type: Boolean
    },
    href: {
      required: false,
      default: '',
      type: String
    }
  },
  components: {
    OnClickOutside
  },
  data: () => ({
    showModal: false
  }),
  mounted () {
    this.showModal = this.isShow
  },
})
</script>

<template>
<div class="modal" v-if="showModal">
  <OnClickOutside @trigger="showModal = false">
    <div class="modal-content flex flex-col items-center justify-center" ref="popupModal">
      <div class="flex justify-end items-center w-full">
        <a href="#" @click.prevent="showModal = false">
          <svg class="w-[24px] h-[24px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" /></svg>
        </a>
      </div>
      <template v-if="href !== ''">
        <a :href="href"><img class="responsive-image" src="/images/popup.png" alt="Popup Image"></a>
      </template>
      <template v-else>
        <img class="responsive-image" src="/images/popup.png" alt="Popup Image">
      </template>
    </div>
  </OnClickOutside>
</div>
</template>

<style scoped>
.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 999;
}


.modal-content {
  background-color: white;
  padding: 20px;
  border-radius: 5px;
  max-width: 90%;
  box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.3);
  margin: 0 auto;
}

.responsive-image {
  max-width: 100%;
  height: auto;
}
</style>
