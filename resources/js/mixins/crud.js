import { apiDomain } from '../config';

export const validStatus = {
  idle: 'IDLE',
  loading: 'LOADING',
  saving: 'SAVING',
  creating: 'CREATING',
  editing: 'EDITING'
}

export default {
  computed: {
    disableButtonsAndFields() {
      return [validStatus.loading, validStatus.saving].includes(this.status);
    },
    loading() {
      return this.status === validStatus.loading;
    },
    formTitle() {
      return this.status === validStatus.editing
        ? `Edit ${this.crudTitle}`
        : `New ${this.crudTitle}`;
    },
    endPoint() {
      return this.crudTitle.toLocaleLowerCase();
    },
    dialog: {
      get() {
        return [
          validStatus.editing,
          validStatus.creating,
          validStatus.saving
        ].includes(this.status);
      },
      set() {
        this.status = validStatus.idle;
      }
    }
  },
  data: () => ({
    crudTitle: 'Field'
  }),
  methods: {
    async deleteItem(item) {
      this.status = validStatus.loading;
      await axios.delete(`${apiDomain}/${this.endPoint}/${item.id}`);
      await this.requestData();
      this.showMessage('Subscriber deleted successfully', true);
    },
    showMessage(message, success) {
      this.status = validStatus.idle;
      this.$toasted.show(`${success ? '✅' : '🚨️' } ${message}`);
    },
    close() {
      this.status = validStatus.idle;
    },
  }
}
