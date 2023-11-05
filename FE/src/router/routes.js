import { createRouter, createWebHistory } from 'vue-router'

import Book from './../components/Book'

import NotFound from './../components/NotFound'

const routes = [

    {path: '',component: Book,name:'Book'},
    {path: '/:NotFound(.*)*',component: NotFound,name:'NotFound'}
    
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes
})

export default router
