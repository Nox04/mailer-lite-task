import Vue from 'vue';
import Vuetify from 'vuetify';
import { mount } from '@vue/test-utils';
import CustomField from '../components/Subscribers/Fields.vue';
import { singleField, multipleFields, emptyPreviousValues } from './__mocks__/FieldMocks';

Vue.use(Vuetify);

describe('Tests', () => {
  let vuetify;
  beforeEach(() => {
    vuetify = new Vuetify()
  })

  it('should mount without crashing', () => {
    const wrapper = mount(CustomField, {
      vuetify,
      propsData: {
        fields: multipleFields,
        previousValues: emptyPreviousValues,
      }
    })
    expect(wrapper.html()).toMatchSnapshot();
  })

  it('should render different types of inputs acording to the props', () => {
    const wrapper = mount(CustomField, {
      vuetify,
      propsData: {
        fields: multipleFields,
        previousValues: emptyPreviousValues,
      }
    })
    expect(wrapper.findAll('input[role="switch"]').length).toBe(1);
    expect(wrapper.findAll('input:not([readonly]):not([role="switch"])').length).toBe(2);

    // Select using readonly inputs for DatePickers
    expect(wrapper.findAll('input[readonly]').length).toBe(1);
  })

  it('should render an error message on invalid state', async () => {
    const wrapper = mount(CustomField, {
      vuetify,
      propsData: {
        fields: singleField,
        previousValues: {},
      }
    })

    wrapper.find('input').setValue('something not numeric')

    //Needs to wait 2 ticks because of Vuetify bug.
    await Vue.nextTick()
    await Vue.nextTick()

    expect(wrapper.find('.v-messages__message').text()).toBe('Invalid number.');
  })

  it('should update model on input', async () => {
    const wrapper = mount(CustomField, {
      vuetify,
      propsData: {
        fields: singleField,
        previousValues: {},
      }
    })

    wrapper.find('input').setValue(123)
    await Vue.nextTick()

    expect(wrapper.vm.previousValues).toStrictEqual({ "1": "123" });
  })

})
