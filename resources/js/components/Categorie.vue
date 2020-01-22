<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Liste des Catégories</h3>

                        <div class="card-tools">
                            <button class="btn btn-primary" @click="newModal">Add New Categorie<i class="fas fa-user-plus"></i></button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0" >
                        <table class="table table-head-fixed">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="category in categories.data" :key="category.ID">
                                <td>{{category.id}}</td>
                                <td>{{category.name}}</td>
                                <td>
                                    <a href="#" @click="editModal(category)">
                                        Edit
                                        <i class="fas fa-user-edit"></i>
                                    </a>
                                    ||||||
                                    <a href="#" @click="deleteCategory(category.id)" >
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

         <form @submit.prevent="editmode ? updateCategory() : createCategory() ">
        <!-- Modal -->
        <div class="modal fade" id="addcategory" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        
                        <h4 v-show="editmode" class="modal-title">Mise à jour  d'une category</h4>
                        <h4 v-show="!editmode" class="modal-title">Créer une category</h4>
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
                categories: {},
                form: new Form({
                    id: '',
                    name: ''
                })
            }
        },

        methods: {

            updateCategory(){

                this.$Progress.start();
                //console.log('editing user');
                this.form.put('api/category/'+this.form.id)
                .then(() =>{

                    $('#addcategory').modal('hide');
                    Fire.$emit('apresCreation');
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
                $('#addcategory').modal('show');
                this.form.fill(user);
            },

            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addcategory').modal('show');

            },



            createCategory() {
                this.$Progress.start();
                this.form.post('api/category')
                .then(() => {
                    Fire.$emit('apresCreation');
                    $('#addcategory').modal('hide');
                    Toast.fire({
                        icon: 'success',
                        title: 'Categorie créé avec Succès'
                    });
                    this.$Progress.finish();
                })
                .catch(() =>{

                });


            },

            deleteCategory(id){

                 Swal.fire({
                  title: 'Are you sure you will delete a category?',
                  text: "You won't be able to revert this!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!'
                }).then((result) => {

                    if (result.value) {
                            //send request to the server
                            this.form.delete('api/category/'+id).then(() =>{
                                 
                                    Swal.fire(
                                      'Deleted!',
                                      'Your category has been deleted.',
                                      'success'
                                    )
                                  Fire.$emit('apresCreation');
                            }).catch(() =>{
                                Swal("Failed!", "Il y'a un problème dans le traitement", "warning");
                            });
                        }
                     
                })
            },

            loadCategories(){
                axios.get('api/category').then(({data}) =>(this.categories = data));
            },

        },

        created() {
                this.loadCategories();
                Fire.$on('apresCreation', () => {
                this.loadCategories();
            });

        }
        
    }
</script>
