<template>
    <div class="dashboard">
        <v-navigation-drawer class="fullheight" width="256" app color="#ffcc99">
            <v-list-item >
                <v-list-item-content>
                    <v-list-item-title class="title" color="#964b00"><v-icon>PERPUS IDEA</v-icon></v-list-item-title>
                </v-list-item-content>
            </v-list-item>

            <v-divider></v-divider>
            <v-list dense nav>
                <v-list-item
                    v-for="item in items"
                    :key="item"
                    link
                    tag="router-link"
                    :to="item.to">
                    <v-list-item-icon>
                        <v-icon>{{ item.icon }}</v-icon>
                        <v-list-item-content>
                        <v-list-item-title>{{ item.title }}</v-list-item-title>
                    </v-list-item-content>
                    </v-list-item-icon>
                    
                </v-list-item>
            </v-list>
        </v-navigation-drawer>
        <v-app-bar app fixed height="75px" color="#eea990">
            <VSpacer />
            <v-toolbar-items>
                <v-btn @click="profil" text router ><v-icon>mdi-account-circle</v-icon></v-btn>
                <v-btn @click="logout" text router><v-icon>mdi-power</v-icon></v-btn>
            </v-toolbar-items>
        </v-app-bar>
        <div class="fullheight pa-5">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
export default {
    name: "Dashboard",
    data() {
        return {
            items: [
                { icon: "mdi-view-dashboard",title: "Dashboard", to: '/dashboard'},
                { icon: "mdi-view-list", title: 'Buku', to: '/buku'},
                { icon: "mdi-view-list",title: 'Peminjaman', to: '/peminjaman'},
                { icon: "mdi-view-list",title: 'Register Offline', to: '/registeroffline'},
                { icon: "mdi-account", title: 'Pegawai Perpustakaan', to: '/staff'},
                { icon: "mdi-account", title: 'User Perpustakaan', to: '/user'},
                { icon: "mdi-email",title: 'About Us', to: '/aboutus'},
                

            ],
        };
    },
    methods: {
        logout() {
            localStorage.removeItem('token')
            this.$router.push("/login")
        },
        
        profil() {
                this.$router.push("/profil")
        },
    }
};
</script>
<style scoped>
    .fullheight {
        min-height: 100vh !important;
    }

    .router{
        text-decoration: none;
        color:black;
        
    }
</style>
