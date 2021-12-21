<template>
  <main>
        <v-container fluid fill-height class="posisinya">
            <v-layout flex align-center justify-center class="pa-5">
                <v-flex xs12 sm4 elevation-6>
                    <v-card class="pa-9">
                      <div class="text-center">
                        <v-avatar size="100" color="#ffcc99" >
                          <v-icon size="40" color="brown">mdi-account</v-icon>
                        </v-avatar>
                        <h2 class="brown--text">REGISTER</h2>
                      </div>
                    </v-card>

                    <v-card>
                        <v-card-text class="pt-10">
                            <div>
                                <v-form v-model="valid" ref="form">
                                    <v-text-field label="Nama" v-model="nama_pengunjung" :rules="nama_pengunjungRules" required></v-text-field>
                                    <v-text-field label="E-mail" v-model="email" :rules="emailRules" required></v-text-field>
                                      <v-text-field label="Username" v-model="username" :rules="usernameRules" required></v-text-field>
                                     <v-text-field :append-icon="passwordShow ?'mdi-eye': 'mdi-eye-off'" @click:append="passwordShow = !passwordShow"
                                     label="Password" v-model="password" :type="passwordShow?'text': 'password'" min="8" :rules="passwordRules" counter required></v-text-field>
                                       <!-- <v-text-field label="Status" v-model="status" :rules="statusRules" required></v-text-field> -->

                            <div class="checkbox-inline">
                                Status:
                            <label class="radio mr-2">
                                <input class="details-input" type="radio" name="status"  value="Pengunjung" v-model="status" :checked="status == null"/> Pengunjung
                                </label>
                                <label class="radio">
                                    <input class="details-input" type="radio" name="status" value="Staff" v-model="status"/> Staff
                            </label>
                            </div>
                                     <v-layout justify-end>
                                         <v-btn class="mr-2" @click="submit" :class="{ 'brown darken-1 white--text' : valid,disabled: !valid }">Submit</v-btn>
                                         <v-btn @click="clear" class="grey darken-3 white--text">Clear</v-btn>
                                     </v-layout>
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
    .grey--text{
        font-family: "Jolly Lodger";
    }

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
    name: "Register",
    data() {
      return {
        load: false,
        snackbar: false,
        error_message: '',
        color: '',
        valid: false,
        nama_pengunjung: '',
        nama_pengunjungRules: [
           (v) => !!v || 'Nama tidak boleh kosong!',
        ],
        email:'',
        emailRules: [
          (v) => !!v || 'E-mail tidak boleh kosong!'
        ],
        username:'',
        usernameRules: [
          (v) => !!v || 'Username tidak boleh kosong!'
        ],
        passwordShow:false,
        password: '',
        passwordRules: [
          (v) => !!v || 'Password tidak boleh kosong!',
        ],
        status: '',
        // statusRules: [
        //   (v) => !!v || 'Status tidak boleh kosong!'
        // ]
      };
    },
    methods: {
      submit(){
        if(this.$refs.form.validate()) {
          //cek validasi data yang terkirim
          this.load = true;
          this.$http.post(this.$api + '/register', {
            nama_pengunjung: this.nama_pengunjung,
            email: this.email,
            username:this.username,
            password: this.password,
            status: this.status,
          }).then(response => {
            //simpan data id user yang diinput
            //localStorage.setItem('id', response.data.user.id);
            //localStorage.setItem('token', response.data.access_token);
            this.error_message = response.data.message;
            this.color= "green";
            this.snackbar= true;
            this.load= false;
            this.clear();
            this.$router.push({
              name: 'Login',
            });
          }).catch(error => {
            this.error_message = error.response.data.message;
            this.color = "red";
            this.snackbar = true;
            //localStorage.removeItem('token');
            this.load= false;
          })
        }
      },

      clear() {
        this.$refs.form.reset() //clear form login
      }
    },
  };
</script>

