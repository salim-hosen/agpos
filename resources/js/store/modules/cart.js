

const cart = {
    namespaced: true,
    state: () => ({
        cart_data: {
            cart_items: []
        }
    }),
    mutations: {
        SET_CART_ITEMS (state, payload) {
            state.cart_data = payload;
        }
    },
    actions: {
        async fetchCartData({commit, dispatch}){
            
            let company_id  = localStorage.getItem('company_id');
            let temp_user_id = localStorage.getItem('temp_id');

            const res = await axios.get(`/api/v1/get-cart?company_id=${company_id}&temp_user_id=${temp_user_id}`);
            // state.cart_data = res.data.data;
            commit("SET_CART_ITEMS", res.data);
        },
    },
    getters: { 
        
    }
}

export default cart