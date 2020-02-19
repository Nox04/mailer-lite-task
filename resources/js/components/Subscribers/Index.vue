<template>
  <v-data-table
    :headers="headers"
    :items="subscribers"
    :items-per-page="perPage"
    :loading="loading"
    :page.sync="page"
    :server-items-length="totalSubscribers"
    class="elevation-1 dt-min-width"
    :hide-default-footer="true"
    :disable-sort="true"
    @pagination="onPagination"
  >
    <template v-slot:top>
      <v-toolbar flat>
        <v-toolbar-title>Subscribers</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn color="secondary" class="mb-2" @click="create">New Subscriber</v-btn>
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
              <v-btn color="primary" text @click="save" :disabled="disableButtonsAndFields">Save</v-btn>
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
import { apiDomain } from '../../config';
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
        { text: 'State', value: 'state', filterable: true },
        ...this.fieldsHeader,
        { text: 'Actions', value: 'action' }
      ];
    },
    fieldsHeader() {
      return this.fields.map((field, index) => {
        return { text: field.title, value: `fields[${index}].value` };
      });
    }
  },
  data: () => ({
    fields: [],
    totalSubscribers: 0,
    subscribers: [],
    status: validStatus.idle,
    editedItem: {},
    page: 1,
    pageCount: 0,
    perPage: 10,
    rules,
    crudTitle: 'Subscriber'
  }),
  mounted() {
    this.loadFields();
    this.requestData();
  },
  methods: {
    onPagination(paginationInfo) {
      this.page = paginationInfo.page;
    },
    async requestData() {
      this.status = validStatus.loading;
      const {
        data: {
          data,
          meta: { total, per_page, last_page }
        }
      } = await axios.get(`${apiDomain}/subscriber?page=${this.page}`);
      this.subscribers = data;
      this.totalSubscribers = total;
      this.perPage = per_page;
      this.pageCount = last_page;
      this.status = validStatus.idle;
    },
    async loadFields() {
      const fields = await axios.get(`${apiDomain}/field`);
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
      });
      if (response) {
        await this.requestData();
        this.status = validStatus.idle;
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
    }
  }
};
</script>
