<template>
  <v-data-table
    :headers="headers"
    :items="fields"
    :items-per-page="100"
    :loading="loading"
    class="elevation-1 dt-min-width"
    :hide-default-footer="true"
    :disable-sort="true"
  >
    <template v-slot:top>
      <v-toolbar flat>
        <v-toolbar-title>Fields</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-btn
          color="secondary"
          class="mb-2"
          @click="create"
          :disabled="disableButtonsAndFields"
        >New Field</v-btn>
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
                      v-model="editedItem.title"
                      label="Title"
                      :rules="[rules.required]"
                      :disabled="disableButtonsAndFields"
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12">
                    <v-select
                      :items="types"
                      label="Type"
                      :disabled="disableButtonsAndFields"
                      v-model="editedItem.type"
                    ></v-select>
                  </v-col>
                </v-row>
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
  </v-data-table>
</template>

<script>
import { apiDomain } from '../../config';
import rules from '../../helpers/validations';
import crudMixin, { validStatus } from '../../mixins/crud';

export default {
  mixins: [crudMixin],
  computed: {
    headers() {
      return [
        { text: 'Id', value: 'id' },
        {
          text: 'Title',
          align: 'left',
          value: 'title'
        },
        { text: 'Type', value: 'type' },
        { text: 'Actions', value: 'action' }
      ];
    }
  },
  data: () => ({
    fields: [],
    status: validStatus.idle,
    editedItem: {},
    types: [
      { text: 'Boolean', value: 'BOOLEAN' },
      { text: 'Date', value: 'DATE' },
      { text: 'Number', value: 'NUMBER' },
      { text: 'String', value: 'STRING' }
    ],
    rules,
    crudTitle: 'Field'
  }),
  mounted() {
    this.requestData();
  },
  methods: {
    async requestData() {
      this.status = validStatus.loading;
      const {
        data: { data }
      } = await axios
        .get(`${apiDomain}/field`)
        .catch(e => this.showMessage(e, false));
      this.fields = data;
      this.status = validStatus.idle;
    },
    editItem(item) {
      this.editedItem = item;
      this.status = validStatus.editing;
    },
    async save() {
      const method = this.status === validStatus.editing ? 'patch' : 'post';
      const url = `${apiDomain}/field/${
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
      this.editedItem = {};
      this.status = validStatus.creating;
    }
  }
};
</script>
