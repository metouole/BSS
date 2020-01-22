<template>
    <div class="container">
        <div class="row mt-5">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Catalogue</h3>

                        <div class="card-tools">
                            <button class="btn btn-primary" @click="newModal">Add New Product <i class="fas fa-cogs"></i></button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0" >
                        <table class="table table-head-fixed">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Code Produit</th>
                                <th>Type</th>
                                <th>Désignation</th>
                                <th>Code Douanier</th>
                                <th>fournisseur</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="produit in products.data" :key="produit.id">
                                <td>{{produit.id}}</td>
                                <td><img class="img-circle" :src="getProductImage(produit.image)" style="height: 55px; width: 55px;" alt="product image"></td>
                                <td>{{produit.code_produit}}</td>
                                <td>{{produit.type}}</td>
                                <td>{{produit.designation}}</td>
                                <td>{{produit.code_douanier}}</td>
                                <td>{{produit.fournisseur}}</td>
                                <td>
                                    <a href="#" @click="editModal(produit)">
                                        Edit
                                        <i class="fas fa-user-edit"></i>
                                    </a>
                                    ||||||
                                    <a href="#" @click="deleteProduct(produit.id)">
                                        Delete
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination :data="products" @pagination-change-page="getResults"></pagination>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>

        <form @submit.prevent="editmode ? updateProduct() : createProduct() ">
        <!-- Modal -->
        <div class="modal fade" id="addproduct" aria-hidden="true" style="display: none;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        
                         <h4 v-show="editmode" class="modal-title">Mise à jour information Product</h4>
                        <h4 v-show="!editmode" class="modal-title">Créer un Product</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="image">image</label>
                            <input type="file" name="image" @change="uploadProductImage" placeholder="image"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('image') }">
                            <has-error :form="form" field="image"></has-error>
                        </div>

                        <div class="form-group">
                            <input v-model="form.name" type="text" name="name" placeholder="name"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                            <has-error :form="form" field="name"></has-error>
                        </div>

                        <div class="form-group">
                            <select v-model="form.category_id" type="text" name="category_id" placeholder="write your bio here" id="category_id"
                                    class="form-control" :class="{ 'is-invalid': form.errors.has('category_id') }">
                                <option value="">Select Category</option>
                                <option value="1">Carroserrie</option>
                                <option value="2">lingerie</option>
                                <option value="3">Peinture</option>
                            </select>
                            <has-error :form="form" field="category_id"></has-error>
                        </div>

                        <div class="form-group">
                            <input v-model="form.designation" type="text" name="designation" placeholder="designation"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('designation') }">
                            <has-error :form="form" field="designation"></has-error>
                        </div>

                        <div class="form-group">
                            <input v-model="form.type" type="text" name="type" placeholder="type"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                            <has-error :form="form" field="type"></has-error>
                        </div>

                        <div class="form-group">
                            <input v-model="form.position" type="text" name="position" placeholder="position"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('position') }">
                            <has-error :form="form" field="position"></has-error>
                        </div>

                        <div class="form-group">
                            <input v-model="form.couleur" type="text" name="couleur" placeholder="couleur"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('couleur') }">
                            <has-error :form="form" field="couleur"></has-error>
                        </div>

                        <div class="form-group">
                            <input v-model="form.longueur" type="text" name="longueur" placeholder="longueur"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('longueur') }">
                            <has-error :form="form" field="longueur"></has-error>
                        </div>

                        <div class="form-group">
                            <input v-model="form.largeur" type="text" name="largeur" placeholder="largeur"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('largeur') }">
                            <has-error :form="form" field="largeur"></has-error>
                        </div>

                        <div class="form-group">
                            <input v-model="form.hauteur" type="text" name="hauteur" placeholder="hauteur"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('hauteur') }">
                            <has-error :form="form" field="hauteur"></has-error>
                        </div>

                        <div class="form-group">
                            <input v-model="form.epaisseur" type="text" name="epaisseur" placeholder="epaisseur"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('epaisseur') }">
                            <has-error :form="form" field="epaisseur"></has-error>
                        </div>

                        <div class="form-group">
                            <input v-model="form.poids" type="text" name="poids" placeholder="poids"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('poids') }">
                            <has-error :form="form" field="poids"></has-error>
                        </div>

                        <div class="form-group">
                            <input v-model="form.prix_uni" type="text" name="prix_uni" placeholder="prix_uni"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('prix_uni') }">
                            <has-error :form="form" field="prix_uni"></has-error>
                        </div>

                        <div class="form-group">
                            <input v-model="form.code_produit" type="text" name="code_produit" placeholder="code_produit"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('code_produit') }">
                            <has-error :form="form" field="code_produit"></has-error>
                        </div>

                        <div class="form-group">
                            <input v-model="form.code_douanier" type="text" name="code_douanier" placeholder="code_douanier"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('code_douanier') }">
                            <has-error :form="form" field="code_douanier"></has-error>
                        </div>

                        <div class="form-group">
                            <input v-model="form.port_sortie" type="text" name="port_sortie" placeholder="port_sortie"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('port_sortie') }">
                            <has-error :form="form" field="port_sortie"></has-error>
                        </div>

                        <div class="form-group">
                            <input v-model="form.fournisseur" type="text" name="fournisseur" placeholder="fournisseur"
                                   class="form-control" :class="{ 'is-invalid': form.errors.has('fournisseur') }">
                            <has-error :form="form" field="fournisseur"></has-error>
                        </div>

                        <div class="form-group">
                          <textarea v-model="form.comment" type="comment" name="comment" placeholder="write your comment here" id="comment" 
                            class="form-control" :class="{ 'is-invalid': form.errors.has('comment') }"> </textarea>
                          <has-error :form="form" field="comment"></has-error>
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
                products : {},
                form: new Form({
                    id: '',
                    category_id: '',
                    name: '',
                    type: '',
                    designation: '',
                    position: '',
                    couleur: '',
                    longueur: '',
                    largeur: '',
                    hauteur: '',
                    epaisseur: '',
                    poids: '',
                    prix_uni: '',
                    code_produit: '',
                    code_douanier: '',
                    port_sortie: '',
                    fournisseur: '',
                    image: '',
                    comment: ''
                })
            }
        },

        methods: {

            getResults(page = 1) {
            axios.get('api/product?page=' + page)
                .then(response => {
                    this.products = response.data;
                });
            },

            getProductImage(image){
                let photo = "img/products/"+ image;
                return photo;
              },


            uploadProductImage(element){
                //console.log('uploading file');
                //console.log('uploading');
                let file = element.target.files[0];
                //console.log(file);
                let reader = new FileReader();//convert it to base64 file

                if(file['size'] < 2111775) {
                    reader.onloadend = (file) => {
                        //console.log('RESULT', reader.result)
                        this.form.image = reader.result;
                      }
                      reader.readAsDataURL(file);
                      } else {

                    if(file['type'] != 'png' && file['type'] != 'jpeg') {
                          Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Vous devez selectionner un fichier png ou jpeg',
                          })
                        } else {
                            Swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'You are uploading a large file',
                          })
                        }
                    }
             
                
            },

            updateProduct(){
                this.$Progress.start();
                //console.log('editing user');
                this.form.put('api/product/'+this.form.id)
                .then(() =>{

                    $('#addproduct').modal('hide');
                    Fire.$emit('insertProduct');
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



            editModal(product){
                this.editmode = true;
                this.form.reset();
                $('#addproduct').modal('show');
                this.form.fill(product);
            },

            newModal(){
                this.editmode = false;
                this.form.reset();
                $('#addproduct').modal('show');
            },

            deleteProduct(id) {
                Swal.fire({
                  title: 'Are you sure you want to delete the product?',
                  text: "You won't be able to revert this!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!'
                }).then((result) => {

                    if (result.value) {
                            //send request to the server
                            this.form.delete('api/product/'+id).then(() =>{
                                 
                                    Swal.fire(
                                      'Deleted!',
                                      'Your Product has been deleted.',
                                      'success'
                                    )
                                  Fire.$emit('insertProduct');
                            }).catch(() =>{
                                Swal("Failed!", "Il y'a un problème dans le traitement", "warning");
                            });
                        }
                     
                })

            },

            loadProducts(){
                axios.get('api/product').then(({data}) => (this.products = data));
            },

            createProduct(){

                this.$Progress.start();
                this.form.post('api/product')
                .then(() => {
                    Fire.$emit('insertProduct');
                    $('#addproduct').modal('hide');
                    Toast.fire({
                        icon: 'success',
                        title: 'Produit créé avec Succès'
                    });
                    this.$Progress.finish();
                })
                .catch(() =>{

                });
            
            }


        },


        created() {

            Fire.$on('searching', () =>{
              let query = this.$parent.search;
              axios.get('api/findProduct?q=' + query)
              .then((data) =>{
                this.products = data.data;
              })
              .catch(() =>{

              })
            });

            this.loadProducts();
            Fire.$on('insertProduct', () => {
                this.loadProducts();
            });
        }

        
    }
</script>
