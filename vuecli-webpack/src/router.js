import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

function importComponent(path) {
    return () => import(`./components/${path}.vue`);
}

const router = new VueRouter({
    mode: 'history',
    routes: [
    {
        path: '/',
        component: importComponent('Login'),
    },
    {
        path: '/dashboardlayout',
        name: 'DashboardLayout',
        component: importComponent('DashboardLayout'),
        children: [
            //Dashboard
            {
                path: '/dashboard', 
                name: 'Dashboard',
                meta: { title: 'Dashboard' },
                component: importComponent('Dashboard'),
            },

            // Buku
            {
                path: '/buku',
                name: 'Buku',
                meta: { title: 'Bukus' },
                component: importComponent('DataMaster/Buku'),
            },

             // Staff
             {
                path: '/staff',
                name: 'Staff',
                meta: { title: 'Staffs' },
                component: importComponent('DataMaster/Staff'),
            },

             // User
             {
                path: '/user',
                name: 'User',
                meta: { title: 'Users' },
                component: importComponent('DataMaster/User'),
            },

             // Peminjaman
             {
                path: '/peminjaman',
                name: 'Peminjaman',
                meta: { title: 'Peminjamans' },
                component: importComponent('DataMaster/Peminjaman'),
            },
            
             // RegisterOffline
             {
                path: '/registeroffline',
                name: 'RegisterOffline',
                meta: { title: 'RegisterOfflines' },
                component: importComponent('DataMaster/RegisterOffline'),
            },

             //profil
             {
                path: "/profil",
                name: "Profil",
                meta: {title: 'Profil'},
                component: importComponent('Profil'),
            },

            {
                path: "/aboutus",
                name: "AboutUs",
                meta: {title: 'About Us'},
                component: importComponent('AboutUs'),
            },
        ],
    },

    //login
    {
        path: "/login",
        name: "Login",
        meta: {title: 'Login'},
        component: importComponent('Login'),
    },

    //register
    {
        path: "/register",
        name: "Register",
        meta: {title: 'Register'},
        component: importComponent('Register'),
    },

    
    
    {
        path: '*',
        redirect: '/'
    },
  ],
});

// Set Judul
router.beforeEach((to, from, next) => {
    document.title = to.meta.title;
    next();
});


export default router;
