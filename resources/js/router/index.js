import { createRouter, createWebHistory } from "vue-router";
import AppLayout from '../layout/AppLayout.vue';


const routes = [
    {
        path: '/',
        component: AppLayout,
        children: [
            {
                path: '/',
                name: 'dashboard',
                component: () => import('../views/Dashboard.vue')
            },
        ]
    },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// router.beforeEach((to, from, next) => {

// });

export default router;
