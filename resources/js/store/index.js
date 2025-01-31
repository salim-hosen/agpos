import { createStore } from 'vuex'
import cart from './modules/cart'
import auth from './modules/auth'

const store = createStore({
    modules: {
        cart,
        auth
    },
    state: {

    },
    mutations: {

    },
    actions: {

    }
})

export default store
