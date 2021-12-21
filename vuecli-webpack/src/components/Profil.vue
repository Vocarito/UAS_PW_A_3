<template>
    <v-main class="list" hx="3">

        
         
        <div >
            <h2 class="text-h3 font-weight-medium mb-5">Profil</h2>
            <img :src="imgPrev" width="200px">
            <input type="file" @change="imgSelect" name=image class="align-content-center">
        </div>
        
        <v-btn color="blue darken-1" text @click="imgUpload">
            Upload Image
        </v-btn>
        
        <v-card>
            <v-row>
                <v-col>
                    <img :src="'http://127.0.0.1:8000/user_profile/' + user.image" width="200px">
                </v-col>
            
                <v-col cols="8">
                    <v-card-text>
                        <v-container>
                            <v-text-field
                                v-model="user.nama_pengunjung"
                                label="Nama">
                            </v-text-field>
                                
                            <v-text-field
                                v-model="user.email"
                                label="Email">
                            </v-text-field>
                        </v-container>
                    </v-card-text>
                </v-col>
            </v-row>
            <v-card-actions>
                <v-btn color="red darken-1" text @click="dialogPass = true">
                    Change Password and Profil
                </v-btn>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" text @click="save">
                    Save
                </v-btn>
            </v-card-actions>
        </v-card>

        <v-dialog v-model="dialogPass" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">Change Password and Profil</span>
                </v-card-title>
                    
                <v-card-text>
                    <v-container>
                        <v-text-field
                            v-model="user.nama_pengunjung"
                            label="Nama">
                        </v-text-field>
                            
                        <v-text-field
                            v-model="user.email"
                            label="Email">
                        </v-text-field>

                        <v-text-field
                            v-model="user.oldPass"
                            label="Old Password">
                        </v-text-field>
                            
                        <v-text-field
                            v-model="user.newPass"
                            label="New Password">
                        </v-text-field>

                        <v-text-field
                            v-model="user.confirmPass"
                            label="Confirm New Password">
                        </v-text-field>
                    </v-container>
                </v-card-text>
                
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="cancel">
                        Cancel
                    </v-btn>
                    <v-btn color="blue darken-1" text @click="saveData">
                        Save
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        
        <v-snackbar v-model="snackbar" :color="color" timeout="2000" bottom>
            {{error_message}}
        </v-snackbar>
    </v-main>
</template>

<script>
export default {
    name: "List",
    data() {
        return {
            load: false,
            snackbar: false,
            error_message: '',
            color: '',
            dialogPass: false,
            user: new FormData,
            users: [],
            form: 
            {
                nama_pengunjung: null,
                email: null,
                oldPass: null,
                newPass: null,
                confirmPass: null,
            },
            deleteId: '',
            editId: '',
            images: new FormData,
            image: null,
            imgPrev: null,
        };
    },
    methods: {
        //read data user
        readData() {
            var url = this.$api + '/show/' + localStorage.getItem('id')
            this.$http.get(url, {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                    }
            }).then(response => {
                this.user = response.data.data
            })
        },
        
        //ubah data user
        save() {
            let newData = {
                nama_pengunjung: this.user.nama_pengunjung,
                email: this.user.email,
            }
            var url = this.$api + '/update/' + localStorage.getItem('id');
            this.load = true
            this.$http.put(url, newData, {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            }).then(response => {
                this.error_message=response.data.message;
                this.color="green"
                this.snackbar=true;
                this.load = false;
                this.resetForm();
            }).catch(error => {
                this.error_message=error.response.data.message;
                this.color="red"
                this.snackbar=true;
                this.load = false;
            })
        },

        //ubah data user dan password
        saveData(){
            let newData = {
                nama_pengunjung: this.user.nama_pengunjung,
                email: this.user.email,
                oldPass: this.user.oldPass,
                newPass: this.user.newPass,
                confirmPass: this.user.confirmPass,
            }
            var url = this.$api + '/change-password/'+ localStorage.getItem('id');
            this.load = true
            
            this.$http.put(url, newData, {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            }).then(response => {
                this.error_message=response.data.message;
                this.color="green"
                this.snackbar=true;
                this.load = false;
                this.resetForm();
                this.readData();
                this.dialogPass = false;
            }).catch(error => {
                this.error_message=error.response.data.message;
                this.color="red"
                this.snackbar=true;
                this.load = false;
            })
        },
        
        cancel(){
            this.resetForm();
            this.dialogPass = false;
            this.readData();
        },

        resetForm() {
            this.form = {
                nama_pengunjung: null,
                email: null,
                oldPass: null,
                newPass: null,
                confirmPass: null,
            };
        },

        imgSelect(e){
            this.image = e.target.files[0];

            let reader = new FileReader();
            reader.readAsDataURL(this.image);
            reader.onload = e =>{
                this.imgPrev = e.target.result;
            };
        },

        imgUpload(){
            let data = new FormData;
            data.append('image', this.image);

            var url = this.$api + '/userprofile/'+ localStorage.getItem('id');
            this.load = true
            this.$http.post(url, data, {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            }).then(response => {
                this.error_message=response.data.message;
                this.color="green"
                this.snackbar=true;
                this.load = false;
                window.location.reload();
            }).catch(error => {
                this.error_message=error.response.data.message;
                this.color="red"
                this.snackbar=true;
                this.load = false;
            })
        }
    },
    
    computed: {
        formTitle() {
            return this.inputType
        },
    },
    
    mounted() {
        this.readData();
    },
};
</script>