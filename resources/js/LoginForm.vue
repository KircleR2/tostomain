<script setup>
import {onMounted, reactive, ref, defineProps} from 'vue'

const fetchLogin = ref(false)
const loginForm = ref(null)
const errorMessage = ref(null)
const errorClose = ref(null)
const errorText = ref(null)

const loginInitialState = {
  email: '',
  password: ''
}
const loginData = reactive({ ...loginInitialState })

onMounted(() => {
  fetchLogin.value = false
  // Fetch CSRF token on component mount
  fetchCsrfToken()
})

// Function to fetch CSRF token
function fetchCsrfToken() {
  axios.get('/sanctum/csrf-cookie')
    .catch(error => {
      console.error('Error fetching CSRF token:', error)
    })
}

function resetForm() {
  Object.assign(loginData, loginInitialState)
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
function login () {
  if (!loginForm.value.checkValidity()) {
    return
  }

  fetchLogin.value = true
  
  // First get CSRF token, then submit form
  axios.get('/sanctum/csrf-cookie')
    .then(() => {
      setTimeout(() => {
        axios.post('/api/login', loginData, {
          withCredentials: true // Ensure cookies are sent with the request
        })
        .then(response => {
          fetchLogin.value = false
          if (response.data.code === 0) {
            window.location.href = '/dashboard'
          } else {
            showError(response.data?.message || 'Error al iniciar sesión. Intente nuevamente.')
          }
        })
        .catch(err => {
          fetchLogin.value = false
          showError(err?.response?.data?.message || 'Error al iniciar sesión. Intente nuevamente.')
        })
      }, 1500)
    })
    .catch(error => {
      fetchLogin.value = false
      showError('Error de conexión. Intente nuevamente.')
      console.error('CSRF token error:', error)
    })
}

</script>

<template>
  <div>
    <div class="md:absolute flex flex-row justify-center md:justify-end items-end w-full pt-12 md:pr-20 z-10">
      <a href="/"><img class="w-[150px]" src="images/logo-white.svg" alt="Logo White"></a>
    </div>
    <div class="flex flex-col md:flex-row items-center justify-between">
      <div class="flex flex-col w-full md:w-4/12 justify-center px-10 md:pl-20 mb-10">
        <div class="flex flex-col mt-10 md:mt-3 text-center">
          <div><h4 class="text-white text-5xl font-action">Club Elite</h4></div>
          <div><p class="font-write text-4xl text-[#F89C24]">¡Acumula puntos y canjea!</p></div>
        </div>
        <form ref="loginForm" action="#" method="post" @submit.prevent="login()" class="flex flex-col mt-10 space-y-5">
          <div ref="errorMessage" class="bg-red-600 rounded flex flex-row p-5 hidden">
            <span ref="errorClose">
              <svg class="w-[20px] fill-white" viewBox="0 0 24 24"><path d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z" /></svg>
            </span>
            <span ref="errorText" class="text-white ml-2">Lo sentimos ha ocurrido un error</span>
          </div>
          <div class="flex flex-col space-y-3 relative">
            <label for="email" class="text-xl text-white">Email</label>
            <div class="position absolute top-0 md:-top-5 -right-2 rotate-6">
              <svg class="w-[50px] md:w-[80px] drop-shadow-md" viewBox="0 0 93 60" fill="none">
                <path d="M90.4341 28.3784C89.3077 28.3784 88.3944 29.4356 88.3944 30.7393C88.3944 31.463 88.676 32.1095 89.1192 32.5427C87.0453 35.3463 84.7294 38.2519 82.1533 41.166C78.8566 44.8949 75.6755 48.0299 72.7322 50.6577C71.7568 44.8092 70.8552 38.5167 70.0897 31.8082C69.6015 27.5314 69.2053 23.3929 68.8841 19.4056C70.0051 19.3981 70.912 18.3452 70.912 17.0458C70.912 15.7463 69.9987 14.6849 68.8723 14.6849C67.7459 14.6849 66.8326 15.7421 66.8326 17.0458C66.8326 17.7759 67.1196 18.4278 67.5693 18.8609C65.9568 22.3572 64.127 26.024 62.0466 29.784C59.8838 33.6931 57.737 37.1894 55.677 40.2954C54.1149 34.7374 52.5741 28.8287 51.0955 22.577C49.9081 17.5583 48.2185 9.45281 47.3213 5.11703C48.1468 4.81468 48.7442 3.92158 48.7442 2.86658C48.7442 1.56284 47.8309 0.506775 46.7045 0.506775C45.5782 0.506775 44.6649 1.56392 44.6649 2.86658C44.6649 3.92158 45.2634 4.81468 46.0878 5.11703C45.1906 9.45281 43.501 17.5572 42.3136 22.577C40.8339 28.8287 39.2932 34.7384 37.7321 40.2954C35.6721 37.1894 33.5253 33.6931 31.3625 29.784C29.3367 26.1215 27.5476 22.547 25.9662 19.1333C26.6139 18.7376 27.0561 17.9518 27.0561 17.0458C27.0561 15.7421 26.1428 14.6849 25.0165 14.6849C23.8901 14.6849 22.9768 15.7421 22.9768 17.0458C22.9768 18.1555 23.6395 19.0839 24.5314 19.3359C24.2092 23.3447 23.8119 27.5057 23.3205 31.8072C22.5549 38.5146 21.6534 44.8081 20.678 50.6567C17.7346 48.0288 14.5536 44.8939 11.2569 41.1649C8.68508 38.2562 6.37451 35.3571 4.30271 32.5587C4.73848 32.1256 5.01579 31.4855 5.01579 30.7683C5.01579 29.4645 4.10249 28.4074 2.97611 28.4074C1.84974 28.4074 0.9375 29.4667 0.9375 30.7693C0.9375 32.072 1.85081 33.1302 2.97718 33.1302C3.13779 33.1302 3.29411 33.1066 3.44401 33.0659C4.186 36.8463 4.89266 40.8979 5.52545 45.2134C6.25888 50.2149 6.80386 54.956 7.20752 59.3818H86.2037C86.6074 54.956 87.1524 50.2149 87.8858 45.2134C88.5196 40.8872 89.2284 36.8259 89.9736 33.0369C90.1225 33.0766 90.2756 33.1002 90.4351 33.1002C91.5615 33.1002 92.4748 32.043 92.4748 30.7404C92.4748 29.4377 91.5615 28.3795 90.4351 28.3795L90.4341 28.3784Z" fill="#FFA039"/>
              </svg>
            </div>
            <input
              id="email"
              type="email"
              v-model="loginData.email"
              required
              placeholder="Ingrese su email"
              class="text-md px-5 py-3 rounded-md border border-gray-300 bg-gray-50 text-gray-700 outline-0 placeholder-gray-300"
            />
          </div>
          <div class="flex flex-col space-y-3">
            <label for="password" class="text-xl text-white">Contraseña</label>
            <input
              id="password"
              type="password"
              v-model="loginData.password"
              required
              placeholder="Ingrese su contraseña"
              class="text-md px-5 py-3 rounded-md border border-gray-300 bg-gray-50 text-gray-700 outline-0 placeholder-gray-300"
            />
          </div>
          <div class="text-right text-white">
            <a class="text-[#513628]" href="/recuperar">Recuperar Contraseña</a>
          </div>
          <div>
            <button type="submit" class="mt-10 w-full">
              <div class="relative">
                <div class="flex flex-row h-full items-center px-6 justify-between z-10 absolute w-full" v-show="!fetchLogin">
                  <div class="font-action text-white text-3xl">Iniciar Sesión</div>
                  <div>
                    <svg class="w-[26px]" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M0 11.3586L0 14.6414H19.697L10.6692 23.6692L13 26L26 13L13 0L10.6692 2.33081L19.697 11.3586H0Z" fill="white"/>
                    </svg>
                  </div>
                </div>
                <div class="flex flex-col items-center h-full px-6 justify-between z-10 top-3 absolute w-full" v-show="fetchLogin">
                  <div>
                    <img style="width: 25px;" src="/images/loading.svg" alt="Loading Image">
                  </div>
                </div>
                <div>
                  <svg class="w-full drop-shadow-md" viewBox="0 0 512 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#F89C24" d="M5.60376 1.83891C4.90725 2.26856 5.35048 2.74977 6.61687 3.9528C5.5721 4.84647 8.57977 5.32768 8.54811 5.4308C8.99134 5.37924 7.50334 6.10105 9.37126 7.25252C10.6693 7.33845 12.4739 8.06027 10.9542 8.90238C10.9542 10.4663 9.90947 10.7413 10.9542 11.2225C9.52956 11.7037 10.5427 11.2225 10.3844 11.824C9.59288 11.9443 8.8647 13.0099 9.97279 13.3536C7.66164 13.7317 9.08632 13.4911 9.05466 14.0066C9.05466 14.2988 10.3844 14.3504 8.57977 14.8659C8.67475 15.2784 8.23151 15.7081 7.97823 15.9487C7.97823 15.9143 8.26317 15.9315 6.26861 15.983C6.33193 16.4127 3.83082 17.0314 3.51422 17.2892C5.22384 17.2204 2.62775 17.7188 2.46945 17.8391C0.854811 18.0454 3.32426 18.1657 2.81771 18.3719C3.89414 19.3515 1.488 19.4031 0 20.0218C0.854811 20.2624 -0.284937 20.8123 0.379916 21.0357C1.55132 21.4482 1.55132 21.6888 1.26639 22.2216C2.46945 22.72 0.316597 23.2528 1.29805 23.7511C1.01311 24.0433 2.75439 24.3355 1.89958 24.662C0.696513 24.9026 0.474895 24.8167 1.55132 24.9885C-0.379916 25.3323 1.20307 25.401 1.23473 25.8822C2.31116 26.2259 0.601534 26.7759 1.23473 27.1024C0.696513 27.3602 0.0316598 27.5836 2.05788 27.7727C1.8046 28.0992 2.43779 28.357 3.38758 28.8726C3.54588 29.3022 2.46945 29.835 3.4509 30.2819C2.46945 30.7459 4.59065 30.7803 3.7675 31.1412C2.81771 31.4333 4.74895 31.7599 4.36903 32.0005C3.13431 32.2926 5.85704 32.2411 5.38214 32.6192C4.14742 32.7395 5.79372 33.2035 5.35048 33.2722C5.8887 33.5644 6.55355 33.8737 5.35048 34.1659C6.36359 34.5784 5.16052 34.5784 4.74895 34.9049C4.17907 35.5064 5.79372 36.5204 6.14197 37.1047C6.71185 37.6203 7.34504 38.1531 6.55355 38.6515C7.31338 39.3045 6.55355 39.992 6.39525 40.6279C6.23695 41.2294 4.59065 41.9168 6.33193 42.5355C7.09176 43.1886 5.47712 43.687 5.38214 44.1682C5.03389 44.6494 4.0841 45.1478 4.43235 45.7837C5.0972 46.4024 4.24239 46.9695 4.78061 47.5882C6.04699 48.3788 6.33193 49.1522 4.78061 49.9427C5.79372 50.5442 4.46401 51.1629 4.78061 51.7473C7.02844 52.3316 5.79372 52.8987 5.7304 53.4143C7.72496 53.9127 6.74351 54.0674 6.36359 54.7376C7.09176 55.2876 6.87015 55.8032 6.68019 56.3359C6.80683 56.8687 5.79372 57.5733 6.68019 58.0889C4.78061 58.6389 5.2555 58.6732 5.69874 59.1544C6.33193 59.6357 5.82538 60.3575 5.69874 60.8559C7.94657 61.698 4.68563 62.4714 5.38214 63.1932C6.11031 63.8806 2.56443 65.0149 5.69874 65.5133C8.95968 66.321 2.72273 66.4413 6.01534 67.2663C5.76206 67.9881 6.36359 67.5756 5.82538 67.9365C3.70418 68.5209 4.81227 69.1567 5.06555 69.2427C3.86248 69.7239 4.71729 69.9301 4.43235 70.5316C6.93347 69.8957 2.05788 72.9205 4.43235 72.3362C4.05244 73.058 6.11031 73.5736 4.11576 74.261C4.02078 74.6391 3.7675 74.8625 3.54588 75H6.87015C7.25006 74.9656 7.62998 74.9656 7.94657 75H507.979C507.853 74.7766 507.694 74.5532 507.504 74.3126C507.568 73.7111 505.636 73.9688 506.396 73.1611C507.093 72.7314 506.65 72.2502 505.383 71.0472C506.428 70.1535 503.42 69.6723 503.452 69.5692C503.009 69.6208 504.497 68.8989 502.629 67.7475C501.331 67.6615 499.526 66.9397 501.046 66.0976C501.046 64.5337 502.091 64.2587 501.046 63.7775C502.47 63.2963 501.457 63.7775 501.616 63.176C502.407 63.0557 503.135 61.9901 502.027 61.6464C504.338 61.2683 502.914 61.5089 502.945 60.9934C502.945 60.7012 501.616 60.6496 503.42 60.1341C503.325 59.7216 503.769 59.2919 504.022 59.0513C504.022 59.0857 503.737 59.0685 505.731 59.017C505.668 58.5873 508.169 57.9686 508.486 57.7108C506.776 57.7796 509.372 57.2812 509.531 57.1609C511.145 56.9546 508.676 56.8343 509.182 56.6281C508.106 55.6485 510.512 55.5969 512 54.9782C511.145 54.7376 512.285 54.1877 511.62 53.9643C510.449 53.5518 510.449 53.3112 510.734 52.7784C509.531 52.28 511.683 51.7472 510.67 51.2489C510.955 50.9567 509.214 50.6645 510.069 50.338C511.272 50.0974 511.493 50.1833 510.417 50.0115C512.348 49.6677 510.765 49.599 510.734 49.1178C509.657 48.7741 511.367 48.2241 510.734 47.8976C511.272 47.6398 511.937 47.4164 509.91 47.2273C510.164 46.9008 509.531 46.643 508.581 46.1274C508.422 45.6978 509.499 45.165 508.517 44.7182C509.499 44.2541 507.378 44.2198 508.201 43.8588C509.151 43.5667 507.219 43.2402 507.599 42.9995C508.834 42.7074 506.111 42.7589 506.586 42.3808C507.821 42.2605 506.175 41.7965 506.618 41.7278C506.08 41.4356 505.415 41.1263 506.618 40.8341C505.605 40.4216 506.808 40.4216 507.219 40.0951C507.789 39.4936 506.175 38.4796 505.826 37.8953C505.257 37.3797 504.623 36.8469 505.415 36.3485C504.655 35.6955 505.415 35.008 505.573 34.3721C505.731 33.7706 507.378 33.0832 505.636 32.4645C504.877 31.8114 506.491 31.313 506.586 30.8318C506.934 30.3506 507.884 29.8522 507.536 29.2163C506.871 28.5976 507.726 28.0305 507.188 27.4118C505.921 26.6212 505.636 25.8478 507.188 25.0573C506.175 24.4558 507.504 23.8371 507.188 23.2528C504.94 22.6684 506.175 22.1013 506.238 21.5857C504.243 21.0873 505.225 20.9326 505.605 20.2624C504.877 19.7124 505.098 19.1968 505.288 18.6641C505.162 18.1313 506.175 17.4267 505.288 16.9111C507.188 16.3611 506.713 16.3268 506.27 15.8456C505.636 15.3643 506.143 14.6425 506.27 14.1441C504.022 13.302 507.283 12.5286 506.586 11.8068C505.858 11.1194 509.404 9.98511 506.27 9.48671C503.009 8.67896 509.246 8.55866 505.953 7.73373C506.206 7.0291 505.605 7.42438 506.143 7.06347C508.264 6.47915 507.156 5.84326 506.903 5.75733C508.106 5.27612 507.251 5.06989 507.536 4.46838C505.035 5.10426 509.91 2.07951 507.536 2.66384C507.916 1.94203 505.858 1.42644 507.853 0.739001C507.948 0.360907 508.201 0.137489 508.422 0L505.098 0C504.718 0.0343721 504.338 0.0343721 504.022 0L4.02078 0C4.14742 0.223419 4.30571 0.446838 4.49567 0.687443C4.43235 1.28896 6.36359 1.03116 5.60376 1.83891Z" />
                  </svg>
                </div>
              </div>
            </button>
          </div>
          <div class="text-center text-white">
            Aún no tienes una cuenta?, <a class="text-[#513628]" href="/registro-club-elite">Regístrate Aquí!</a>
          </div>
        </form>
      </div>
      <div class="w-7/12 relative h-screen hidden md:flex flex-row justify-center items-center space-x-2">
        <div><img class="rounded-2xl rotate-3" src="images/login-1.png" alt=""></div>
        <div><img class="rounded-2xl -rotate-3" src="images/login-2.png" alt=""></div>
      </div>
    </div>
  </div>
</template>
