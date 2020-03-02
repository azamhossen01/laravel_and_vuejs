<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">All Users</h3>

                <div class="card-tools">
                  <button class="btn btn-success" type="button"  data-toggle="modal" data-target="#addNewUser">Add New <i class="fas fa-user-plus"></i></button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>User</th>
                      <th>Date</th>
                      <th>Status</th>
                      <th>Reason</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>183</td>
                      <td>John Doe</td>
                      <td>11-7-2014</td>
                      <td><span class="tag tag-success">Approved</span></td>
                      <td>
                          <a href=""><i class="fas fa-edit"></i></a>
                          |
                          <a href=""><i class="fas fa-trash"></i></a>
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
                        <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="createUser()">
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
                        <button type="submit" class="btn btn-primary">Save</button>
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
            form : new Form({
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
          createUser(){
            this.form.post('api/user');
          }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
