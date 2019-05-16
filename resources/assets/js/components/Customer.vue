<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <button @click="initAddTask()" class="btn btn-primary btn-xs pull-right">
                            + Tambah Data
                        </button>
                        Data Customers
                    </div>
                    <div class="panel-body">
                        <table class="table table-bordered table-striped table-responsive" v-if="customers.length > 0">
                            <tbody>
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>Action</th>
                            </tr>
                            <tr v-for="(customer, index) in customers">
                                <td>{{ index + 1 }}</td>
                                <td>{{ customer.nama }}</td>
                                <td>{{ customer.alamat }}</td>
                                <td>
                                    <button @click="initUpdate(index)" class="btn btn-success btn-xs">Edit</button>
                                    <button @click="deleteTask(index)" class="btn btn-danger btn-xs">Delete</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="panel-footer">
                        Created by <b>Kievin Abdirrohman</b>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="add_task_model">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Tambah Customer Baru</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul>
                                <li v-for="error in errors">{{ error }}</li>
                            </ul>
                        </div>
                        <div class="form-group">
                            <label for="nama">Nama</label>
                            <input type="text" name="nama" id="nama" placeholder="Nama Customer..." class="form-control"
                                   v-model="customer.nama">
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea name="alamat" id="alamat" cols="30" rows="5" class="form-control"
                                      placeholder="Alamat Customer..." v-model="customer.alamat"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="createTask" class="btn btn-primary">Submit</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div class="modal fade" tabindex="-1" role="dialog" id="update_task_model">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Perbarui Data Customer</h4>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul>
                                <li v-for="error in errors">{{ error }}</li>
                            </ul>
                        </div>
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" placeholder="Nama Customer..." class="form-control"
                                   v-model="update_customer.nama">
                        </div>
                        <div class="form-group">
                            <label for="description">Alamat</label>
                            <textarea cols="30" rows="5" class="form-control"
                                      placeholder="Alamat Customer..." v-model="update_customer.alamat"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="updateTask" class="btn btn-primary">Submit</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

    </div>
</template>

<script>
    export default {
        data(){
            return {
                customer: {
                    nama: '',
                    alamat: ''
                },
                errors: [],
                customers: [],
                update_customer: {}
            }
        },
        mounted()
        {
            this.readTasks();
        },
        methods: {
            initAddTask()
            {
                $("#add_task_model").modal("show");
            },
            createTask()
            {
                axios.post('/customer', {
                    nama: this.customer.nama,
                    alamat: this.customer.alamat,
                })
                    .then(response => {
                        this.reset();
                        this.customers.push(response.data.customer);
                        $("#add_task_model").modal("hide");
                    })
                    .catch(error => {
                        this.errors = [];
                        if (error.response.data.errors.name) {
                            this.errors.push(error.response.data.errors.name[0]);
                        }
                        if (error.response.data.errors.description) {
                            this.errors.push(error.response.data.errors.description[0]);
                        }
                    });
            },
            reset()
            {
                this.customer.nama = '';
                this.customer.alamat = '';
            },
            readTasks()
            {
                axios.get('/customer')
                    .then(response => {
                        this.customers = response.data.customers;
                    });
            },
            initUpdate(index)
            {
                this.errors = [];
                $("#update_task_model").modal("show");
                this.update_customer = this.customers[index];
            },
            updateTask()
            {
                axios.patch('/customer/' + this.update_customer.id, {
                    nama: this.update_customer.nama,
                    alamat: this.update_customer.alamat,
                })
                    .then(response => {
                        $("#update_task_model").modal("hide");
                    })
                    .catch(error => {
                        this.errors = [];
                        if (error.response.data.errors.name) {
                            this.errors.push(error.response.data.errors.name[0]);
                        }
                        if (error.response.data.errors.description) {
                            this.errors.push(error.response.data.errors.description[0]);
                        }
                    });
            },
            deleteTask(index)
            {
                let conf = confirm("Anda Yakin ?");
                if (conf === true) {
                  axios.delete('/customer/' + this.customers[index].id)
                    .then(response => {
                        this.customers.splice(index, 1);
                    })
                    .catch(error => {
                    });
                }
            }
        }
    }
</script>
