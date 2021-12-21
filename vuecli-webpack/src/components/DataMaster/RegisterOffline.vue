<template>
    <v-main class="RegisterOffline">
        <h3 class="text-h4 font-weight-medium mb-1"> Register Offline </h3>

        <v-card>
            <v-card-title>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Search"
                    single-line
                    hide-details>
                </v-text-field>

                <v-btn color="success" dark @click="dialog = true"> Register </v-btn>

            </v-card-title>
            <v-data-table :headers="headers" :items="staffs" :search="search" >
                <template v-slot:[`item.actions`]="{ item }"> 
                    <v-btn small class="mr-2" @click="editHandler(item)" color="primary"> edit </v-btn>
                    <v-btn small @click="deleteHandler(item.id)" color="error"> delete</v-btn>
                </template>
            </v-data-table>
        </v-card>

        <v-dialog v-model="dialog" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">{{ formTitle}} Pendaftaran Offline</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-text-field v-model="nama" label="Nama Lengkap" required></v-text-field>
                        <select v-model="lokasi">
                            <option value="Perpustakaan UAJY Babarsari 3" data-foo="Perpustakaan UAJY Babarsari 3">Perpustakaan UAJY Babarsari 3</option>
                        </select>
                        <div class="FormDate">
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
                <v-card-text>Anda Yakin ingin menghapus registrasi ini?</v-card-text>
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
    name: "Registrasi",
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
                { text: "Nama Lengkap", align: "start", sortable: true, value: "nama"},
                { text: "Lokasi Perpustakaan", value: 'lokasi'},
                { text: "Tanggal Masuk", value: 'tanggal_masuk'},
                { text: "Actions", value: 'actions'},
            ],
            staff: new FormData,
            staffs: [],
            form: {
                nama: null,
                lokasi: null,
                tanggal_masuk: null,
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
        // Read Data 
        readData() {
            var url = this.$api + '/register_offlines';
            this.$http.get(url, {
                headers: {
                    'Authorization' : 'Bearer ' + localStorage.getItem('token')
                }
            }).then(response => {
                this.staffs = response.data.data;
            })
        },
        //simpan data 
        save() {
            this.staff.append('nama', this.form.nama);
            this.staff.append('lokasi', this.form.lokasi);
            this.staff.append('tanggal_masuk', this.form.tanggal_masuk);

            var url = this.$api +'/staff/'
            this.load = true;
            this.$http.post(url, this.staff, {
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
                nama: this.form.nama,
                lokasi: this.form.lokasi,
                tanggal_masuk: this.form.tanggal_masuk

            };
            var url = this.$api + '/register_offlines/' + this.editId;
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
//MET
        // Hapus data produk
        deleteData() {
            //menghapus data
            var url = this.$api + '/register_offlines/' + this.deleteId;
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
            this.form.nama = item.nama;
            this.form.lokasi = item.lokasi;
            this.form.tanggal_masuk = item.tanggal_masuk;
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
                nama: null,
                lokasi: null,
                tanggal_masuk: null
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
