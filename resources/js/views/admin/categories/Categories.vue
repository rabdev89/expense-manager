<template>
  <div class="main-content">
    <div class="page-header">
      <h3 class="page-title">Categories</h3>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Expense Management</a></li>
        <li class="breadcrumb-item active">Categories</li>
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
            <h6>All Categories</h6>
            <div class="card-actions" />
          </div>
          <div class="card-body">
            <table-component
              :data="getCategories"
              sort-by="row.name"
              sort-order="desc"
              table-class="table"
            >
              <table-column show="name" label="Name"/>
              <table-column show="description" label="Description"/>
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

                    <a class="btn btn-default btn-sm" @click="showEdit(row)">
                      <i class="icon-fa icon-fa-search"/> Edit
                    </a>

                    <a
                      class="btn btn-default btn-sm"
                      data-delete
                      data-confirmation="notie"
                      @click="deleteCategories(row.id)"
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
      ref="edit_form"
      modal-theme="light"
      overlay-theme="light"
    >
      <div class="card">
      <div class="card-header">
        <h6>Edit Categories</h6>
      </div>
      <div class="card-body">
        <form @submit.prevent="validateEditBeforeSubmit">
          <div :class="{'form-group' : true}">
            <label>Name </label>
            <input
              v-validate
              v-model="categories.name"
              :class="['form-control', {'is-invalid': errors.has('categories.name') }]"
              data-vv-rules="required"
              name="categories.name"
              data-vv-as="Name"
              type="text"
            >
            <div v-show="errors.has('categories.name')" class="invalid-feedback">
              {{ errors.first('categories.name') }}
            </div>
          </div>
          <div :class="{'form-group' : true}">
            <label>Description </label>
            <input
              v-validate
              v-model="categories.description"
              :class="['form-control', {'is-invalid': errors.has('categories.description') }]"
              data-vv-rules="required"
              name="categories.description"
              data-vv-as="Description"
              type="text"
            >
            <div v-show="errors.has('categories.description')" class="invalid-feedback">
              {{ errors.first('categories.description') }}
            </div>
          </div>

          <button class="btn btn-primary" type="submit">Submit</button>
        </form>
      </div>
    </div>
    </sweet-modal>

    <sweet-modal
      ref="create_form"
      modal-theme="light"
      overlay-theme="light"
    >
      <div class="card">
      <div class="card-header">
        <h6>Add Categories</h6>
      </div>
      <div class="card-body">
        <form @submit.prevent="validateBeforeSubmit">
          <div :class="{'form-group' : true}">
            <label>Name </label>
            <input
              v-validate
              v-model="form.name"
              :class="['form-control', {'is-invalid': errors.has('form.name') }]"
              data-vv-rules="required"
              name="form.name"
              data-vv-as="Name"
              type="text"
            >
            <div v-show="errors.has('form.name')" class="invalid-feedback">
              {{ errors.first('form.name') }}
            </div>
          </div>
          <div :class="{'form-group' : true}">
            <label>Description </label>
            <input
              v-validate
              v-model="form.description"
              :class="['form-control', {'is-invalid': errors.has('form.description') }]"
              data-vv-rules="required"
              name="form.description"
              data-vv-as="Description"
              type="text"
            >
            <div v-show="errors.has('form.description')" class="invalid-feedback">
              {{ errors.first('form.description') }}
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
        description: ''
      },
      categories: {}
    }
  },
  methods: {
    showEdit: function(cat) {
      const self = this;
      self.categories = cat;
      this.$refs.edit_form.open();
    },
    async validateEditBeforeSubmit (cat) {
      let self = this;
      this.$validator.validateAll().then((result) => {
        this.params = {
          name: self.categories.name,
          description: self.categories.description,
        }
        axios.put(`/api/categories/` + self.categories.id, this.params).then(function (response) {
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
    async validateBeforeSubmit () {
      let self = this;
      this.$validator.validateAll().then((result) => {
        this.params = {
          name: this.form.name,
          description: this.form.description,
        }
        axios.post(`/api/categories`, this.params).then(function (response) {
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
    async getCategories ({ page, filter, sort }) {
      try {
        const response = await axios.get(`/api/categories?page=${page}`)

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
    deleteCategories (id) {
      let self = this
      window.notie.confirm({
        text: 'Are you sure?',
        cancelCallback: function () {
          window.toastr['info']('Cancel')
        },
        submitCallback: function () {
          self.deleteCategoriesData(id)
        }
      })
    },
    async deleteCategoriesData (id) {
      try {
        let response = await window.axios.delete('/api/categories/' + id)
        this.categories = response.data
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
