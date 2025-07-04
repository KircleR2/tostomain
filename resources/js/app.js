import './bootstrap';

import { gsap } from "gsap"
import { ScrollTrigger } from "gsap/ScrollTrigger"
import { ScrollToPlugin } from "gsap/ScrollToPlugin"

gsap.registerPlugin(ScrollTrigger, ScrollToPlugin);

import {createApp} from 'vue/dist/vue.esm-bundler';
import MenuCarrousel from './MenuCarrousel.vue'
import BranchesCarrousel from './BranchesCarrousel.vue'
import RegisterForm from './RegisterForm.vue'
import LoginForm from './LoginForm.vue'
import Dashboard from './Dashboard.vue'
import RecoveryPasswordForm from './RecoveryPasswordForm.vue'
import LangModal from './LangModal.vue'
import PopupModal from './PopupModal.vue'
import {i18nVue} from 'laravel-vue-i18n'

createApp(MenuCarrousel)
.use(i18nVue, {
    resolve: lang => require(`../lang/${lang}.json`),
})
.mount('#menu-carrousel')
createApp(BranchesCarrousel).mount('#branches-carrousel')
createApp(RegisterForm).mount('#register')
createApp(LoginForm).mount('#login')
createApp(RecoveryPasswordForm).mount('#recovery-password')
createApp(Dashboard).mount('#dashboard')

const langApp = createApp({
  components: {
    'lang-modal': LangModal
  }
})
langApp.mount('#lang-modal')

const langMobileApp = createApp({
  components: {
    'lang-modal': LangModal
  }
})
langMobileApp.mount('#lang-mobile-modal')

const popupApp = createApp({
  components: {
    'popup-modal': PopupModal
  }
})
popupApp.mount('#popup-modal')

const menuIcon = document.getElementById('menu-icon')
const closeIcon = document.getElementById('close-icon')
const fullMenu = document.getElementById('full-menu')
const menuContainerList = fullMenu?.querySelector('ul')

InitHomeAnimations()
InitMenuAnimation()
InitMenuScroll()
initMenuActions()

function InitHomeAnimations () {
  const coffeeSeedsImage = document.querySelector('.coffee-seeds-image');

  const tl = gsap.timeline()
  tl.to(coffeeSeedsImage, {
    duration: 0.9,
    y: -8,
    repeat: -1,
    yoyo: true,
    ease: 'power1.inOut'
  })

  const coffeeCup = document.querySelector('.coffee-cup')
  tl.from(coffeeCup, {
    opacity: 0,
    duration: 0.9,
    y: 100,
    ease: 'power1.inOut'
  })
}

function InitMenuAnimation () {
  window.addEventListener('scroll', function () {
    const topMenuHeight = document.querySelector('.nav')?.offsetHeight
    if (topMenuHeight) {
      const fromTop = window.scrollY + topMenuHeight

      const scrollItems = document.querySelectorAll('section')
      let cur = Array.from(scrollItems).map(function (item) {
        if (item.offsetTop < fromTop)
          return item
      })
      cur = cur.filter(Boolean)
      cur = cur[cur.length - 1]
      const id = cur ? cur.id : ''

      const menuItems = document.querySelectorAll('.main-menu li>a')
      menuItems.forEach(function (item) {
        item.classList.remove('link-active')
        if (item.getAttribute('href') === '#' + id) {
          item.classList.add('link-active')
        }
        else {
          item.classList.remove('link-active')
        }
      })

      if (window.scrollY > topMenuHeight) {
        document.querySelector('.nav').classList.add('menu-fixed')
        document.querySelector('.nav').classList.remove('menu')
      }
      else {
        document.querySelector('.nav').classList.remove('menu-fixed')
        document.querySelector('.nav').classList.add('menu')
      }
    }
  })
}

function InitMenuScroll () {
  document.addEventListener('DOMContentLoaded', function () {
    const links = document.querySelectorAll('a[href^="#"]')
    const topMenuHeight = document.querySelector('.nav')?.offsetHeight
    if (topMenuHeight) {
      links.forEach(function (link) {
        link.addEventListener('click', function (e) {
          e.preventDefault()

          const target = document.querySelector(link.getAttribute('href'))
          const scrollToOffset = target.offsetTop - topMenuHeight + 1

          gsap.to(window, {duration: 1, scrollTo: scrollToOffset})
          closeMenu(e)
        })
      })
    }
  })
}

function openMenu () {
  fullMenu.classList.remove('hidden', 'md:flex')
  fullMenu.classList.add('fixed', 'inset-0', 'bg-white', 'z-50', 'flex', 'flex-col', 'items-center', 'justify-center')
  menuContainerList.classList.add('space-y-42')
  menuContainerList.classList.remove('space-x-10')
  document.body.classList.add('overflow-hidden')
}

function closeMenu (e) {
  e.preventDefault()
  fullMenu.classList.add('hidden', 'md:flex')
  fullMenu.classList.remove('fixed', 'inset-0', 'bg-white', 'z-50', 'flex', 'flex-col', 'items-center', 'justify-center')
  menuContainerList.classList.remove('space-y-42')
  menuContainerList.classList.add('space-x-10')
  document.body.classList.remove('overflow-hidden')
}
function initMenuActions () {
  if (menuIcon) {
    menuIcon.addEventListener('click', openMenu)
  }
  if (closeIcon) {
    closeIcon.addEventListener('click', closeMenu)
  }
}
