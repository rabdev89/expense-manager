<template>
  <div class="main-content">
    <div class="page-header">
      <h3 class="page-title">Expenses</h3>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item"><a href="#">Expense Management</a></li>
        <li class="breadcrumb-item active">Expenses</li>
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
            <h6>All Expenses</h6>
            <div class="card-actions" />
          </div>
          <div class="card-body">
            <table-component
              :data="getExpenses"
              sort-by="row.id"
              sort-order="desc"
              table-class="table"
            >
              <table-column show="category" label="Category"/>
              <table-column show="amount" label="Amount"/>
              <table-column show="entry_date" label="Entry Date" data-type="date:YYYY-MM-DD h:i:s"/>
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
                    <a class="btn btn-default btn-sm" @click="showEdit(row)">
                      <i class="icon-fa icon-fa-search"/> Edit
                    </a>
                    <a
                      class="btn btn-default btn-sm"
                      data-delete
                      data-confirmation="notie"
                      @click="deleteExpenses(row.id)"
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
        <h6>Edit Expenses</h6>
      </div>
      <div class="card-body">
        <form @submit.prevent="validateBeforeSubmit">
          <div :class="{'form-group' : true}">
            <label>Category </label>
            <select
              name="expenses.category"
              v-model="expenses.category"
              :class="['form-control', {'is-invalid': errors.has('expenses.category') }]">
              <option
                v-bind:key="index"
                v-for="(cat,index) in categoryOptions"
                :value="cat.id">{{ cat.name }}
              </option>
            </select>

            <div v-show="errors.has('expenses.category')" class="invalid-feedback">
              {{ errors.first('expenses.category') }}
            </div>
          </div>
          <div :class="{'form-group' : true}">
            <label>Amount </label>
            <input
              v-validate
              v-model="expenses.amount"
              :class="['form-control', {'is-invalid': errors.has('expenses.amount') }]"
              data-vv-rules="required"
              name="expenses.amount"
              data-vv-as="Amount"
              type="text"
            >
            <div v-show="errors.has('expenses.amount')" class="invalid-feedback">
              {{ errors.first('expenses.amount') }}
            </div>
          </div>
          <div :class="{'form-group' : true}">
            <label>Entry Date </label>
            <datepicker v-model="expenses.date" input-class="form-control" placeholder="Select Date" />
            <div v-show="errors.has('expenses.description')" class="invalid-feedback">
              {{ errors.first('expenses.description') }}
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
        <h6>Add Expenses</h6>
      </div>
      <div class="card-body">
        <form @submit.prevent="validateBeforeSubmit">
          <div :class="{'form-group' : true}">
            <label>Category </label>
            <select
              name="form.category"
              v-model="form.category"
              :class="['form-control', {'is-invalid': errors.has('form.category') }]">
              <option
                v-bind:key="index"
                v-for="(cat,index) in categoryOptions"
                :value="cat.id">{{ cat.name }}
              </option>
            </select>

            <div v-show="errors.has('form.category')" class="invalid-feedback">
              {{ errors.first('form.category') }}
            </div>
          </div>
          <div :class="{'form-group' : true}">
            <label>Amount </label>
            <input
              v-validate
              v-model="form.amount"
              :class="['form-control', {'is-invalid': errors.has('form.amount') }]"
              data-vv-rules="required"
              name="form.amount"
              data-vv-as="Amount"
              type="text"
            >
            <div v-show="errors.has('form.amount')" class="invalid-feedback">
              {{ errors.first('form.amount') }}
            </div>
          </div>
          <div :class="{'form-group' : true}">
            <label>Entry Date </label>
            <datepicker v-model="form.date" input-class="form-control" placeholder="Select Date" />
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
import Datepicker from 'vuejs-datepicker'
import Multiselect from 'vue-multiselect'
export default {
  components: {
    TableComponent,
    TableColumn,
    SweetModal,
    Multiselect,
    Datepicker
  },
  data () {
    return {
      form: {
        amount: '',
        date: '',
        category: ''
      },
      categoryOptions: [],
      expenses: []
    }
  },
  created() {
    // this.getCategories();
    axios.get(`/api/categories`).then((response) => {
      this.categoryOptions = response.data.data
    })

  },
  methods: {
    showEdit: function(cat) {
      const self = this;
      self.expenses = cat;
      this.$refs.edit_form.open();
    },
    async validateEditBeforeSubmit (cat) {
      let self = this;
      this.$validator.validateAll().then((result) => {
        this.params = {
          name: self.expenses.name,
          date: self.expenses.description,
          category: self.expenses.category
        }
        axios.put(`/api/expenses/` + self.expenses.id, this.params).then(function (response) {
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
        var d = new Date(this.form.date);

        this.params = {
          expense_amount: this.form.amount,
          expense_date: d.getUTCFullYear() +"-"+ (d.getUTCMonth()+1) +"-"+ d.getUTCDate(),
          expense_category: this.form.category
        }
        axios.post(`/api/expenses`, this.params).then(function (response) {
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
    async getExpenses ({ page, filter, sort }) {
      try {
        const response = await axios.get(`/api/expenses?page=${page}`)

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
    deleteExpenses (id) {
      let self = this
      window.notie.confirm({
        text: 'Are you sure?',
        cancelCallback: function () {
          window.toastr['info']('Cancel')
        },
        submitCallback: function () {
          self.deleteExpensesData(id)
        }
      })
    },
    async deleteExpensesData (id) {
      try {
        let response = await window.axios.delete('/api/expenses/' + id)
        this.expenses = response.data
        window.toastr['success']('Expenses Deleted', 'Success')
      } catch (error) {
        if (error) {
          window.toastr['error']('There was an error', 'Error')
        }
      }
    },

    getCategories: function () {
      axios.get(`/api/categories`).then((response) => {
        this.categoryOptions = response.data.data
      })
    },
  }
}
</script>
