import App from '@/App.vue'
import { registerPlugins } from '@core/utils/plugins'
import { createApp } from 'vue'

import setup_interceptors from './interceptors/interceptors.js'

// importar componentes globales
import ConfirmDialog from './components/ConfirmDialog.vue'
import Loader from './components/Loader.vue'
import SelectDate from './components/SelectDate.vue'
import VClienteComponent from './components/VClienteComponent.vue'
import VCustomCard from './components/VCustomCard.vue'
import VCustomMenuCalendar from './components/VCustomMenuCalendar.vue'
import VCustomTitle from './components/VCustomTitle.vue'



// Styles
import '@core/scss/template/index.scss'
import '@styles/styles.scss'

// Create vue app
const app = createApp(App)


// Register plugins
registerPlugins(app)


// implementar interceptores
setup_interceptors()


// componentes globales
app.component('loader', Loader)
app.component('v-custom-title', VCustomTitle)
app.component('v-custom-card', VCustomCard)
app.component('v-cliente-component', VClienteComponent)
app.component('v-custom-menu-calendar', VCustomMenuCalendar)
app.component('ConfirmDialog', ConfirmDialog)
app.component('select-date', SelectDate)


// Mount vue app
app.mount('#app')
