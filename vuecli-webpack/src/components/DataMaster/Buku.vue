<template>
    <v-main class="Buku">
        <h3 class="text-h4 font-weight-medium mb-1"> Daftar Buku </h3>

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
            <v-data-table :headers="headers" :items="bukus" :search="search" >
                <template v-slot:[`item.actions`]="{ item }"> 
                    <v-btn small class="mr-2" @click="editHandler(item)" color="primary"> edit </v-btn>
                    <v-btn small @click="deleteHandler(item.id)" color="error"> delete</v-btn>
                </template>
            </v-data-table>
        </v-card>

        <v-dialog v-model="dialog" persistent max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="headline">{{ formTitle}} Buku</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-text-field v-model="form.judul_buku" label="Judul Buku" :rules="judulBukuRules" required></v-text-field>
                        <v-text-field v-model="form.pengarang" label="Pengarang" :rules="pengarangRules" required></v-text-field>
                        <v-text-field v-model="form.penerbit" label="Penerbit" :rules="penerbitRules" required></v-text-field>
                        <v-text-field v-model="form.thn_terbit" label="Tahun Terbit" :rules="tahunTerbitRules" required></v-text-field>
                        <v-text-field v-model="form.jenis_buku" label="Jenis Buku" :rules="jenisBukuRules" required></v-text-field>
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
                <v-card-text>Anda Yakin ingin menghapus buku ini?</v-card-text>
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
    name: "Buku",
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
                { text: "Judul Buku", align: "start", sortable: true, value: "judul_buku"},
                { text: "Pengarang", value: 'pengarang'},
                { text: "Penerbit", value: 'penerbit'},
                { text: "Tahun Terbit", value: 'thn_terbit'},
                 { text: "Jenis Buku", value: 'jenis_buku'},
                { text: "Actions", value: 'actions'},
            ],
            buku: new FormData,
            bukus: [],
            form: {
                judul_buku: null,
                pengarang: null,
                penerbit: null,
                thn_terbit: null,
                jenis_buku: null,
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
            var url = this.$api + '/buku';
            this.$http.get(url, {
                headers: {
                    'Authorization' : 'Bearer ' + localStorage.getItem('token')
                }
            }).then(response => {
                this.bukus = response.data.data;
            })
        },
        //simpan data Buku
        save() {
            this.buku.append('judul_buku', this.form.judul_buku);
            this.buku.append('pengarang', this.form.pengarang);
            this.buku.append('penerbit', this.form.penerbit);
            this.buku.append('thn_terbit', this.form.thn_terbit);
            this.buku.append('jenis_buku', this.form.jenis_buku);


            var url = this.$api +'/buku'
            this.load = true;
            this.$http.post(url, this.buku, {
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
                judul_buku : this.form.judul_buku,
                pengarang: this.form.pengarang,
                penerbit: this.form.penerbit,
                thn_terbit: this.form.thn_terbit,
                jenis_buku: this.form.jenis_buku

            };
            var url = this.$api + '/buku/' + this.editId;
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
            var url = this.$api + '/buku/' + this.deleteId;
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
            this.form.judul_buku = item.judul_buku;
            this.form.pengarang = item.pengarang;
            this.form.penerbit = item.penerbit;
            this.form.thn_terbit = item.thn_terbit;
            this.form.jenis_buku = item.jenis_buku;
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
                nama_kelas: null, 
                kode: null,
                biaya_pendaftaran: null,
                kapasitas: null
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
