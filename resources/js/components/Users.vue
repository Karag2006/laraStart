<template>
    <div class="container">
        <div class="row mt-5" v-if="$gate.isAdmin() || $gate.isAuthor()">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">User Management</h3>

              <div class="card-tools">
                <button class="btn btn-success" @click="newModal">Add User <i class="fas fa-user-plus fa-fw"></i> </button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody><tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>E-Mail</th>
                  <th>Type</th>
                  <th>Registered At</th>
                  <th>Modify</th>
                </tr>
                <tr v-for="user in users.data" :key="user.id">
                  <td>{{user.id}}</td>
                  <td>{{user.name}}</td>
                  <td>{{user.email}}</td>
                  <td>{{user.type | upText}}</td>
                  <td>{{user.created_at | myDate}}</td>
                  <td>
                    <a href="#" @click="editModal(user)">
                        <i class="fas fa-edit blue"></i>
                    </a>
                     /
                    <a href="#" @click="deleteUser(user.id)">
                        <i class="fas fa-minus-circle red"></i>
                    </a>
                  </td>
                </tr>
              </tbody></table>
            </div>
            <!-- /.box-body -->
            <div class="card-footer">
                <pagination :data="users"
                @pagination-change-page="getResults"></pagination>
            </div>
          </div>
          <!-- /.box -->
        </div>
      </div>
        <div v-if="!$gate.isAdmin() && !$gate.isAuthor()"><not-found></not-found></div>
    <!-- Modal -->
<div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addNewLabel" v-show="!editmode">Add New User</h5>
        <h5 class="modal-title" id="addNewLabel" v-show="editmode">Update {{ form.name }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form @submit.prevent="editmode ? updateUser() : createUser()">
      <div class="modal-body">

         <div class="form-group">
            <input v-model="form.name" type="text" name="name" placeholder="Name"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
            <has-error :form="form" field="name"></has-error>
        </div>

        <div class="form-group">
            <input v-model="form.email" type="email" name="email" placeholder="E-Mail Address"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
            <has-error :form="form" field="email"></has-error>
        </div>

        <div class="form-group">
            <textarea v-model="form.bio" type="bio" name="bio" id="bio" placeholder="Short bio for this user (Optional)"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
            <has-error :form="form" field="bio"></has-error>
        </div>

        <div class="form-group">
            <select v-model="form.type" name="type" id="type"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                <option value="">Select User Role</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
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
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success" v-show="!editmode">Create</button>
        <button type="submit" class="btn btn-primary" v-show="editmode">Update User</button>
      </div>
      </form>
    </div>
  </div>
</div>
    </div>
</template>

<script>
    export default {

        data() {
            return {
                editmode : false,
                users : {},
                form: new Form({
                    id : '',
                    name: '',
                    email: '',
                    password: '',
                    type: '',
                    bio: '',
                    photo: '',
                })
            }
        },

        methods: {
            getResults(page = 1){
                axios.get('api/user?page=' + page)
				.then(response => {
					this.users = response.data;
				});
            },
            newModal(){
                this.form.clear();
                this.form.reset();
                this.editmode = false;
                $('#addNew').modal('show')
            },

            editModal(user){
                this.form.clear();
                this.form.reset();
                this.editmode = true;
                $('#addNew').modal('show')
                this.form.fill(user);
            },
            createUser(){
                this.$Progress.start();
                this.form.post('api/user')
                .then(() => {
                    Fire.$emit('user_change');

                    $('#addNew').modal('hide')

                    toast.fire({
                        icon: 'success',
                        title: 'User created successfully'
                    });

                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                })

            },

            updateUser(){
              this.$Progress.start();
              this.form.patch('api/user/' + this.form.id)
                .then(() => {
                    Fire.$emit('user_change');
                    $('#addNew').modal('hide');

                    toast.fire({
                        icon: 'success',
                        title: 'User "'+ this.form.name +'" updated successfully'
                    });

                    this.$Progress.finish();

                })
                .catch(() => {
                    this.$Progress.fail();
                })

            },

            deleteUser(id){
                swal.fire({
                    title: 'Delete this User?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Delete!'
                    })
                .then((result) => {
                    if (result.value) {
                        this.form.delete('api/user/' + id)
                        .then(() => {
                            swal.fire(
                            'Deleted!',
                            'The User has been deleted.',
                            'success'
                            )
                        })
                        .catch(() => {
                            swal.fire("Failed!", "There was an Error", "warning");
                        });
                        Fire.$emit('user_change');
                    }
                })
            },

            loadUsers(){
                if(this.$gate.isAdmin() || this.$gate.isAuthor()){
                    axios.get("api/user").then(({data}) => (this.users = data));
                }
            },
        },

        created() {
            this.loadUsers();
            Fire.$on('user_change', () => {this.loadUsers()});
        }
    }
</script>
