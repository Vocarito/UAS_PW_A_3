<template>
    <v-main class="Peminjaman">
        <h3 class="text-h4 font-weight-medium mb-1"> Peminjaman Buku </h3>

        <v-card>
            <v-card-title>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Search"
                    single-line
                    hide-details>
                </v-text-field>
            </v-card-title>
            <v-data-table :headers="headers" :items="staffs" :search="search" >
                <template v-slot:[`item.actions`]="{ item }"> 
                    <v-btn small @click="deleteHandler(item.id)" color="error"> delete</v-btn>
                </template>
            </v-data-table>
        </v-card>

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
    name: "Peminjaman",
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
                { text: "Nama Peminjam", align: "start", sortable: true, value: "nama_peminjam"},
                { text: "Judul Buku", value: 'judul_buku'},
                { text: "Tanggal Pinjam", value: 'tanggal_pinjam'},
                { text: "Tanggal Kembali", value: 'tanggal_kembali'},
                { text: "Action", value: 'actions'},
            ],
            staff: new FormData,
            staffs: [],
            form: {
                nama_peminjam: null,
                judul_buku: null,
                tanggal_pinjam: null,
                tanggal_kembali: null,
            },
            deleteId: ''
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
            var url = this.$api + '/peminjaman';
            this.$http.get(url, {
                headers: {
                    'Authorization' : 'Bearer ' + localStorage.getItem('token')
                }
            }).then(response => {
                this.staffs = response.data.data;
            })
        },

        // Hapus data
        deleteData() {
            //menghapus data
            var url = this.$api + '/peminjaman/' + this.deleteId;
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
                nama_peminjam: null,
                judul_buku: null,
                tanggal_pinjam: null,
                tanggal_kembali: null
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
