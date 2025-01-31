

const auth = {
    namespaced: true,
    state: () => ({
        user: null,
        authenticated: false,
    }),
    mutations: {
        SET_USER_DATA (state, payload) {
            state.user = payload;
            state.authenticated = !!payload;
        }
    },
    actions: {
        async fetchUserDetails({commit, dispatch}) {
            try{
                const res = await axios.get(`/api/v1/user-details`);
                // console.log(res.data, 'fromstore');
                if (res.data.data){
                    commit("SET_USER_DATA", res.data.data);
                }
            }catch(err){
                console.log(err);
            }
        },
    },
    getters: {

    }
}

export default auth
