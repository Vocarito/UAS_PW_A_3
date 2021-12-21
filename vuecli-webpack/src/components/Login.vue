<template>
  <main>
        <v-container fluid fill-height class="posisinya">
            <v-layout flex align-center justify-center class="pa-10">
                <v-flex xs12 sm4 elevation-6>
                    <v-card class="pa-9">
                      <div class="text-center">
                        <v-avatar size="100" color="#ffcc99" >
                          <v-icon size="40" color="brown">mdi-account</v-icon>
                        </v-avatar>
                        <h2 class="brown--text">Welcome to PERPUS IDEA</h2>
                      </div>
                    </v-card>

                    <v-card>
                        <v-card-text class="pt-10">
                            <div>
                                <v-form v-model="valid" ref="form">
                                    <v-text-field prepend-inner-icon="mdi-account" label="Username" v-model="username" :rules="usernameRules" required></v-text-field>
                                     <v-text-field prepend-inner-icon="mdi-key" :append-icon="passwordShow ?'mdi-eye': 'mdi-eye-off'" @click:append="passwordShow = !passwordShow"
                                     label="Password" v-model="password" :type="passwordShow?'text': 'password'" min="8" :rules="passwordRules" counter required></v-text-field>
                                     <v-layout py-9 justify-end>
                                         <v-btn class="mr-2" @click="submit" :class="{ ' brown darken-1 white--text' : valid,disabled: !valid }">Login</v-btn>
                                         <v-btn @click="clear" class="grey darken-3 white--text">Clear</v-btn>
                                     </v-layout>
                                         <v-btn text color="primary" @click="regis" >Create Account</v-btn>
                                </v-form>
                            </div>
                        </v-card-text>
                    </v-card>
                    <v-snackbar v-model="snackbar" :color="color" timeout="2000" bottom>{{error_message}}</v-snackbar>
                </v-flex>
            </v-layout>
        </v-container>
    </main>
</template>

<style>
    @import url("https://fonts.googleapis.com/css?family=Jolly%20Lodger");

    .posisinya{
        position:fixed;
        top: 20px;
        left: 0;
        right: 0;
        background-image:url(https://cdn.edu.buncee.com/rackspace/bnc-assets/backgrounds/58b/e18/1488576911_BgLibrary030317_01.jpg);
        
    }

    

</style>

<script>
  export default{
    name: "Login",
    data() {
      return {
        load: false,
        snackbar: false,
        error_message: '',
        color: '',
        valid: false,
        passwordShow:false,
        password: '',
        passwordRules: [
          (v) => !!v || 'Password Invalid!',
        ],
        username:'',
        usernameRules: [
          (v) => !!v || 'Username Invalid!'
        ]
      };
    },
    methods: {
      submit(){
        if(this.$refs.form.validate()) {
          //cek validasi data yang terkirim
          this.load = true;
          this.$http.post(this.$api + '/login', {
            username: this.username,
            password: this.password
          }).then(response => {
            //simpan data id user yang diinput
            localStorage.setItem('id', response.data.user.id);
            localStorage.setItem('token', response.data.access_token);
            this.error_message = response.data.message;
            this.color= "green";
            this.snackbar= true;
            this.load= false;
            this.clear();
            this.$router.push({
              name: 'Dashboard',
            });
          }).catch(error => {
            this.error_message = error.response.data.message;
            this.color = "red";
            this.snackbar = true;
            localStorage.removeItem('token');
            this.load= false;
          })
        }
      },

      clear() {
        this.$refs.form.reset() //clear form login
      },

      regis() {
          this.load = true;
          this.$router.push({
              name: 'Register',
            });
      }
    },
  };
</script>

