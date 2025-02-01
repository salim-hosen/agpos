import { createRouter, createWebHistory } from "vue-router";
import AppLayout from '../layout/AppLayout.vue';


const routes = [
    {
        path: '/',
        component: AppLayout,
        children: [
            {
                path: '/',
                name: 'products',
                component: () => import('../views/Products.vue')
            },
            {
                path: '/suppliers',
                name: 'suppliers',
                component: () => import('../views/Suppliers.vue')
            },
            {
                path: '/purchase-orders',
                name: 'purchase-orders',
                component: () => import('../views/PurchaseOrders.vue')
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
