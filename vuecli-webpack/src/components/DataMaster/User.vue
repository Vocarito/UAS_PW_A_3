<template>
    <v-main class="User">
        <h3 class="text-h4 font-weight-medium mb-1"> Daftar User Perpustakaan </h3>

        <v-card>
            <v-card-title>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Search"
                    single-line
                    hide-details>
                </v-text-field>

                <v-spacer></v-spacer>

                <v-btn color="success" dark @click="dialog = true"> Tambah </v-btn>

            </v-card-title>
            <v-data-table :headers="headers" :items="users" :search="search" >
                <template v-slot:[`item.actions`]="{ item }"> 
                    <v-btn small class="mr-2" @click="editHandler(item)" color="primary"> edit </v-btn>
                    <v-btn small @click="deleteHandler(item.id)" color="error"> delete</v-btn>
                </template>
            </v-data-table>
        </v-card>

        <v-dialog v-model="dialog" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">{{ formTitle}} User</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-text-field v-model="form.nama_pengunjung" label="Nama" required></v-text-field>
                        <v-text-field v-model="form.email" label="Email" required></v-text-field>
                        <v-text-field v-model="form.username" label="Username" required></v-text-field>
                        <v-text-field v-model="form.password" label="Password" required></v-text-field>
                         <!-- <v-text-field v-model="form.status" label="Status" required></v-text-field> -->

                         <div class="checkbox-inline">
                            Status:
                        <label class="radio mr-2">
                            <input class="details-input" type="radio" name="status"  value="Pengunjung" v-model="form.status" :checked="form.status == null"/> Pengunjung
                            </label>
                            <label class="radio">
                                <input class="details-input" type="radio" name="status" value="Staff" v-model="form.status"/> Staff
                        </label>
                        </div>    

                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="cancel"> Cancel </v-btn>
                    <v-btn color="blue darken-1" text @click="setForm"> Save </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="dialogConfirm" persistent max-width="400px">
            <v-card>
                <v-card-title>
                    <span class="headline">Warning!</span>
                </v-card-title>
                <v-card-text>Anda Yakin ingin menghapus user ini?</v-card-text>
                <v-card-action>
                    <v-spacer></v-spacer>
                     <v-btn color="blue darken-1" text @click="dialogConfirm = false" > Cancel </v-btn>
                    <v-btn color="blue darken-1" text @click="deleteData"> Delete </v-btn>
                </v-card-action>
            </v-card>
        </v-dialog>

        <v-snackbar v-model="snackbar" :color="color" timeout="2000" bottom>{{ error_message }}</v-snackbar>
    </v-main>
</template>

<script>
export default {
    name: "User",
    data() {
        return {
            inputType: 'Tambah',
            load: false,
            snackbar: false,
            error_message: '',
            color: '',
            search: null,
            dialog: false,
            dialogConfirm: false,
            headers: [
                { text: "Nama", align: "start", sortable: true, value: "nama_pengunjung"},
                { text: "Email", value: 'email'},
                { text: "Username", value: 'username'},
                { text: "Status", value: 'status'},
            
                { text: "Actions", value: 'actions'},
            ],
            user: new FormData,
            users: [],
            form: {
                nama_pengunjung: null,
                email: null,
                username: null,
                password:null,
                status: null,
            },
            deleteId: '',
            editId: ''
        };
    },

    methods: {
        setForm(){
            if(this.inputType !== 'Tambah'){
                this.update();
            }else{
                this.save();
            }
        },
        // Read Data Buku
        readData() {
            var url = this.$api + '/user';
            this.$http.get(url, {
                headers: {
                    'Authorization' : 'Bearer ' + localStorage.getItem('token')
                }
            }).then(response => {
                this.users = response.data.data;
            })
        },
        //simpan data Buku
        save() {
            this.user.append('nama_pengunjung', this.form.nama_pengunjung);
            this.user.append('email', this.form.email);
            this.user.append('username', this.form.username);
            this.user.append('password', this.form.password);
             this.user.append('status', this.form.status);

            var url = this.$api +'/user'
            this.load = true;
            this.$http.post(url, this.user, {
                headers: {
                    'Authorization' : 'Bearer ' + localStorage.getItem('token'),
                }
            }).then(response=> {
                this.error_message = response.data.message;
                this.color = "green";
                this.snackbar= true;
                this.load= true;
                this.close();
                this.readData(); // baca data
                this.resetForm();
            }).catch(error => {
                this.error_message = error.response.data.message;
                this.color= "red";
                this.snackbar= true;
                this.load = false;
            });
        },
        // ubah data buku
        update() {
            let newData = {
                nama_pengunjung : this.form.nama_pengunjung,
                email: this.form.email,
                username: this.form.username,
                password: this.form.password,
                status: this.form.status

            };
            var url = this.$api + '/user/' + this.editId;
            this.load = true;
            this.$http.put(url, newData, {
                headers: {
                    'Authorization' : 'Bearer ' + localStorage.getItem('token')
                }
            }).then(response => {
                this.error_message = response.data.message;
                this.color = 'green';
                this.snackbar = true;
                this.load = false;
                this.close();
                this.readData(); // baca data
                this.resetForm();
                this.inputType = 'Tambah';
            }).catch(error => {
                this.error_message = error.response.data.message;
                this.color= 'red';
                this.snackbar = true;
                this.load = false;

            });
        },
        // Hapus data produk
        deleteData() {
            //menghapus data
            var url = this.$api + '/user/' + this.deleteId;
            this.load = true;
            this.$http.delete(url, {
                headers: {
                    'Authorization' : 'Bearer ' + localStorage.getItem('token')
                }
            }).then(response => {
                this.error_message = response.data.message;
                this.color= "green";
                this.snackbar = true;
                this.load = false;
                this.close();
                this.readData(); // baca data
                this.resetForm();
                this.inputType = "Tambah";
            }).catch(error => {
                this.error_message = error.response.data.message;
                this.color = "red";
                this.snackbar = true;
                this.load = false;
            });
        },
        editHandler(item) {
            this.inputType = 'Ubah';
            this.editId = item.id;
            this.form.nama_pengunjung = item.nama_pengunjung;
            this.form.email = item.email;
            this.form.username = item.username;
            this.form.password = item.password;
            this.form.status = item.status;
            this.dialog = true;
        },
        deleteHandler(id) {
            this.deleteId = id;
            this.dialogConfirm = true;
        },
        close() {
            this.dialog = false;
            this.inputType = 'Tambah';
            this.dialogConfirm = false;
            this.readData();
        },
        cancel() {
            this.resetForm();
            this.readData();
            this.dialog = false;
            this.dialogConfirm  = false;
            this.inputType = 'Tambah';
        },
        resetForm() {
            this.form = { 
                nama_pengunjung: null, 
                email: null,
                username: null,
                password: null,
                status: null
            };
        },
    },
    computed: {
        formTitle() {
            return this.inputType;
        },
    },
    mounted() {
        this.readData();
    },
};
</script>
