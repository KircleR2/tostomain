<template>
  <div class="select-none h-[610px]">
    <div class="flex flex-col h-full justify-center items-center bg-[#009F9A] rounded-2xl py-10 px-8 space-y-4 border border-[#009388]">
      <div class="mb-3">
        <img class="rounded-full" :src="menuData.img" alt="">
      </div>
      <div class="flex flex-col space-y-8">
        <div>
          <h4 class="text-black font-action text-[29px]">{{ $t(menuData.name) }}</h4>
          <p class="text-gray-800 font-medium text-[17px]">{{ $t(menuData.description) }}</p>
          <div class="mt-5">
            <svg class="w-[100px]" viewBox="0 0 231 34" fill="none">
              <path d="M17 24.4658L25.755 29.7499L23.4316 19.7908L31.1666 13.0899L20.9808 12.2116L17 2.83325L13.0191 12.2116L2.83331 13.0899L10.5541 19.7908L8.24498 29.7499L17 24.4658Z" fill="#F89C24"/>
              <path d="M64 24.4658L72.755 29.7499L70.4316 19.7908L78.1666 13.0899L67.9808 12.2116L64 2.83325L60.0191 12.2116L49.8333 13.0899L57.5541 19.7908L55.245 29.7499L64 24.4658Z" fill="#F89C24"/>
              <path d="M114 24.4658L122.755 29.7499L120.432 19.7908L128.167 13.0899L117.981 12.2116L114 2.83325L110.019 12.2116L99.8333 13.0899L107.554 19.7908L105.245 29.7499L114 24.4658Z" fill="#F89C24"/>
              <path d="M164 24.4658L172.755 29.7499L170.432 19.7908L178.167 13.0899L167.981 12.2116L164 2.83325L160.019 12.2116L149.833 13.0899L157.554 19.7908L155.245 29.7499L164 24.4658Z" fill="#F89C24"/>
              <path d="M214 24.4658L222.755 29.7499L220.432 19.7908L228.167 13.0899L217.981 12.2116L214 2.83325L210.019 12.2116L199.833 13.0899L207.554 19.7908L205.245 29.7499L214 24.4658Z" fill="#F89C24"/>
            </svg>
          </div>
        </div>
        <div class="flex justify-center items-center">
          <a class="btn-action bg-black text-2xl px-10" :href="menuData.href">{{ $t('Ver Menú') }}</a>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {defineComponent} from 'vue'

export default defineComponent({
  //code
  props: ['menuData']
})
</script>

<style scoped>

</style>
