<template>
  <div v-if="fields.length > 0">
    <v-row v-for="field in fields" :key="field.id">
      <v-col cols="12">
        <!-- Number input -->
        <v-text-field
          v-if="field.type === 'NUMBER'"
          v-model="previousValues[field.id]"
          :label="field.title"
          :rules="[rules.number]"
          :disabled="disableFields"
        ></v-text-field>
        <!-- Text input -->
        <v-text-field
          v-else-if="field.type === 'STRING'"
          v-model="previousValues[field.id]"
          :label="field.title"
          :disabled="disableFields"
        ></v-text-field>
        <!-- Boolean input -->
        <v-switch
          v-else-if="field.type === 'BOOLEAN'"
          v-model="previousValues[field.id]"
          :label="field.title"
          :disabled="disableFields"
        ></v-switch>
        <!-- Date input -->
        <v-menu
          v-else-if="field.type === 'DATE'"
          v-model="pickers[field.id]"
          :close-on-content-click="false"
          :nudge-right="40"
          transition="scale-transition"
          offset-y
          min-width="290px"
        >
          <template v-slot:activator="{ on }">
            <v-text-field
              v-model="previousValues[field.id]"
              :label="field.title"
              prepend-icon="mdi-calendar"
              readonly
              v-on="on"
              :disabled="disableFields"
            ></v-text-field>
          </template>
          <v-date-picker
            v-model="previousValues[field.id]"
            @input="pickers[field.id] = false"
            :disabled="disableFields"
          ></v-date-picker>
        </v-menu>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import rules from '../../helpers/validations';
export default {
  props: {
    fields: {
      type: Array,
      default: () => []
    },
    previousValues: {
      type: Object,
      default: () => {}
    },
    status: {
      type: String,
      default: 'IDLE'
    }
  },
  data: () => ({
    pickers: {},
    rules
  }),
  computed: {
    disableFields() {
      return ['LOADING', 'SAVING'].includes(this.status);
    }
  },
  methods: {
    fillPreviousValue(fieldId) {
      return this.previousValues.findIndex(field => field.id === fieldId);
    }
  }
};
</script>
