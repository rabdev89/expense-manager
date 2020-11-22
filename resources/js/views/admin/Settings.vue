<template>
  <div class="main-content">
    <div class="page-header">
      <h3 class="page-title">Settings</h3>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active"><a href="#">Settings</a></li>
      </ol>
    </div>

    <div class="row">
      <div class="col-md-12 col-lg-8">
        <div class="card">
          <div class="card-header">
            <h6>Change Password</h6>
          </div>
          <div class="card-body">
            <form  @submit.prevent="validateBeforeSubmit">
              <div class="form-body">
                <div class="form-group row">
                <label for="name" class="col-sm-4 col-form-label">Name</label>
                <div class="col-sm-8">
                  <input
                    id="name"
                    type="text"
                    class="form-control"
                    placeholder="Name"
                    v-model="profile.name"
                  >
                </div>
                </div>

                <div class="form-group row">
                  <label for="email" class="col-sm-4 col-form-label">Email</label>
                  <div class="col-sm-8">
                    <input
                      id="email"
                      type="email"
                      class="form-control"
                      placeholder="Email"
                      v-model="profile.email"
                    >
                  </div>
                </div>
                <div class="form-group row">
                  <label for="password" class="col-sm-4 col-form-label">Password</label>
                  <div class="col-sm-8">
                    <input
                      id="password"
                      type="password"
                      class="form-control"
                      placeholder="Password"
                    >
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-primary">
                Save
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script type="text/babel">
import { TableComponent, TableColumn } from 'vue-table-component'
export default {
  components: {
    TableComponent,
    TableColumn,
  },
  data () {
    return {
      profile: []
    }
  },
  created() {
    axios.get(`/api/check`).then((response) => {
      this.profile = response.data
    })
  },
  methods: {
    async validateBeforeSubmit () {
      let self = this;
      this.$validator.validateAll().then((result) => {
        this.params = {
          name: this.profile.name,
          email: this.profile.email,
          password: this.profile.password
        }
        axios.put(`/api/user/profile`, this.params).then(function (response) {
          // handle success
          console.log(response);
          alert('Form Submitted!')
          self.$router.go();
        })
        .catch(function (error) {
          // handle error
          console.log(error);
          alert('Correct them errors!')
        })
      });
    }

  }
}
</script>
