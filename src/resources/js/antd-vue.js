import { createApp } from 'vue'
import Antd from 'ant-design-vue';
import 'ant-design-vue/dist/antd.css';
import Results from './components/Results'
import Tree from './components/Tree'
// import FullCalendar from './components/FullCalendar'
// import Draggable from './components/Draggable'

const app = createApp({}).use(Antd)

app.component('antd-results', Results)
app.component('antd-tree', Tree)
// app.component('draggable-calendar', Draggable)
app.mount('#app')

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */