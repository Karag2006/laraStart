<style>
  .widget-user .widget-user-header{
    background-position: center center;
    background-size: cover;
    height: 250px;
  }

</style>


<template>
    <div class="container">
        <div class="row card mt-3">
          <div class="col-md-12 mt-3">
            <!-- Widget: user widget style 1 -->
            <div class="box box-widget widget-user">
              <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="widget-user-header bg-black" style="background: url('./img/user-cover.jpg') center center;">
                <h3 class="widget-user-username">{{ form.name }}</h3>
                <h5 class="widget-user-desc">{{ form.type }}</h5>
              </div>
              <div class="widget-user-image">
                <img class="img-circle" src="" alt="User Avatar">
              </div>
              <div class="box-footer">
                <div class="row">
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header">3,200</h5>
                      <span class="description-text">SALES</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4 border-right">
                    <div class="description-block">
                      <h5 class="description-header">13,000</h5>
                      <span class="description-text">FOLLOWERS</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4">
                    <div class="description-block">
                      <h5 class="description-header">35</h5>
                      <span class="description-text">PRODUCTS</span>
                    </div>
                    <!-- /.description-block -->
                  </div>
                  <!-- /.col -->
                </div>
                <!-- /.row -->
              </div>
            </div>
            <!-- /.widget-user -->
          </div>

        </div>
        <div class="row card">
          <div class="col-md-12">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab" aria-expanded="false">Activity</a></li>
              <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab" aria-expanded="true">Settings</a></li>
            </ul>
            <div class="tab-content mt-3">
              <div class="tab-pane" id="activity">
              </div>

              <div class="tab-pane active" id="settings">
                <form class="form-horizontal">
                  <div class="form-group">
                    <label for="name" class="col-sm-2 control-label">Name</label>

                    <div class="col-sm-10">
                      <input v-model="form.name" type="text" class="form-control" id="name" placeholder="Name">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">Email</label>

                    <div class="col-sm-10">
                      <input v-model="form.email" type="email" class="form-control" id="email" placeholder="Email">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="bio" class="col-sm-2 control-label">Experience</label>

                    <div class="col-sm-10">
                      <textarea v-model="form.bio" class="form-control" id="bio" placeholder="Experience"></textarea>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="photo" class="col-sm-2 control-label">Picture</label>

                    <div class="col-sm-10">
                      <input type="file" class="form-control-file" id="photo" @change="updateProfile">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="password" class="col-sm-2 control-label">Password (leave empty if not changing)</label>

                    <div class="col-sm-10">
                      <input type="password" class="form-control" id="password" placeholder="Password">
                    </div>
                  </div>


                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button @click.prevent="updateInfo" type="submit" class="btn btn-success">Update</button>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                form: new Form({
                    id : '',
                    name: '',
                    email: '',
                    password: '',
                    type: '',
                    bio: '',
                    photo: '',
                }),
            }
        },

        mounted() {
            console.log('Component mounted.')
        },

        created() {
            axios.get("api/profile")
            .then(({ data }) => (this.form.fill(data)));
        },

        methods:{
          updateInfo(){
            this.form.patch('api/profile')
               .then(() => {

                })
                .catch(() => {

                })

          },
            updateProfile(e){
                //console.log('uploading');
                let file = e.target.files[0];

                let reader = new FileReader();
                reader.onloadend = (file) => {
                    //console.log('RESULT', reader.result)
                    this.form.photo = reader.result;
                }
                reader.readAsDataURL(file);
            }
        },
    }
</script>
