<template>
  <v-data-table
    :headers="headers"
    :items="formattedSubscribers"
    :items-per-page="perPage"
    :loading="loading"
    :page.sync="page"
    :server-items-length="totalSubscribers"
    class="elevation-1 dt-min-width"
    :hide-default-footer="true"
    @pagination="onPagination"
    @update:options="opnUpdatedOptions"
  >
    <template v-slot:top>
      <v-toolbar flat>
        <v-toolbar-title>Subscribers</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-autocomplete
          v-model="selectedState"
          :items="subscriberStates"
          class="mt-6"
          label="State"
          color="secondary"
          multiple
        ></v-autocomplete>
        <v-btn
          color="secondary"
          class="ma-2"
          @click="create"
          :disabled="disableButtonsAndFields"
        >New Subscriber</v-btn>
        <v-dialog v-model="dialog" max-width="600px">
          <v-card>
            <v-card-title>
              <span class="headline">{{ formTitle }}</span>
            </v-card-title>

            <v-card-text>
              <v-container>
                <v-row>
                  <v-col cols="12">
                    <v-text-field
                      v-model="editedItem.name"
                      label="Name"
                      :rules="[rules.required]"
                      :disabled="disableButtonsAndFields"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12">
                    <v-text-field
                      v-model="editedItem.email"
                      label="Email"
                      :rules="[rules.required, rules.email]"
                      :disabled="disableButtonsAndFields"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <Fields :fields="fields" :previousValues="editedItem.fields" :status="status" />
              </v-container>
            </v-card-text>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="error" text @click="close" :disabled="disableButtonsAndFields">Cancel</v-btn>
              <v-btn
                color="primary"
                text
                @click="save"
                :disabled="disableButtonsAndFields"
                :loading="disableButtonsAndFields"
              >Save</v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-toolbar>
    </template>
    <template v-slot:item.action="{ item }">
      <v-icon small class="mr-2" @click="editItem(item)">mdi-pencil</v-icon>
      <v-icon small @click="deleteItem(item)">mdi-delete</v-icon>
    </template>
    <template v-slot:footer="{}">
      <v-pagination v-model="page" :length="pageCount" color="secondary"></v-pagination>
    </template>
  </v-data-table>
</template>

<script>
import { apiDomain, subscriberStates } from '../../config';
import Fields from './Fields';
import rules from '../../helpers/validations';
import crudMixin, { validStatus } from '../../mixins/crud';

export default {
  components: { Fields },
  mixins: [crudMixin],
  computed: {
    headers() {
      return [
        { text: 'Id', value: 'id' },
        {
          text: 'Name',
          align: 'left',
          value: 'name'
        },
        { text: 'Email', value: 'email' },
        { text: 'State', value: 'state' },
        ...this.fieldsHeader,
        { text: 'Actions', value: 'action', sortable: false }
      ];
    },
    fieldsHeader() {
      return this.fields.map((field, index) => {
        return {
          text: field.title,
          value: `fieldValues[${field.id}]`,
          sortable: false
        };
      });
    },
    formattedSubscribers() {
      return this.subscribers.map(subscriber => {
        const fieldValues = {};
        subscriber.fields.forEach(
          field => (fieldValues[field.id] = field.value)
        );
        return { ...subscriber, fieldValues };
      });
    }
  },
  data: () => ({
    fields: [],
    subscribers: [],
    editedItem: {},
    totalSubscribers: 0,
    status: validStatus.idle,
    page: 1,
    pageCount: 0,
    perPage: 10,
    crudTitle: 'Subscriber',
    rules,
    subscriberStates,
    selectedState: undefined,
    sortingCriteria: {}
  }),
  mounted() {
    this.loadFields();
    this.requestData();
  },
  methods: {
    onPagination(paginationInfo) {
      this.page = paginationInfo.page;
    },
    opnUpdatedOptions(optionsInfo) {
      if (optionsInfo.sortBy.length > 0) {
        this.sortingCriteria = {
          by: optionsInfo.sortBy[0],
          desc: optionsInfo.sortDesc[0]
        };
      } else {
        this.sortingCriteria = {};
      }
    },
    async requestData() {
      this.status = validStatus.loading;
      const {
        data: {
          data,
          meta: { total, per_page, last_page }
        }
      } = await axios
        .get(`${apiDomain}/subscriber`, {
          params: {
            page: this.page,
            state: this.selectedState,
            sorting: JSON.stringify(this.sortingCriteria),
          }
        })
        .catch(e => this.showMessage(e, false));
      this.subscribers = data;
      this.totalSubscribers = total;
      this.perPage = per_page;
      this.pageCount = last_page;
      if (this.page > last_page) {
        this.page = last_page;
      }
      this.status = validStatus.idle;
    },
    async loadFields() {
      const fields = await axios
        .get(`${apiDomain}/field`)
        .catch(e => this.showMessage(e, false));
      this.fields = fields.data.data;
    },
    editItem(item) {
      const formattedFields = {};
      item.fields.forEach(field => {
        formattedFields[field.id] = field.value;
      });
      this.editedItem = { ...item, fields: formattedFields };
      this.status = validStatus.editing;
    },
    async save() {
      const method = this.status === validStatus.editing ? 'patch' : 'post';
      const url = `${apiDomain}/subscriber/${
        this.status === validStatus.editing ? this.editedItem.id : ''
      }`;
      this.status = validStatus.saving;

      const response = await axios({
        method,
        url,
        data: this.editedItem
      }).catch(e => this.showMessage(e, false));
      if (response) {
        await this.requestData();
        this.showMessage('Subscriber saved successfully', true);
      }
    },
    create() {
      const formattedFields = {};
      this.fields.forEach(field => {
        formattedFields[field.id] = null;
      });
      this.editedItem = { fields: formattedFields };
      this.status = validStatus.creating;
    }
  },
  watch: {
    page() {
      this.requestData();
    },
    selectedState() {
      this.requestData();
    },
    sortingCriteria: {
      handler: function(value) {
          this.requestData();
      },
      deep: true
    }
  }
};
</script>
