<script setup>
import { ref } from 'vue'
const props = defineProps({
  currentLang: {
    required: true,
    type: String
  }
})

const showLangModal = ref(false)
</script>

<template>
<div class="flex justify-center items-center space-x-2 cursor-pointer select-none" @click="showLangModal = true">
  <div><img class="w-[25px]" :src="`/images/flags/${props.currentLang}.png`" :alt="props.currentLang"></div>
  <div class="uppercase">{{ props.currentLang }}</div>
  <div><svg class="w-[20px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M7.41,8.58L12,13.17L16.59,8.58L18,10L12,16L6,10L7.41,8.58Z" /></svg></div>
</div>
<div class="modal" v-if="showLangModal">
  <div class="modal-content flex flex-col items-center justify-center">
    <div class="flex flex-col justify-center items-center mb-10">
      <h2 class="font-action text-2xl">Elegir idioma</h2>
    </div>
    <div class="flex flex-row justify-center items-center gap-7 h-full">
      <a href="/lang/es" class="flex flex-col justify-center items-center space-y-3 hover:opacity-50">
        <div>
          <img class="w-[50px]" src="/images/flags/es.png" alt="Español">
        </div>
        <div>Español</div>
      </a>
      <a href="/lang/en" class="flex flex-col justify-center items-center space-y-3 hover:opacity-50">
        <div>
          <img class="w-[50px]" src="/images/flags/en.png" alt="English">
        </div>
        <div>English</div>
      </a>
    </div>
  </div>
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
  box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.3);
  width: 350px;
  height: 280px;
}
</style>
