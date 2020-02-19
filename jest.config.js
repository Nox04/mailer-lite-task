module.exports = {
  testRegex: 'resources/js/test/.*.spec.js$',
  moduleFileExtensions: [
    'js',
    'json',
    'vue'
  ],
  transform: {
    '^.+\\.js$': '<rootDir>/node_modules/babel-jest',
    '.*\\.(vue)$': '<rootDir>/node_modules/vue-jest'
  },
  snapshotSerializers: [
    '<rootDir>/node_modules/jest-serializer-vue'
  ],
  moduleNameMapper: {
    "^~/(.*)$": "<rootDir>/resources/js/$1"
  }
}
