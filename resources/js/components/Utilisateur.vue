<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Liste des Utilisateurs</h3>

                        <div class="card-tools">
                            <button class="btn btn-primary" @click="newModal">Add New <i class="fas fa-user-plus"></i></button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0" >
                        <table class="table table-head-fixed">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="user in users.data" :key="user.id">
                                <td>{{user.id}}</td>
                                <td>{{user.name}}</td>
                                <td>{{user.email}}</td>
                                <td><span class="tag tag-success">{{user.type | upText}}</span></td>
                                <td>
                                    <a href="#" @click="editModal(user)">
                                        Edit
                                        <i class="fas fa-user-edit"></i>
                                    </a>
                                    ||||||
                                    <a href="#" @click="deleteUser(user.id)">
                                        Delete
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>


        <form @submit.prevent="editmode ? updateUser() : createUser() ">
        <!-- Modal -->
        <div class="modal fade" id="addnew" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 v-show="editmode" class="modal-title">Mise à jour information Utilisateur</h4>
                        <h4 v-show="!editmode" class="modal-title">Créer un Utilisateur</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <input v-model="form.name" type="text" name="name" placeholder="name"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                            <has-error :form="form" field="name"></has-error>
                        </div>

                        <div class="form-group">
                            <input v-model="form.email" type="email" name="email" placeholder="email"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                            <has-error :form="form" field="email"></has-error>
                        </div>


                        <div class="form-group">
                            <select v-model="form.type" type="type" name="type" placeholder="write your bio here" id="type"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                                <option value="">Select User Role</option>
                                <option value="admin">Admin</option>
                                <option value="user">Standard User</option>
                                <option value="author">Author</option>
                            </select>
                            <has-error :form="form" field="type"></has-error>
                        </div>

                        <div class="form-group">
                            <input v-model="form.password" type="password" name="password" placeholder="Password"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                            <has-error :form="form" field="password"></has-error>
                        </div>


                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                        <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
                <!-- /.modal-content -->

            </div>
            <!-- /.modal-dialog -->
        </div>
        </form>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                editmode: true,
                users : {},
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    password: '',
                    type: '',
                    photo: ''
                })
            }
        },

        methods: {

            updateUser(){
                this.$Progress.start();
                //console.log('editing user');
                this.form.put('api/user/'+this.form.id)
                .then(() =>{

                    $('#addnew').modal('hide');
                    Fire.$emit('AfterCreate');
                     Swal.fire(
                          'Updated!',
                          'informations bien mise à jour.',
                          'success'
                        )
                     this.$Progress.finish();
                     

                })
                .catch(() =>{
                    this.$Progress.fail();
                });
            },

            editModal(user){
                this.editmode = true;
                this.form.reset();
                $('#addnew').modal('show');
                this.form.fill(user);
            },

            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addnew').modal('show');
            },

            deleteUser(id){

                Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!'
                }).then((result) => {

                    if (result.value) {
                            //send request to the server
                            this.form.delete('api/user/'+id).then(() =>{
                                 
                                    Swal.fire(
                                      'Deleted!',
                                      'Your user has been deleted.',
                                      'success'
                                    )
                                  Fire.$emit('AfterCreate');
                            }).catch(() =>{
                                Swal("Failed!", "Il y'a un problème dans le traitement", "warning");
                            });
                        }
                     
                })

            },

            loadUsers(){
                axios.get('api/user').then(({data}) =>(this.users = data));
            },
            createUser(){
                this.$Progress.start();
                this.form.post('api/user')
                .then(() => {
                    Fire.$emit('AfterCreate');
                    $('#addnew').modal('hide');
                    Toast.fire({
                        icon: 'success',
                        title: 'Utilisateur créé avec Succès'
                    });
                    this.$Progress.finish();
                })
                .catch(() =>{

                });

            }
        },

        created() {
            this.loadUsers();
            Fire.$on('AfterCreate', () => {
                this.loadUsers();
            });
        }
    }
</script>
