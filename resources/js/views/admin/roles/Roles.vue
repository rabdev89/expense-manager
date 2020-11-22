<template>
  <div class="main-content">
    <div class="page-header">
      <h3 class="page-title">Roles</h3>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">User Management</a></li>
        <li class="breadcrumb-item active">Roles</li>
      </ol>
      <div class="page-actions">

        <button class="btn btn-primary" @click="$refs.dark_html_modal.open()">
          <i class="icon-fa icon-fa-plus"/> Add New
        </button>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header">
            <h6>All Roles</h6>
            <div class="card-actions" />
          </div>
          <div class="card-body">
            <table-component
              :data="getRoles"
              sort-by="row.id"
              sort-order="desc"
              table-class="table"
            >
              <table-column show="name" label="Name"/>
              <table-column show="display_name" label="Display Name"/>
              <table-column show="description" label="Description" />
              <table-column
                show="created_at"
                label="Created Date"
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
                      @click="deleteRoles(row.id)"
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
      ref="dark_html_modal"
      modal-theme="light"
      overlay-theme="light"
    >
      <div class="card">
      <div class="card-header">
        <h6>Add Role</h6>
      </div>
      <div class="card-body">
        <form @submit.prevent="validateBeforeSubmit">
          <div :class="{'form-group' : true}">
            <label>Display Name </label>
            <input
              v-validate
              v-model="newRoles.display_name"
              :class="['form-control', {'is-invalid': errors.has('newRoles.display_name') }]"
              data-vv-rules="required"
              name="newRoles.display_name"
              data-vv-as="Name"
              type="text"
            >
            <div v-show="errors.has('newRoles.display_name')" class="invalid-feedback">
              {{ errors.first('newRoles.display_name') }}
            </div>
          </div>
          <div :class="{'form-group' : true}">
            <label>Description </label>
            <input
              v-validate
              v-model="newRoles.description"
              :class="['form-control', {'is-invalid': errors.has('newRoles.description') }]"
              data-vv-rules="required"
              name="newRoles.description"
              data-vv-as="Description"
              type="text"
            >
            <div v-show="errors.has('newRoles.description')" class="invalid-feedback">
              {{ errors.first('newRoles.description') }}
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
      newRoles: {
        name: '',
        description: '',
      },
      roles: []
    }
  },
  install (Vue, options) {
    Vue.component('SweetModal', SweetModal)
  },
  methods: {
    async validateBeforeSubmit () {
      let self = this;
      this.$validator.validateAll().then((result) => {
        this.params = {
          name: this.newRoles.display_name,
          description: this.newRoles.description,
        }
        axios.post(`/api/roles`, this.params).then(function (response) {
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
    async getRoles ({ page, filter, sort }) {
      try {
        const response = await axios.get(`/api/roles`)
        return {
          data: response.data.data,
          pagination: {
            totalPages: response.data.last_page,
            currentPage: page,
            count: response.data.total
          }
        }
      } catch (error) {
        if (error) {
          window.toastr['error']('There was an error', 'Error')
        }
      }
    },
    deleteRoles (id) {
      let self = this
      window.notie.confirm({
        text: 'Are you sure?',
        cancelCallback: function () {
          window.toastr['info']('Cancel')
        },
        submitCallback: function () {
          self.deleteRolesData(id)
        }
      })
    },
    async deleteRolesData (id) {
      try {
        let response = await window.axios.delete('/api/roles/' + id)
        this.roles = response.data
        window.toastr['success']('Roles Deleted', 'Success')
      } catch (error) {
        if (error) {
          window.toastr['error']('There was an error', 'Error')
        }
      }
    }
  }
}
</script>
