<template>
    <v-main class="Pegawai">
        <h3 class="text-h4 font-weight-medium mb-1"> Daftar Pegawai Perpustakaan </h3>

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
                    <span class="headline">{{ formTitle}} Pegawai</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-text-field v-model="form.nomor_pegawai" label="Nomor Pegawai" required></v-text-field>
                        <v-text-field v-model="form.nama_pegawai" label="Nama Pegawai" required></v-text-field>
                        <!-- <v-text-field v-model="form.status" label="Status" required></v-text-field> -->
                        <v-text-field v-model="form.role" label="Role" required></v-text-field>    

                        <div class="checkbox-inline">
                            Status:
                        <label class="radio mr-2">
                            <input class="details-input" type="radio" name="status"  value="Aktif" v-model="form.status" :checked="form.status == null"/> Aktif
                            </label>
                            <label class="radio">
                                <input class="details-input" type="radio" name="status" value="Tidak Aktif" v-model="form.status"/> Tidak Aktif
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
                <v-card-text>Anda Yakin ingin menghapus pegawai ini?</v-card-text>
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
    name: "Pegawai",
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
                { text: "Nomor Pegawai", align: "start", sortable: true, value: "nomor_pegawai"},
                { text: "Nama Pegawai", value: 'nama_pegawai'},
                { text: "Role", value: 'role'},
                { text: "Status", value: 'status'},
                { text: "Actions", value: 'actions'},
            ],
            staff: new FormData,
            staffs: [],
            form: {
                nomor_pegawai: null,
                nama_pegawai: null,
                role: null,
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
            var url = this.$api + '/staff';
            this.$http.get(url, {
                headers: {
                    'Authorization' : 'Bearer ' + localStorage.getItem('token')
                }
            }).then(response => {
                this.staffs = response.data.data;
            })
        },
        //simpan data Buku
        save() {
            this.staff.append('nomor_pegawai', this.form.nomor_pegawai);
            this.staff.append('nama_pegawai', this.form.nama_pegawai);
            this.staff.append('role', this.form.role);
            this.staff.append('status', this.form.status);

            var url = this.$api +'/staff'
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
                nomor_pegawai : this.form.nomor_pegawai,
                nama_pegawai: this.form.nama_pegawai,
                role: this.form.role,
                status: this.form.status

            };
            var url = this.$api + '/staff/' + this.editId;
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
            var url = this.$api + '/staff/' + this.deleteId;
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
            this.form.nomor_pegawai = item.nomor_pegawai;
            this.form.nama_pegawai = item.nama_pegawai;
            this.form.role = item.role;
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
                nomor_pegawai: null, 
                nama_pegawai: null,
                role: null,
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
