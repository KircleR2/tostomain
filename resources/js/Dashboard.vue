<script setup>
import {onMounted, reactive, ref} from 'vue'
import Toastify from 'toastify-js'
import "toastify-js/src/toastify.css"
import CheckIcon from './components/CheckIcon.vue'

const fetchDashboard = ref(false)
const fetchStorePoints = ref(false)
const fetchGifts = ref(false)
const registerForm = ref(null)
const isSuccess = ref(false)
const errorMessage = ref(null)
const errorClose = ref(null)
const errorText = ref(null)

const userInitialData = {
  fullname: '',
  email: '',
  phone: '',
  points: '',
  balance: '',
  ref: ''
}
const userData = reactive(structuredClone(userInitialData))
const products = ref([])
const gifts = ref([])

onMounted(() => {
  fetchDashboard.value = false
  // Fetch CSRF token on component mount
  fetchCsrfToken()
  getUserData()
})

// Function to fetch CSRF token
function fetchCsrfToken() {
  axios.get('/sanctum/csrf-cookie')
    .catch(error => {
      console.error('Error fetching CSRF token:', error)
    })
}

function getFirstName (){
  return userData.fullname.split(' ')[0]
}
function showError (message) {
  console.log(errorText.value)
  errorText.value.innerHTML = message
  errorMessage.value.classList.remove('hidden')
  setTimeout(hideError, 5000)
}

function hideError () {
  errorMessage.value.classList.add('hidden')
}

async function getUserDataRequest () {
  return new Promise((resolve, reject) => {
    axios.post('/api/dashboard', {}, {
      withCredentials: true // Ensure cookies are sent with the request
    })
    .then(response => {
      resolve(response)
    })
    .catch(error => {
      reject(error)
    })
  })
}

function getUserData () {
  fetchDashboard.value = true
  // First get CSRF token, then make API call
  axios.get('/sanctum/csrf-cookie')
    .then(() => {
      setTimeout(() => {
        getUserDataRequest()
        .then(response => {
          fetchDashboard.value = false
          if (response.data.code === 0) {
            Object.assign(userData, response.data.user)
            getGifts()
            getStorePoints()
            isSuccess.value = true
          } else {
            isSuccess.value = false
          }
        })
        .catch(() => {
          window.location.href = '/login'
        })
      }, 1500)
    })
    .catch(error => {
      console.error('CSRF token error:', error)
      window.location.href = '/login'
    })
}

function getStorePoints () {
  fetchStorePoints.value = true
  // First get CSRF token, then make API call
  axios.get('/sanctum/csrf-cookie')
    .then(() => {
      setTimeout(() => {
        axios.post('/api/store-points', {}, {
          withCredentials: true // Ensure cookies are sent with the request
        })
        .then(response => {
          fetchStorePoints.value = false
          if (response.data.code === 0) {
            products.value = response.data.products
          } else {
            products.value = []
          }
        })
        .catch(() => {
          window.location.href = '/login'
        })
      }, 1500)
    })
    .catch(error => {
      console.error('CSRF token error:', error)
      window.location.href = '/login'
    })
}

function getGifts () {
  fetchGifts.value = true
  // First get CSRF token, then make API call
  axios.get('/sanctum/csrf-cookie')
    .then(() => {
      setTimeout(() => {
        axios.post('/api/gifts', {}, {
          withCredentials: true // Ensure cookies are sent with the request
        })
        .then(response => {
          fetchGifts.value = false
          if (response.data.code === 0) {
            gifts.value = response.data.gifts
          } else {
            gifts.value = []
          }
        })
        .catch(() => {
          window.location.href = '/login'
        })
      }, 1500)
    })
    .catch(error => {
      console.error('CSRF token error:', error)
      window.location.href = '/login'
    })
}

function buyProduct(productId) {
  if (confirm('¿Está seguro que desea canjear el producto?')) {
    fetchStorePoints.value = true
    // First get CSRF token, then make API call
    axios.get('/sanctum/csrf-cookie')
      .then(() => {
        axios.post('/api/buy-product', {
          regaloId: productId
        }, {
          withCredentials: true // Ensure cookies are sent with the request
        })
        .then(response => {
          if (response.data.code === 0) {
            Toastify({
              text: response.data.message,
              duration: 3000,
              close: true,
              gravity: "top", // `top` or `bottom`
              position: "center", // `left`, `center` or `right`
              stopOnFocus: true, // Prevents dismissing of toast on hover
              style: {
                background: "linear-gradient(to right, #00b09b, #96c93d)",
              },
              onClick: function(){} // Callback after click
            }).showToast();

            getUserDataRequest()
            .then(response => {
              if (response.data.code === 0) {
                Object.assign(userData, response.data.user)
                getGifts()
                getStorePoints()
              }
            })
          }
        })
        .catch(() => {
          window.location.href = '/login'
        })
      })
      .catch(error => {
        console.error('CSRF token error:', error)
        window.location.href = '/login'
      })
  }
}

function formatNumber (nStr) {
  return parseFloat(nStr).toFixed(2)
}

async function shareRef () {
  //check if native sharing is available
  const urlToShare = `${window.location.origin}/registro-club-elite?ref=${userData.ref}`
  if (navigator.share) {
    try {
      const shareData = {
        title: 'Tosto Coffee - Link Referido',
        text: 'Te mando un regalo 🎁 de Tosto Coffee. Abre el link',
        url: urlToShare,
      }
      await navigator.share(shareData)
      console.log('Share successfull')
    }
    catch (err) {
      console.error('Error: ', err)
    }
  }
  else {

    console.warn('Native Web Sharing not supported')
    window.open(`https://wa.me/?text=${encodeURI(urlToShare)}`, '_blank')
  }
}

</script>

<template>
  <div v-show="fetchDashboard === true" class="flex flex-col items-center justify-center relative w-full h-screen space-y-11 overflow-hidden">
    <div class="absolute top-0 left-0 w-full flex justify-between opacity-5 -z-10">
      <div><img class="w-full h-screen" src="images/bg-coffee-elements.svg" alt=""></div>
      <div><img class="w-[500px] mt-16" src="images/right-coffee.svg" alt=""></div>
    </div>
    <div class="absolute bg-[#FFF0E5] top-0 left-0 w-full h-full -z-20"></div>
    <div>
      <img class="w-[180px]" src="images/logo.svg" alt="Logo">
    </div>
    <div>Cargando, por favor espere...</div>
  </div>
  <div v-show="!fetchDashboard">
    <nav class="nav menu-dashboard fixed top-0">
      <div class="container mx-auto flex items-center justify-between py-7 px-6 sm:px-0 md:px-0">
        <div class="flex items-center">
          <a href="/dashboard">
            <img src="images/logo.svg" alt="Logo" class="h-[55px] sm:h-[60px] md:h-16">
          </a>
        </div>
        <div class="flex md:hidden items-center justify-center space-x-6">
          <div>
            <a href="logout">
              <svg class="w-[30px]" viewBox="0 0 24 24"><path d="M14.08,15.59L16.67,13H7V11H16.67L14.08,8.41L15.5,7L20.5,12L15.5,17L14.08,15.59M19,3A2,2 0 0,1 21,5V9.67L19,7.67V5H5V19H19V16.33L21,14.33V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5C3,3.89 3.89,3 5,3H19Z" /></svg>
            </a>
          </div>
        </div>
        <div id="full-menu" class="hidden md:flex items-center text-2xl md:space-x-14">
          <div class="flex md:hidden justify-between absolute top-20 left-0 w-full opacity-5 -z-10">
            <div><img class="w-[200px] mt-16" src="images/left-coffee.svg" alt=""></div>
            <div><img class="w-[500px] mt-16" src="images/right-coffee.svg" alt=""></div>
          </div>
          <ul class="main-menu flex flex-col md:flex-row items-center space-y-11 md:space-y-0 md:space-x-10">
            <li>
              <div class="flex flex-row justify-center items-center space-x-5">
                <div>
                  <img class="w-[50px] bg-gray-50 border border-gray-300 rounded-full" src="images/club-elite-logo.png" alt="">
                </div>
                <div class="text-[20px]">{{ userData.fullname }}</div>
              </div>
            </li>
            <li>
              <a href="/logout">
                <svg class="w-[30px]" viewBox="0 0 24 24"><path d="M14.08,15.59L16.67,13H7V11H16.67L14.08,8.41L15.5,7L20.5,12L15.5,17L14.08,15.59M19,3A2,2 0 0,1 21,5V9.67L19,7.67V5H5V19H19V16.33L21,14.33V19A2,2 0 0,1 19,21H5C3.89,21 3,20.1 3,19V5C3,3.89 3.89,3 5,3H19Z" /></svg>
              </a>
            </li>
          </ul>
          <button id="close-icon" class="flex md:hidden absolute top-8 right-6">
            <svg class="w-[40px] fill-[#F89C24]" viewBox="0 0 24 24"><path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" /></svg>
          </button>
        </div>
      </div>
    </nav>
    <div class="container flex flex-col mx-auto mt-36 px-5">
      <div class="flex flex-col md:flex-row justify-between items-center">
        <div class="font-action text-4xl md:pb-3.5">Hola, {{ getFirstName() }}!</div>
        <div class="flex flex-row items-center space-x-2 md:space-x-5 text-2xl">
          <div class="flex flex-row items-center justify-end space-x-2">
            <div class="text-sm md:text-2xl text-right">Balance: ${{ formatNumber(userData.balance) }}</div>
            <div>
              <a href="/dashboard">
                <svg class="w-[30px] fill-gray-500" viewBox="0 0 24 24"><path d="M2 12C2 16.97 6.03 21 11 21C13.39 21 15.68 20.06 17.4 18.4L15.9 16.9C14.63 18.25 12.86 19 11 19C4.76 19 1.64 11.46 6.05 7.05C10.46 2.64 18 5.77 18 12H15L19 16H19.1L23 12H20C20 7.03 15.97 3 11 3C6.03 3 2 7.03 2 12Z" /></svg>
              </a>
            </div>
          </div>
          <div class="w-[2px] bg-black h-[60px] opacity-5"></div>
          <div class="flex flex-row items-center space-x-2">
            <div>
              <svg class="w-[30px] fill-[#F89C24]" viewBox="0 0 56 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M45.8081 1.43646L45.7917 1.41719C38.909 -1.33868 29.0595 -0.088765 19.5518 5.28532C5.03734 13.4758 -3.19337 28.4693 1.16809 38.766C5.52955 49.0599 20.8288 50.7585 35.3406 42.5543C46.6093 36.1891 54.0935 25.7327 54.8318 16.5538H54.84C56.5408 4.54473 45.9175 1.46399 45.8081 1.43921V1.43646ZM30.6865 23.2687C16.8611 25.284 9.9785 35.074 9.54099 34.0306C9.03238 32.8413 13.3063 24.6122 27.9603 21.2919C42.2806 18.046 48.2964 11.7001 51.3371 8.36055C51.5286 8.14856 51.6626 7.95309 51.7719 7.76037C52.3872 8.76801 52.8766 9.54164 52.8466 10.9567C49.5324 14.7395 43.7272 21.3718 30.6838 23.2659L30.6865 23.2687Z" fill="#F99C25"/></svg>
            </div>
            <div class="text-sm md:text-2xl"><span class="font-black">{{ formatNumber(userData.points) }}</span> Puntos</div>
          </div>
        </div>
      </div>
      <div class="mt-10" v-if="userData.ref">
        <div class="flex flex-col md:flex-row justify-center md:justify-between items-center bg-[#EF7D15] px-2 md:px-6 py-5 rounded-2xl shadow-2xl shadow-[#FFA039]">
          <div class="flex flex-row justify-between items-center space-x-5 md:mb-0 mb-5">
            <div class="hidden md:flex">
              <svg class="w-[45px]" viewBox="0 0 68 68" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="34" cy="34" r="34" fill="#FFB039"/>
                <path d="M24.4165 39.75H28.2498C28.2498 41.82 30.8757 43.5833 33.9998 43.5833C37.124 43.5833 39.7498 41.82 39.7498 39.75C39.7498 37.6417 37.7565 36.875 33.5398 35.8592C29.4765 34.8433 24.4165 33.5783 24.4165 28.25C24.4165 24.8192 27.234 21.9058 31.1248 20.9283V16.75H36.8748V20.9283C40.7657 21.9058 43.5832 24.8192 43.5832 28.25H39.7498C39.7498 26.18 37.124 24.4167 33.9998 24.4167C30.8757 24.4167 28.2498 26.18 28.2498 28.25C28.2498 30.3583 30.2432 31.125 34.4598 32.1408C38.5232 33.1567 43.5832 34.4217 43.5832 39.75C43.5832 43.1808 40.7657 46.0942 36.8748 47.0717V51.25H31.1248V47.0717C27.234 46.0942 24.4165 43.1808 24.4165 39.75Z" fill="white"/>
              </svg>
            </div>
            <div class="flex flex-col">
              <div class="text-white text-md">Invita a tus amigos y gana <b>$3.00 dólares</b> por cada persona <br> que se registre en Club Elite y haga una compra de $10 en adelante.</div>
              <div class="text-white opacity-90 text-sm mt-2">* Canjea tus cupones de $3.00 por un consumo mínimo de $10.00</div>
            </div>
          </div>
          <div>
            <button @click.prevent="shareRef" class="flex flex-row items-center bg-[#009F9A] p-2 w-[200px] rounded font-action text-white text-2xl space-x-3">
              <span>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M18 16.08C17.24 16.08 16.56 16.38 16.04 16.85L8.91 12.7C8.96 12.47 9 12.24 9 12C9 11.76 8.96 11.53 8.91 11.3L15.96 7.19C16.5 7.69 17.21 8 18 8C18.7956 8 19.5587 7.68393 20.1213 7.12132C20.6839 6.55871 21 5.79565 21 5C21 4.20435 20.6839 3.44129 20.1213 2.87868C19.5587 2.31607 18.7956 2 18 2C17.2044 2 16.4413 2.31607 15.8787 2.87868C15.3161 3.44129 15 4.20435 15 5C15 5.24 15.04 5.47 15.09 5.7L8.04 9.81C7.5 9.31 6.79 9 6 9C5.20435 9 4.44129 9.31607 3.87868 9.87868C3.31607 10.4413 3 11.2044 3 12C3 12.7956 3.31607 13.5587 3.87868 14.1213C4.44129 14.6839 5.20435 15 6 15C6.79 15 7.5 14.69 8.04 14.19L15.16 18.34C15.11 18.55 15.08 18.77 15.08 19C15.08 20.61 16.39 21.91 18 21.91C19.61 21.91 20.92 20.61 20.92 19C20.92 18.2256 20.6124 17.4829 20.0648 16.9352C19.5171 16.3876 18.7744 16.08 18 16.08Z" fill="white"/>
                </svg>
              </span>
              <span>Compartir Enlace</span>
            </button>
          </div>
        </div>
      </div>
      <div class="mt-10">
        <div class="flex flex-col md:flex-row justify-center md:justify-between items-center bg-white border border-gray-300 px-6 py-5 rounded-2xl">
          <div class="space-y-3">
            <div class="text-xl font-bold">Términos y condiciones Club Elite:</div>
            <div>
              <ul class="space-y-3">
                <li class="flex flex-row items-center justify-start space-x-3">
                  <div>
                    <CheckIcon />
                  </div>
                  <div>Por cada dólar consumido recibirás 10 puntos.</div>
                </li>
                <li class="flex flex-row items-center justify-start space-x-3">
                  <div>
                    <CheckIcon />
                  </div>
                  <div>100 puntos equivalen a $1.00 que es la cantidad mínima de puntos para poder canjear.</div>
                </li>
                <li class="flex flex-row items-center justify-start space-x-3">
                  <div>
                    <CheckIcon />
                  </div>
                  <div>Tus puntos se acumulan en base al total de tu cuenta.</div>
                </li>
                <li class="flex flex-row items-center justify-start space-x-3">
                  <div>
                    <CheckIcon />
                  </div>
                  <div>Con tus puntos podrás canjear hasta el 50 % del total del valor de tu factura.</div>
                </li>
                <li class="flex flex-row items-center justify-start space-x-3">
                  <div>
                    <CheckIcon />
                  </div>
                  <div>El canje de puntos no aplica para otras promociones y descuentos solo para descuento de jubilado.</div>
                </li>
                <li class="flex flex-row items-center justify-start space-x-3">
                  <div>
                    <CheckIcon />
                  </div>
                  <div>Tus puntos nunca expiran.</div>
                </li>
              </ul>
              <p class="text-sm text-gray-400 mt-2">* Los puntos no pueden ser utilizados para el pago de promociones</p>
            </div>
          </div>
          <div class="mt-3 md:mt-0">
            <img class="w-[200px] mr-5" src="images/club-elite-logo.png" alt="">
          </div>
        </div>
      </div>
      <div class="flex flex-col justify-center items-center" v-if="fetchGifts">
        <div>
            <svg class="w-[80px] fill-[#F89C24]" id="L9" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                <path d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                  <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" from="0 50 50" to="360 50 50" repeatCount="indefinite"></animateTransform>
              </path>
            </svg>
        </div>
        <div>Cargando Regalos...</div>
      </div>
      <div class="flex mt-3" v-if="!fetchGifts && gifts.length > 0">
        <div class="font-action text-4xl py-3.5">Mis Regalos</div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-4 mb-10 gap-2.5" v-if="!fetchGifts && gifts.length > 0">
        <div class="flex flex-col justify-between bg-white p-5 border border-gray-300 rounded-2xl" v-for="gift in gifts.filter(gift => gift.rStatus === 1)">
          <div class="flex flex-col">
            <div>
              <img class="w-full" :src="gift.rImg" :alt="gift.rTitulo">
            </div>
            <div class="flex flex-col justify-center items-center mt-2">
              <div class="text-black text-xl text-center">{{ gift.rTitulo }}</div>
            </div>
          </div>
        </div>
      </div>
      <div class="flex flex-col justify-center items-center" v-if="fetchStorePoints">
        <div>
            <svg class="w-[80px] fill-[#F89C24]" id="L9" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                <path d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                  <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s" from="0 50 50" to="360 50 50" repeatCount="indefinite"></animateTransform>
              </path>
            </svg>
        </div>
        <div>Cargando Tienda de Puntos...</div>
      </div>
      <div class="flex mt-3" v-if="!fetchStorePoints && products.length > 0">
        <div class="font-action text-4xl py-3.5">Tienda de Puntos</div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-4 mb-10 gap-2.5" v-if="!fetchStorePoints && products.length > 0">
        <div class="flex flex-col justify-between bg-white p-5 border border-gray-300 rounded-2xl" v-for="product in products">
          <div class="flex flex-col">
            <div>
              <img class="w-full" :src="product.rImg" :alt="product.rTitulo">
            </div>
            <div class="flex flex-col justify-center items-center mt-2">
              <div class="text-black text-xl text-center">{{ product.rTitulo }}</div>
              <div class="font-bold text-lg text-[#009F9A] mt-2">{{ Number(product.rCosto / 100) }}<span class="text-gray-400 ml-1.5">pts</span></div>
            </div>
          </div>
          <div class="flex flex-col justify-center items-center mt-2">
            <button :disabled="Number(product.rCosto / 100) > Number(userData.points)" @click.prevent="buyProduct(product.rId)" class="bg-[#009F9A] p-2 w-[200px] rounded font-action text-white text-2xl">Canjear</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
