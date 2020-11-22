<template>
  <div class="main-content">
    <div class="page-header">
      <h3 class="page-title">Users</h3>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">User Management</a></li>
        <li class="breadcrumb-item active">Users</li>
      </ol>
      <div class="page-actions">
        <button class="btn btn-primary" @click="$refs.create_form.open()">
          <i class="icon-fa icon-fa-plus"/> Add New
        </button>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
            <h6>All Users</h6>
            <div class="card-actions" />
          </div>
          <div class="card-body">
            <table-component
              :data="getUsers"
              sort-by="row.name"
              sort-order="desc"
              table-class="table"
            >
              <table-column show="name" label="Name"/>
              <table-column show="email" label="Email"/>
              <table-column show="role" label="Role"/>
              <table-column
                show="created_at"
                label="Registered On"
                data-type="date:YYYY-MM-DD h:i:s"
              />
              <table-column
                :sortable="false"
                :filterable="false"
                label=""
              >
                <template slot-scope="row">
                  <div class="table__actions">
                    <a
                      class="btn btn-default btn-sm"
                      data-delete
                      data-confirmation="notie"
                      @click="deleteUser(row.id)"
                    >
                      <i class="icon-fa icon-fa-trash"/> Delete
                    </a>
                  </div>
                </template>
              </table-column>
            </table-component>
          </div>
        </div>
      </div>
    </div>


    <sweet-modal
      ref="create_form"
      modal-theme="light"
      overlay-theme="light"
    >
      <div class="card">
      <div class="card-header">
        <h6>Add User</h6>
      </div>
      <div class="card-body">
        <form @submit.prevent="validateBeforeSubmit">
          <div :class="{'form-group' : true}">
            <label>Role </label>
            <select
              name="form.role"
              v-model="form.role"
              :class="['form-control', {'is-invalid': errors.has('form.amount') }]">
              <option
                v-bind:key="index"
                v-for="(rol,index) in roleOptions"
                :value="rol.id">{{ rol.display_name }}
              </option>
            </select>

            <div v-show="errors.has('form.amount')" class="invalid-feedback">
              {{ errors.first('form.amount') }}
            </div>
          </div>
          <div :class="{'form-group' : true}">
            <label>Name </label>
            <input
              v-validate
              v-model="form.name"
              :class="['form-control', {'is-invalid': errors.has('form.name') }]"
              data-vv-rules="required"
              name="form.name"
              data-vv-as="name"
              type="text"
            >
            <div v-show="errors.has('form.name')" class="invalid-feedback">
              {{ errors.first('form.name') }}
            </div>
          </div>
          <div :class="{'form-group' : true}">
            <label>Email </label>
            <input
              v-validate
              v-model="form.email"
              :class="['form-control', {'is-invalid': errors.has('form.email') }]"
              data-vv-rules="required"
              name="form.email"
              data-vv-as="email"
              type="text"
            >
            <div v-show="errors.has('form.email')" class="invalid-feedback">
              {{ errors.first('form.email') }}
            </div>
          </div>

          <button class="btn btn-primary" type="submit">Submit</button>
        </form>
      </div>
    </div>
    </sweet-modal>
  </div>
</template>

<script type="text/babel">
import { TableComponent, TableColumn } from 'vue-table-component'
import { SweetModal } from 'sweet-modal-vue'
export default {
  components: {
    TableComponent,
    TableColumn,
    SweetModal
  },
  data () {
    return {
      form: {
        name: '',
        email: '',
        role: ''
      },
      roleOptions: [],
      users: []
    }
  },
  created() {
    axios.get(`/api/roles`).then((response) => {
      this.roleOptions = response.data.data
    })
  },
  methods: {
    async validateBeforeSubmit () {
      let self = this;
      this.$validator.validateAll().then((result) => {
        this.params = {
          name: this.form.name,
          email: this.form.email,
          password: 'password',
          role: this.form.role
        }
        axios.post(`/api/users`, this.params).then(function (response) {
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
    },
    async getUsers ({ page, filter, sort }) {
      try {
        const response = await axios.get(`/api/users?page=${page}`)

        return {
          data: response.data.data,
          pagination: {
            totalPages: response.data.last_page,
            currentPage: page,
            count: response.data.count
          }
        }
      } catch (error) {
        if (error) {
          window.toastr['error']('There was an error', 'Error')
        }
      }
    },
    deleteUser (id) {
      let self = this
      window.notie.confirm({
        text: 'Are you sure?',
        cancelCallback: function () {
          window.toastr['info']('Cancel')
        },
        submitCallback: function () {
          self.deleteUserData(id)
        }
      })
    },
    async deleteUserData (id) {
      try {
        let response = await window.axios.delete('/api/admin/users/' + id)
        this.users = response.data
        window.toastr['success']('User Deleted', 'Success')
      } catch (error) {
        if (error) {
          window.toastr['error']('There was an error', 'Error')
        }
      }
    }
  }
}
</script>
