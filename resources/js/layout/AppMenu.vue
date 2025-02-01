<script setup>
import { ref } from 'vue';

import AppMenuItem from './AppMenuItem.vue';

const model = ref([
    {
        label: 'Menu',
        items: [
            { label: 'Products', icon: 'pi pi-fw pi-box', to: '/' },
            { label: 'Suppliers', icon: 'pi pi-fw pi-truck', to: '/suppliers' },
            { label: 'Purchase Orders', icon: 'pi pi-fw pi-file-check', to: '/purchase-orders' }
        ]
    },
]);
</script>

<template>
    <ul class="layout-menu">
        <template v-for="(item, i) in model" :key="item">
            <app-menu-item v-if="!item.separator" :item="item" :index="i"></app-menu-item>
            <li v-if="item.separator" class="menu-separator"></li>
        </template>
    </ul>
</template>

<style lang="scss" scoped></style>
