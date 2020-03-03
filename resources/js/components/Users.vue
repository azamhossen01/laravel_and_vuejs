<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">All Users</h3>

                <div class="card-tools">
                  <button class="btn btn-success" type="button" @click="addNew">Add New <i class="fas fa-user-plus"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>User</th>
                      <th>Email</th>
                      <th>Type</th>
                      <th>Registered At</th>
                      <th>Reason</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="user in users" :key="user.id">
                      <td>{{user.id}}</td>
                      <td>{{user.name}}</td>
                      <td>{{user.email}}</td>
                      <td>{{user.type | capitalize}}</td>
                      <td>{{user.created_at | my_date}}</td>
                      <td>
                          <a href="" @click.prevent="edit_user(user)"><i class="fas fa-edit"></i></a>
                          |
                          <a href="" @click.prevent="delete_user(user.id)"><i class="fas fa-trash masum"></i></a>
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



            <div class="modal fade" id="addNewUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="title">Add User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode ? updateUser() : createUser()">
                    <div class="modal-body">
                        <div class="form-group">
                        <input v-model="form.name" placeholder="Name" type="text" name="name"
                          class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                        <has-error :form="form" field="name"></has-error>
                      </div>
                        <div class="form-group">
                        <input v-model="form.email" placeholder="Email Address" type="email" name="email"
                          class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                        <has-error :form="form" field="email"></has-error>
                      </div>
                        <div class="form-group">
                        <textarea name="bio" id="bio" v-model="form.bio" placeholder="User Bio" cols="30" rows="3" class="form-control" :class="{'is-invalid':form.errors.has('bio')}"></textarea>
                        <has-error :form="form" field="bio"></has-error>
                      </div>
                        <div class="form-group">
                        <select name="type" id="type" v-model="form.type" class="form-control" :class="{'is-invalid':form.errors.has('type')}">
                          <option value="">Select User Role</option>
                          <option value="admin">Admin</option>
                          <option value="user">Standard User</option>
                          <option value="author">Author</option>
                        </select>
                        <has-error :form="form" field="type"></has-error>
                      </div>
                      <div class="form-group">
                      <input v-model="form.password" placeholder="User Password" type="password" name="password"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                      <has-error :form="form" field="password"></has-error>
                    </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="submit" v-bind:class="get_btn_class()" id="save_button">{{editmode?'Update':'Save'}}</button>
                    </div>
                    </form>
                    </div>
                </div>
            </div>
    </div>
</template>

<script>
    export default {
        data(){
          return {
            editmode : true,
            users : {},
            warning : 'btn btn-warning',
            info : 'btn btn-info',
            form : new Form({
              id : '',
              name : '',
              email : '',
              bio : '',
              password : '',
              photo : '',
              type : ''
            })
          }
        },
        methods : {
          get_btn_class(){
           return  this.editmode ? this.warning : this.info;
          },
          getUsers(){
            axios.get('api/user')
            .then(res => {
              console.log(res.data.data);
              this.users = res.data.data;
            });
          },
          addNew(){
            this.form.reset();
            this.editmode = false;
            $('#title').text('Add User');
            $('#addNewUser').modal('show');
          },
          createUser(){
            
            
            this.$Progress.start()
            this.form.post('api/user')
            .then(res => {
              this.form.reset();
              this.$Progress.finish()
              // this.getUsers();
              Fire.$emit('AfterCreated');
              Toast.fire({
                icon: 'success',
                title: 'User created successfully'
              })
              $('#addNewUser').modal('hide');
            })
            .catch((err)=>{
              console.log(err);
            });
          },
          updateUser(){
            this.$Progress.start()
            this.form.put('api/user/'+this.form.id)
            .then(()=>{
              Fire.$emit('AfterCreated');
              Toast.fire({
                icon: 'success',
                title: 'User updated successfully'
              })
              $('#addNewUser').modal('hide');
            });
          },
          delete_user(user_id){
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
              axios.delete('api/user/'+user_id)
            .then((res)=>{
              console.log(res.data.message);
              // this.getUsers();
              Fire.$emit('AfterCreated');
              Swal.fire(
                'Deleted!',
                res.data.message,
                'success'
              )
            });
              
            }
          })
            
          },
          edit_user(user){
            // alert(user_id);
            this.form.reset();
            this.editmode = true;
            $('#title').text('Edit User');
            this.form.fill(user);
            $('#addNewUser').modal('show');
            
          }

        },
        mounted() {
          this.getUsers();
            console.log('Component mounted.')
        },
        created(){
          Fire.$on('AfterCreated',()=>{
            this.getUsers();
          });
        }
    }
</script>
